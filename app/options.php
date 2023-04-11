<?php
namespace mihanpanel\app;
class options
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
        return get_option('users_can_register');
    }
    public static function activate_user_can_register()
    {
        return update_option('users_can_register', 1);
    }
    public static function deactivate_user_can_register()
    {
        return update_option('users_can_register', 0);
    }
    public static function get_panel_slug()
    {
        $option = get_option('mp_panelslug');
        return $option ? $option : 'panel';
    }
    public static function get_panel_url()
    {
        $slug = self::get_panel_slug();
        $panel_page_id = get_page_by_path($slug);
        return apply_filters('mihanpanel/panel_url', get_page_link($panel_page_id));
    }
    public static function get_login_slug()
    {
        $option = get_option('rwl_page');
        return $option ? $option : 'login';
    }
    public static function get_login_url($query_args=[], $redirect=false)
    {
        if(!$query_args || !is_array($query_args))
        {
            return esc_url(wp_login_url($redirect));
        }
        return esc_url(add_query_arg($query_args, wp_login_url($redirect)));
    }
    public static function get_logo()
    {
        $option = get_option('mp_logo_image');
        return $option ? $option : MW_MIHANPANEL_URL . 'img/logo.png';
    }
    public static function get_login_bg()
    {
        $option = get_option('mp_bg_image');
        return $option ? $option : MW_MIHANPANEL_URL . 'img/bg.jpg';
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
        return get_option('mp_new_user_email_notify');
    }
    public static function get_reset_password_email_subject()
    {
        return get_option('mp_reset_password_email_subject');
    }
    public static function get_reset_password_email_content()
    {
        return get_option('mp_reset_password_email_content');
    }
    public static function get_change_account_status_email_subject()
    {
        return get_option('mp_change_account_status_email_subject');
    }
    public static function get_change_account_status_email_content()
    {
        return get_option('mp_change_account_status_email_content');
    }
    static function disable_mihanpanel_fonts()
    {
        return get_option('mwpl_disable_font_family');
    }
    static function disable_mihanpanel_fontawesome()
    {
        return get_option('mwpl_disable_mwpl_fontawesome');
    }
    static function get_login_button_bg_color()
    {
        $color = get_option('login_button_color');
        return $color ? $color : '#5454ff';
    }
    static function get_login_logo_width()
    {
        $value = get_option('mp_logo_width', '120');
        return $value ? $value : '120';
    }
    static function get_login_logo_height()
    {
        $value = get_option('mp_logo_height', '120');
        return $value ? $value : '120';
    }
    static function get_offer_code_percentage_value()
    {
        return get_option('mp_offer_perc');
    }
    static function get_offer_code_text()
    {
        $percentage = self::get_offer_code_percentage_value();
        $value = apply_filters('mihanpanel/panel/off_code_text', sprintf(esc_html__('%1$s off code for your next purchase', 'mihanpanel'), $percentage));
        return $value;
    }

    public static function is_active_resend_account_activation_email(){
        return get_option('mp_resend_activation_email_link') == true;
    }
    public static function register_settings()
    {
        register_setting('mihanpanelsettings', 'rwl_page');
        register_setting('mihanpanelsettings', 'mp_account_activation_type');
        register_setting('mihanpanelsettings', 'mp_resend_activation_email_link');
        register_setting('mihanpanelsettings', 'mp_logo_image');
        register_setting('mihanpanelsettings', 'mp_logo_width');
        register_setting('mihanpanelsettings', 'mp_logo_height');
        register_setting('mihanpanelsettings', 'mp_bg_image');
        register_setting('mihanpanelsettings', 'login_button_color');
        register_setting('mihanpanelsettings-panel', 'mwpl_disable_font_family');
        register_setting('mihanpanelsettings-panel', 'mwpl_disable_mwpl_fontawesome');
        register_setting('mihanpanelsettings-panel', 'mp_disable_wordpress_bar');
        register_setting('mihanpanelsettings-panel', 'mp_use_mihan_avatar');
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