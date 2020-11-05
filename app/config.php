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
        add_option('mp_logo_image', MW_MIHANPANEL_URL . 'img/logo.png' );
        add_option('mp_disable_wordpress_bar', 1 );
        add_option('mp_panelslug','panel');
        //Create menu Table in DB
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tablename = $wpdb->prefix . 'mihanpaneltabs';
        $sql = "CREATE TABLE $tablename (
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
        //Create fields Table in DB
        $tablenamef = $wpdb->prefix . 'mihanpanelfields';
        $sqlf = "CREATE TABLE $tablenamef (
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
        if(!ob_get_status()['level'])
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
    public static function redirect_non_admin_after_login($user_login, $user)
    {
        if(users::is_admin_user())
        {
            return false;
        }
        if ( class_exists( 'Easy_Digital_Downloads' ) ){
            $edd_checkout_uri = edd_get_checkout_uri();
            if (wp_get_referer() != $edd_checkout_uri){
                wp_redirect(options::get_panel_url());				
                exit();
            }
        } else {
            wp_redirect(options::get_panel_url());
            exit();
        }
    }

    public static function redirect_not_logged_in_user_from_panel()
    {
        if (!is_user_logged_in())
        {
            $panel_slug = options::get_panel_slug();
            if (is_page($panel_slug))
            {
                wp_redirect(wp_login_url());
            }
        }
    }


    public static function filter_register_url($url)
    {
        $login_url = options::get_login_url('?action=register');
        return $login_url;
    }
    
    public static function user_account_activation_process()
    {
        $activation_code = isset($_GET['activation_code']) ? htmlspecialchars($_GET['activation_code']) : false;
        if (!$activation_code)
            return false;

        $validate_res = users::validate_activation_code($activation_code);
        $state = $validate_res ? 'completed' : 'error';
        wp_redirect(options::get_login_url('?activation_status=' . $state));
        exit();
    }

    static function handle_after_register_message($errors)
    {
        $activation_account_mode = options::get_account_activation_type();
        if(isset($errors->errors['registered']) && ($activation_account_mode == options::AUTO_ACTIVATION_MODE || $activation_account_mode == options::MANUAL_ACTIVATION_MODE))
        {
            switch($activation_account_mode)
            {
                case options::AUTO_ACTIVATION_MODE:
                    $new_message = esc_html__("Registration complete. Your account has been activated automatically.", 'mihanpanel');
                break;
                case options::MANUAL_ACTIVATION_MODE:
                    $new_message = esc_html__("Registration complete. Please wait for admin approval.", 'mihanpanel');
                break;
            }
            $login_url = wp_login_url();
            $append = sprintf(' <a href="%s">%s</a>', $login_url, esc_html__("Login Page", "mihanpanel"));
            $new_message .= $append;
            $errors->errors['registered'][0] = $new_message;
        }
        return $errors;
    }

    public static function account_activation_message_handler($message)
    {
        $activation_status = isset($_GET['activation_status']) ? $_GET['activation_status'] : false;
        $inactive_account_state = isset($_GET['action']) && $_GET['action'] == 'inactive_account' ? true : false;
        if ($inactive_account_state)
        {
            $activation_account_mode = options::get_account_activation_type();
            if ($activation_account_mode === options::MANUAL_ACTIVATION_MODE)
            {
                $message = esc_html__('Your account is disable! Please wait for admin approval.', 'mihanpanel');
            }else{
                $message = esc_html__('Your account is disable!', 'mihanpanel');
            }
            return '<p id="login_error">'.$message.'</p>';
        }
        if (!$activation_status)
            return $message;
        if ($activation_status == 'completed')
        {
            $res['message'] = esc_html__('Your Account successfully activated!', 'mihanpanel');
            $res['state'] = 'class="message"';
        }else{
            $res['message'] = esc_html__('Request is invalid!', 'mihanpanel');
            $res['state'] = 'id="login_error"';
        }
        return '<p '.$res['state'].'>'.$res['message'].'</p>';
    }

    public static function logout_non_active_account($user_login, $user)
    {
        if (users::get_activation_code($user->id))
        {
            wp_logout();
            wp_redirect(options::get_login_url('?action=inactive_account'));
            exit();
        }
    }

    static function redirect_to_option_panel_handler()
    {
        wp_redirect(admin_url('admin.php?page=mihanpanel'));
        exit;
    }
}