<?php
namespace mihanpanel\app;
class config
{
    public static function update_db_version($value)
    {
        return update_option('mihanpanel_db_version', $value);
    }
    public static function get_db_version()
    {
        return get_option('mihanpanel_db_version');
    }
    static function handle_update_version_process()
    {
        $olddbversion = self::get_db_version();
        if($olddbversion < MW_MIHANPANEL_DB_VERSION)
        {
            if ( $olddbversion < 2 ) {
                self::default_values_and_db_tables();
            }
            if ($olddbversion < 4)
            {
                if(get_option('mp_send_activation_link'))
                {
                    options::set_account_activation_type('link');
                    delete_option('mp_send_activation_link');
                }else{
                    options::set_default_activation_account_type();
                }
            }
            if($olddbversion < 5)
            {
                // create session table
                session::create_session_table();
            }
            if($olddbversion < 6)
            {
                // add meta data column to mihanpanelfields table
                global $wpdb;
                $tablename = $wpdb->prefix . 'mihanpanelfields';
                $command = "ALTER TABLE $tablename ADD meta longtext NOT NULL after priority;";
                $wpdb->query($command);
            }
            if($olddbversion < 7)
            {
                // add meta data column to mihanpaneltabs table
                global $wpdb;
                $tablename = $wpdb->prefix . 'mihanpaneltabs';
                $command = "ALTER TABLE {$tablename} ADD meta longtext NOT NULL after priority;";
                $wpdb->query($command);
            }
            do_action('mwpl_before_update_db_version', $olddbversion);
            self::update_db_version(MW_MIHANPANEL_DB_VERSION);
        }
    }
    static function default_values_and_db_tables()
    {
        //add default values to mihanpanel options when installing plugin
        add_option('mp_logo_width', '120');
        add_option('mp_logo_height', '120');
        add_option('mp_bg_image', MW_MIHANPANEL_URL . 'img/bg.jpg' );
        add_option('mp_logo_image', MW_MIHANPANEL_URL . 'img/login-logo.svg' );
        add_option('mp_disable_wordpress_bar', 1 );
        add_option('mp_panelslug','panel');

        self::create_tabs_table_in_database();
        self::create_fields_table_in_database();
    }
    static function create_tabs_table_in_database()
    {
        //Create menu Table in DB
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tablename = $wpdb->prefix . 'mihanpaneltabs';
        $sql = "CREATE TABLE IF NOT EXISTS $tablename (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name TINYTEXT NOT NULL,
                content TEXT NOT NULL,
                icon TINYTEXT NOT NULL,
                link TINYTEXT NOT NULL,
                priority SMALLINT NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        // if table is empty then fill that
        $result = $wpdb->get_results("SELECT id from $tablename WHERE `id` IS NOT NULL");
        if(count($result) == 0)
        {
            $wpdb->insert(
                $tablename,
                array(
                    'name' => esc_html__("Woocommerce Download Products", "mihanpanel"),
                    'content' => '[mihanpanel_woo_downloads]',
                    'icon' => 'fas fa-download',
                    'priority' => 1,
                )
            );
            $wpdb->insert(
                $tablename,
                array(
                    'name' => esc_html__("My Purchases", "mihanpanel"),
                    'content' => '[mp_woocommerce_orders]',
                    'icon' => 'fas fa-list',
                    'priority' => 1,
                )
            );
            $wpdb->insert(
                $tablename,
                array(
                    'name' => esc_html__("Support Telegram", "mihanpanel"),
                    'link' => 'http://t.me/mihanwp',
                    'icon' => 'fab fa-telegram',
                    'priority' => 4,
                )
            );
        }
    }
    static function create_fields_table_in_database()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        //Create fields Table in DB
        $tablenamef = $wpdb->prefix . 'mihanpanelfields';
        $sqlf = "CREATE TABLE IF NOT EXISTS $tablenamef (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                slug TINYTEXT NOT NULL,
                label TINYTEXT NOT NULL,
                required TINYTEXT NOT NULL,
                type TINYTEXT NOT NULL,
                priority SMALLINT NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sqlf );
    }
    public static function load_text_domain()
    {
        load_plugin_textdomain('mihanpanel', false, MW_MIHANPANEL_LANG);
    }
    static function init_session_scheduled_event()
    {
        if(!wp_next_scheduled('mihanpanel_session'))
        {
            wp_schedule_event(strtotime(date('Y-m-d 00:10:00', strtotime("+1 day"))), 'daily', 'mihanpanel_session');
        }
    }
    static function start_depends()
    {
        $ob_status = ob_get_status();
        if(!isset($ob_status['level']) || !$ob_status['level'])
        {
            ob_start();
        }
    }
    static function init_shortcode()
    {
        add_shortcode('mihanpanel', ['\mihanpanel\app\shortcode', 'panel']);
        
        // WooCommerce Orders Shortcode
        add_shortcode('mp_woocommerce_orders', ['\mihanpanel\app\shortcode', 'woocommerce_order']);
    
        if (!\mihanpanel\app\tools::is_edd_active()) {
            add_shortcode('download_history', function () {
                return esc_html__("You need to install easy digital downloads plugin for using this section!", "mihanpanel");
            });
        }

        add_shortcode( 'mihanpanel_woo_downloads', ['\mihanpanel\app\shortcode', 'woocommerce_downloads']);
    }
    public static function redirect_non_admin_after_login($redirectTo, $requested_redirect_to, $user)
    {
        if(users::is_admin_user($user->ID) || ($redirectTo && $redirectTo !== admin_url()))
        {
            return $redirectTo;
        }
        if ( class_exists( 'Easy_Digital_Downloads' ) ){
            $edd_checkout_uri = edd_get_checkout_uri();
            if (wp_get_referer() != $edd_checkout_uri){
                $redirectTo = options::get_panel_url();
            }
        } else {
            $redirectTo = options::get_panel_url();
        }
        return $redirectTo;
    }

    public static function redirect_not_logged_in_user_from_panel()
    {
        if (!is_user_logged_in())
        {
            if (is_page(options::get_panel_slug()))
            {
                wp_redirect(wp_login_url());
            }
        }
    }


    public static function filter_register_url($url)
    {
        $login_url = options::get_login_url(['action' => 'register']);
        return $login_url;
    }
    
    public static function user_account_activation_process()
    {
        $activation_code = isset($_GET['activation_code']) ? sanitize_text_field($_GET['activation_code']) : false;
        if (!$activation_code)
            return false;

        $validate_res = users::validate_activation_code($activation_code);
        $state = $validate_res ? 'completed' : 'error';
        wp_redirect(options::get_login_url(['activation_status' => $state]));
        exit();
    }


    static function redirect_to_option_panel_handler()
    {
        $url = tools::getOptionPanelUrl();
        $url = apply_filters('mwpl_before_redirect_to_option_panel_url', $url);
        wp_redirect($url);
        exit;
    }

    static function WpmlRegisterSingleStringText()
    {
        // get all user fields
        $userFields = user_fields::get_fields();
        // registerin strings
        foreach($userFields as $item)
        {
            $labelOptionSlug = 'user_fields_' . $item->slug;
            do_action('wpml_register_single_string', 'mihanpanel', $labelOptionSlug, $item->label);
        }

        // get all tabs field
        $menus = \mihanpanel\app\panel::get_tabs();
        foreach($menus as $menu)
        {
            $menuName = $menu->name;
            $labelOptionSlug = 'menu_tab_' . $menuName;
            do_action('wpml_register_single_string', 'mihanpanel', $labelOptionSlug, $menuName);
        }
    }
    static function WpmlTranslateUserFieldsLabel($labelValue, $fieldSlug)
    {
        $optionSlug = 'user_fields_' . $fieldSlug;

        $langCode = apply_filters('wpml_current_language', null);
        $labelValue = apply_filters('wpml_translate_single_string', $labelValue, 'mihanpanel', $optionSlug, $langCode);
        return $labelValue;
    }
    static function WpmlTranslatePanelMenuTabLabel($labelValue)
    {
        $optionSlug = 'menu_tab_' . $labelValue;

        $langCode = apply_filters('wpml_current_language', null);
        $labelValue = apply_filters('wpml_translate_single_string', $labelValue, 'mihanpanel', $optionSlug, $langCode);
        return $labelValue;
    }
    static function WpmlHandlePanelPageUrl($url)
    {
        $langCode = apply_filters('wpml_current_language', null);
        $url = apply_filters('wpml_permalink', $url, $langCode, true);
        return $url;
    }
    static function addMihanPanelMenusToAdminBarMenu($wpAdminBar)
    {
        if(is_admin())
        {
            $styles = [
                'width: 20px',
                'vertical-align: middle',
                'border-radius: 50%',
            ];
            $styles = implode('; ', $styles);

            $panelPageNode = [
                'title' => sprintf('<img src="%s" style="%s" /> %s',MW_MIHANPANEL_URL . 'img/mp-menu.png', $styles, __('Show user panel', 'mihanpanel')),
                'href' => options::get_panel_url(),
                'id' => 'mihanpanel_page',
                'parent' => 'site-name',
            ];
            $wpAdminBar->add_node($panelPageNode);
        }elseif(is_page( options::get_panel_slug()))
        {
            $styles = [
                'width: 20px',
                'vertical-align: middle',
                'border-radius: 50%',
            ];
            $styles = implode('; ', $styles);
            $optionPanelNode = [
                'title' => sprintf('<img src="%s" style="%s" /> %s',MW_MIHANPANEL_URL . 'img/mp-menu.png', $styles, __('MihanPanel options', 'mihanpanel')),
                'href' => tools::getOptionPanelUrl(),
                'id' => 'mihanpanel_option_panel_page',
            ];
            $wpAdminBar->add_node($optionPanelNode);
        }
    }
}