<?php
namespace mihanpanel\app;

use mihanpanel\app\form\admin_tabs;
use mihanpanel\app\form\admin_user_fields;

class admin_menu
{
    public static function init()
    {
        self::mw_settings();
        self::tabs();
        self::user_fields();
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
            'general' => __('Login & Register', 'mihanpanel'),
            'panel' => __('User Panel', 'mihanpanel'),
            'register' => __('Register Form', 'mihanpanel'),
            'notification' => __('Notifications', 'mihanpanel'),
            'login_with_sms' =>  __("Login with SMS", "mihanpanel"),
            'email' => __('Emails', 'mihanpanel'),
            'tools' => esc_html__('Tools', 'mihanpanel'),
        ];
        $settings = views::get('admin.settings');
        include $settings;
    }
    static function handle_tools_menu_submission()
    {
        if(isset($_POST['create_user_panel_page']))
        {
            $user_panel_page = array(
                'post_title' =>    __('User Panel','mihanpanel'),
                'post_content' => '[mihanpanel]',
                'post_status' => 'publish',
                'post_name' => 'panel',
                'post_type' => 'page'
            );
            $post_id = wp_insert_post($user_panel_page);
            $slug = get_post_field('post_name', $post_id);
            update_option('mp_panelslug', $slug);
        }
    }
    static function user_fields()
    {
        $user_fields_menu_title = __('User Fields', 'mihanpanel');
        $mw_user_field_menu = add_submenu_page('mihanpanel', $user_fields_menu_title, $user_fields_menu_title, 'manage_options', 'mihanpanel_fields', [__CLASS__, 'user_fields_c']);
        add_action("load-{$mw_user_field_menu}", ['mihanpanel\app\assets', 'load_user_field_menu_assets']);
    }
    static function user_fields_c()
    {
        if(!empty($_POST))
        {
            \mihanpanel\app\form\admin_user_fields::do($_POST);
        }
        ?>
        <div class="mihanpanel-admin">
            <?php
            do_action('mwpl_option_panel/before_render_user_fields_list');
            ?>
                <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                    <p>
                        <span><?php _e('Do you want to save changes?', 'mihanpanel')?></span>
                        <span><input mwpl_nonce="<?php echo wp_create_nonce('mwpl_ajax_update_user_fields_data')?>" class="mw_submit mw_ajax_update_fields_data" data-mw_type="user_field" type="submit" name="save_priority" value="<?php esc_attr_e('Yes', 'mihanpanel')?>"></span>
                    </p>
                </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <?php
            admin_user_fields::render_user_fields();
            ?>
            <div class="new_record_wrapper" style="display: none;">
                <h2><?php esc_html_e('Create new field', 'mihanpanel'); ?><span class="close_new_field_section action_btn"><?php esc_html_e('Close', 'mihanpanel')?></span></h2>
                <form method="post">
                    <?php admin_user_fields::render_new_record_fields()?>
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
        if (!empty($_POST)) {
            admin_tabs::do($_POST);
        }
        ?>
        <div class="mihanpanel-admin">
            <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                <p>
                    <span><?php esc_html_e('Do you want to save changes?', 'mihanpanel')?></span>
                    <span><input mwpl_nonce="<?php echo wp_create_nonce('mwpl_ajax_update_tabs_fields_data')?>" class="mw_submit mw_ajax_update_fields_data" data-mw_type="tabs" type="submit" name="save_priority" value="<?php esc_attr_e("yes", "mihanpanel")?>"></span>
                </p>
            </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <?php admin_tabs::render_fields()?>

            <div class="new_record_wrapper" style="display: none;">
                <h2><?php esc_html_e('Create new field', 'mihanpanel'); ?><span class="close_new_field_section action_btn"><?php esc_html_e('Close', 'mihanpanel')?></span></h2>
                <form method="post">
                    <?php admin_tabs::render_new_record_fields()?>
                </form>
            </div>
        </div>
        <?php
    }
}