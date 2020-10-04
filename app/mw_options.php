<?php
namespace mwplite\app;
class mw_options
{
    const AUTO_ACTIVATION_MODE = 'auto';
    const LINK_ACTIVATION_MODE = 'link';
    const MANUAL_ACTIVATION_MODE = 'manual';

    public static function get_activation_types()
    {
        return [
            self::AUTO_ACTIVATION_MODE  =>  __('Automatic activation', 'mihanpanel'),
            self::LINK_ACTIVATION_MODE  =>  __('Send Activation Link', 'mihanpanel'),
            self::MANUAL_ACTIVATION_MODE    =>  __('Manual Activation', 'mihanpanel')
        ];
    }
    public static function get_user_can_register()
    {
        $option = get_option('users_can_register');
        return $option;
    }

    public static function activate_user_can_register()
    {
        $res = update_option('users_can_register', 1);
        return $res;
    }

    public static function deactivate_user_can_register()
    {
        $res = update_option('users_can_register', 0);
        return $res;
    }
    public static function get_panel_slug()
    {
        $option = get_option('mp_panelslug');
        return $option ? $option : 'panel';
    }

    public static function get_panel_url()
    {
        $slug = self::get_panel_slug();
        $url = get_bloginfo('url') . '/' . $slug . '/';
        return $url;
    }

    public static function get_login_slug()
    {
        $option = get_option('rwl_page');
        return $option ? $option : 'login';
    }

    public static function get_login_url($location = false)
    {
        $slug = self::get_login_slug();
        $url = $location ? network_site_url($slug . '/' . $location) : network_site_url($slug);
        return $url;
    }
    public static function get_logo()
    {
        $option = get_option('mp_logo_image');
        return $option ? $option : MW_MIHANPANEL_LITE_URL . 'img/logo.png';
    }

    public static function get_login_bg()
    {
        $option = get_option('mp_bg_image');
        return $option ? $option : MW_MIHANPANEL_LITE_URL . 'img/bg.jpg';
    }

    public static function is_automate_activation_account()
    {
        $activation_account = self::get_account_activation_type();
        return $activation_account == self::AUTO_ACTIVATION_MODE ? true : false;
    }
    public static function is_send_activation_link_active()
    {
        $activation_type = self::get_account_activation_type();
        return $activation_type == self::LINK_ACTIVATION_MODE ? true : false;
    }
    public static function is_manual_activation_account_type()
    {
        $activation_type = self::get_account_activation_type();
        return $activation_type == self::MANUAL_ACTIVATION_MODE ? true : false;
    }

    public static function set_default_activation_account_type($default = self::AUTO_ACTIVATION_MODE)
    {
        return self::set_account_activation_type($default);
    }
    public static function get_account_activation_type()
    {
        $type = get_option('mp_account_activation_type');
        return $type && in_array($type, array_keys(self::get_activation_types())) ? $type : self::set_default_activation_account_type();
    }

    public static function set_account_activation_type($type)
    {
        if (!in_array($type, array_keys(self::get_activation_types())))
            return false;
        $res = update_option('mp_account_activation_type', $type);
        return $res ? $type : false;
    }
    public static function get_email_notify_content()
    {
        $option = get_option('mp_new_user_email_notify');
        return $option;
    }

    public static function get_reset_password_email_subject()
    {
        $option = get_option('mp_reset_password_email_subject');
        return $option;
    }

    public static function get_reset_password_email_content()
    {
        $option = get_option('mp_reset_password_email_content');
        return $option;
    }
    
    public static function get_change_account_status_email_subject()
    {
        $option = get_option('mp_change_account_status_email_subject');
        return $option;
    }

    public static function get_change_account_status_email_content()
    {
        $option = get_option('mp_change_account_status_email_content');
        return $option;
    }
    public static function register_settings()
    {

        register_setting('mihanpanelsettings', 'rwl_page');
        register_setting('mihanpanelsettings', 'mp_account_activation_type');
        register_setting('mihanpanelsettings', 'mp_logo_image');
        register_setting('mihanpanelsettings', 'mp_logo_width');
        register_setting('mihanpanelsettings', 'mp_logo_height');
        register_setting('mihanpanelsettings', 'mp_bg_image');
        register_setting('mihanpanelsettings', 'login_button_color');
        
        register_setting('mihanpanelsettings-panel', 'mp_disable_wordpress_bar');
        register_setting('mihanpanelsettings-panel', 'mp_panelslug');
        register_setting('mihanpanelsettings-panel', 'mp_offer_code');
        register_setting('mihanpanelsettings-panel', 'mp_offer_perc');


        register_setting('mihanpanelsettings-register', 'mp_send_emails_from');


        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message2');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message3');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message4');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message5');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message6');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message7');
        register_setting('mihanpanelsettings-notification', 'mp_dashboard_message8');
        
        register_setting('mihanpanelsettings-email', 'mp_new_user_email_notify');
        register_setting('mihanpanelsettings-email', 'mp_reset_password_email_subject');
        register_setting('mihanpanelsettings-email', 'mp_reset_password_email_content');
        register_setting('mihanpanelsettings-email', 'mp_change_account_status_email_subject');
        register_setting('mihanpanelsettings-email', 'mp_change_account_status_email_content');

    }
}