<?php

namespace mihanpanel\app;

use mihanpanel\app\form\admin_user_fields;

class admin_menu
{
    public static function init()
    {
        self::tabs();
        self::user_fields();
        self::notifications();
        self::blockedIpsMenu();
    }
    static function add_main_menu_page()
    {
        self::mw_settings();
    }

    public static function mw_settings()
    {
        $title = apply_filters('mwpl_option_panel_title', __('MihanPanel', 'mihanpanel'));
        $mw_settings_page = add_menu_page($title, $title, 'manage_options', 'mihanpanel', [__CLASS__, 'settings_c'], MW_MIHANPANEL_URL . 'img/mp-menu.png');
        add_action("load-{$mw_settings_page}", ['mihanpanel\app\assets', 'load_admin_panel_assets']);
    }

    public static function settings_c()
    {
        $menu_items = [
            'general' => [
                'title' => __('Login & Register', 'mihanpanel'),
                'icon' => 'login',
            ],
            'panel' => [
                'title' => __('User Panel', 'mihanpanel'),
                'icon' => 'user',
            ],
            'register' => [
                'title' => __('Register Form', 'mihanpanel'),
                'icon' => 'clipboard',
            ],
            'notification' => [
                'title' => __('Notifications', 'mihanpanel'),
                'icon' => 'bell',
            ],
            'professional_login' => [
                'title' =>  __("Professional login options", "mihanpanel"),
                'icon' => 'setting-2',
            ],
            'security' => [
                'title' => __('Security', 'mihanpanel'),
                'icon' => 'lock',
            ],
            'email' => [
                'title' => __('Emails', 'mihanpanel'),
                'icon' => 'mail',
            ],
            'tools' => [
                'title' => esc_html__('Tools', 'mihanpanel'),
                'icon' => 'setting-4',
            ],
        ];
        $settings = views::get('admin.settings');
        include $settings;
    }

    public static function get_settings_tab_url($tab = 'general'){
        return add_query_arg([
            'page' => 'mihanpanel',
            'tab' => $tab
        ], admin_url('admin.php'));
    }

    static function handle_tools_menu_submission()
    {
        if (isset($_POST['create_user_panel_page'])) {
            $user_panel_page = array(
                'post_title' =>    __('User Panel', 'mihanpanel'),
                'post_content' => '[mihanpanel]',
                'post_status' => 'publish',
                'post_name' => 'panel',
                'post_type' => 'page'
            );
            $post_id = wp_insert_post($user_panel_page);
            $slug = get_post_field('post_name', $post_id);
            update_option('mp_panelslug', $slug);
        }
        if (isset($_POST['create_mihanpanel_database_tables'])) {
            // create tabs table
            config::create_tabs_table_in_database();

            // add meta data column to mihanpaneltabs table
            global $wpdb;
            $tabsTableName = $wpdb->prefix . 'mihanpaneltabs';

            // check if has not meta col
            $tabsCols = $wpdb->get_col("DESCRIBE {$tabsTableName}");
            if (!in_array('meta', $tabsCols)) {
                $command = "ALTER TABLE {$tabsTableName} ADD meta longtext NOT NULL after priority;";
                $wpdb->query($command);
            }

            // create fields table
            config::create_fields_table_in_database();

            // add meta data column to mihanpanelfields table
            $fieldsTableName = $wpdb->prefix . 'mihanpanelfields';
            $fieldsCols = $wpdb->get_col("DESCRIBE {$fieldsTableName}");
            if (!in_array('meta', $fieldsCols)) {
                $command = "ALTER TABLE {$fieldsTableName} ADD meta longtext NOT NULL after priority;";
                $wpdb->query($command);
            }

            // create session table
            session::create_session_table();

            // do_action to handle pro version tables
            do_action('after_create_default_database_tables_tools_page');
        }
        do_action('after_submit_tools_menu_form');
    }
    static function user_fields()
    {
        $user_fields_menu_title = __('User Fields', 'mihanpanel');
        $mw_user_field_menu = add_submenu_page('mihanpanel', $user_fields_menu_title, $user_fields_menu_title, 'manage_options', 'mihanpanel_fields', [__CLASS__, 'user_fields_c']);
        add_action("load-{$mw_user_field_menu}", ['mihanpanel\app\assets', 'load_user_field_menu_assets']);
    }
    static function user_fields_c()
    {
        if (!empty($_POST)) {
            \mihanpanel\app\form\admin_user_fields::do();
        }
?>
        <div class="mihanpanel-admin">
            <?php if($notice = \mihanpanel\app\notice::once_get_notice()): ?>
                <p class="alert <?php echo esc_attr($notice['type'])?>"><?php echo esc_html($notice['msg'])?></p>
            <?php endif; ?>
            <?php
            do_action('mwpl_option_panel/before_render_user_fields_list');
            ?>
            <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                <p>
                    <span><?php _e('Do you want to save changes?', 'mihanpanel') ?></span>
                    <span><input mwpl_nonce="<?php echo esc_attr(wp_create_nonce('mwpl_ajax_update_user_fields_data')) ?>" class="mw_submit mw_ajax_update_fields_data" data-mw_type="user_field" type="submit" name="save_priority" value="<?php esc_attr_e('Yes', 'mihanpanel') ?>"></span>
                </p>
            </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <?php
            admin_user_fields::render_user_fields();
            ?>
            <div class="new_record_wrapper" style="display: none;">
                <h2><?php esc_html_e('Create new field', 'mihanpanel'); ?><span class="close_new_field_section action_btn"><?php esc_html_e('Close', 'mihanpanel') ?></span></h2>
                <form method="post">
                    <?php admin_user_fields::render_new_record_fields() ?>
                </form>
            </div>
        </div>
        <?php
    }
    static function tabs()
    {
        $mw_menus_management_title = __('Panel Menus', 'mihanpanel');
        $mw_menus_management = add_submenu_page('mihanpanel', $mw_menus_management_title, $mw_menus_management_title, 'manage_options', 'mihanpanel_menus', [__CLASS__, 'tabs_c']);
        add_action("load-{$mw_menus_management}", ['mihanpanel\app\assets', 'load_menus_management_assets']);
    }
    static function tabs_c()
    {
        if (tools::isProVersion()) {
            \mihanpanel\pro\app\admin_menu::handle_tabs_menu_content();
        } else {
            $msgStyles = [
                'position' =>  'absolute',
                'top' =>  '50%',
                'left' =>  '50%',
                'transform' =>  'translate(-50%, -50%)',
                'background' =>  'white',
                'padding' =>  '20px 100px',
                'border-radius' => '10px',
                'border' =>  '2px solid #df6969',
                'color' => '#9f4848',
                'box-shadow' => '0 0 10px #9f4848a1',
                'width' => '400px',
                'text-align' => 'center',
                'font-weight' => 'bold',
            ];
            $msgStyle = '';
            foreach ($msgStyles as $key => $value) {
                $msgStyle .= sprintf('%s: %s;', $key, $value);
            }
            $imgUrl = is_rtl() ? \mihanpanel\app\assets::get_image_url('tabs-menu-view') : \mihanpanel\app\assets::get_image_url('tabs-menu-view-ltr');
        ?>
            <div class="mihanpanel-admin" style="position: relative">
                <img style="filter: blur(2px) grayscale(100)" width="100%" src="<?php echo esc_attr($imgUrl) ?>" alt="">
                <div style="<?php echo esc_attr($msgStyle) ?>"><?php esc_html_e('This section is active in MihanPanel pro', 'mihanpanel') ?></div>
            </div>
        <?php
        }
    }
    static function notifications()
    {
        $menuTitle = __('Notifications', 'mihanpanel');
        $notificationsSubMenu = add_submenu_page('mihanpanel', $menuTitle, $menuTitle, 'manage_options', 'mihanpanel_notifications', [__CLASS__, 'notifications_c']);
        add_action("load-{$notificationsSubMenu}", ['mihanpanel\app\assets', 'loadAdminNotificationMenusAssets']);
    }
    static function notifications_c()
    {
        if (tools::isProVersion()) {
            \mihanpanel\pro\app\admin_menu::handleNotificationsMenuContent();
            return;
        }
        $stylesData = [
            "background" => "#cdcde8",
            "width" => "400px",
            "height" => "160px",
            "border-radius" => "15px",
            "margin" => "0 auto",
            "color" => "black",
            "display" => "flex",
            "justify-content" => "center",
            "align-items" => "center",
            "font-size" => "16px",
            "text-align" => "center",
            "border" => "3px solid #7c7cbd30",
        ];
        $styles = '';
        foreach ($stylesData as $key => $value) {
            $styles .= $key . ': ' . $value . '; ';
        }
        ?>
        <div style="<?php echo esc_attr($styles) ?>">
            <span><?php printf(__('This feature is accessible only in %s.', 'mihanpanel'), sprintf('<a target="_blank" href="%s">%s</a>', \mihanpanel\app\tools::get_pro_version_link(), __('MihanPanel Pro', 'mihanpanel'))); ?></span>
        </div>
<?php
    }

    static function blockedIpsMenu()
    {
        add_submenu_page('mihanpanel', __('Blocked ip', 'mihanpanel'), __('Blocked ip', 'mihanpanel'), 'manage_options', 'mihanpanel_blocked_ips', [__CLASS__, 'blockedIpsMenuContent']);
    }
    static function blockedIpsMenuContent()
    {
        $blockedIpsList = login_guard::getBlockedIps();
        $failedAttemptsTelorance = options::getLoginGuardFailedAttemptCount();

        $whiteList = ['list', 'delete', 'delete_all'];
        $type = isset($_GET['action']) && in_array($_GET['action'], $whiteList) ? $_GET['action'] : $whiteList[0];

        switch ($type) {
            case 'delete':
                $itemID = isset($_GET['id']) ? intval($_GET['id']) : false;
                $nonce = isset($_GET['mwpl_nonce']) && $_GET['mwpl_nonce'] ? sanitize_text_field($_GET['mwpl_nonce']) : false;
                if($nonce && wp_verify_nonce($nonce, 'mwpl_delete_blocked_ip_item'))
                {
                    login_guard::deleteIpItem($itemID);
                }
                $newLocation = esc_url(add_query_arg(['page' => 'mihanpanel_blocked_ips'], admin_url('admin.php')));
                wp_safe_redirect($newLocation);
                exit;
                break;
            case 'delete_all':
                $nonce = isset($_GET['mwpl_nonce']) && $_GET['mwpl_nonce'] ? sanitize_text_field($_GET['mwpl_nonce']) : false;
                if($nonce && wp_verify_nonce($nonce, 'mwpl_delete_blocked_ip_all_items'))
                {
                    login_guard::truncateIpListTable();
                }
                $newLocation = esc_url(add_query_arg(['page' => 'mihanpanel_blocked_ips'], admin_url('admin.php')));
                wp_safe_redirect($newLocation);
                exit;
                break;
            case 'list':
            default:
                $allItemsCount = $blockedIpsList ? count($blockedIpsList) : 0;
                $allItemsCount = sprintf(__('%d item', 'mihanpanel'), $allItemsCount);
        }

        $view = views::get('admin.blocked_ips_menu');
        $view ? include_once $view : null;
    }
}
