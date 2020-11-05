<?php
namespace mihanpanel\app;
class tools
{

    public static function vd($data)
    {
        echo "<pre style='all: revert;'>";
        var_dump($data);
        echo "</pre>";
    }
    static function is_empty_array($data)
    {
        if(is_array($data))
        {
            // check array
            foreach($data as $value)
            {
                return self::is_empty_array($value);
            }
        }
        return empty($data);
    }
    static function get_date($format, $timestamp='')
    {
        $gmt = get_option('gmt_offset');
        $timestamp = $timestamp ? $timestamp : strtotime('now');
        $timestamp = $timestamp + ($gmt * 3600);
        return date($format, $timestamp);
    }
    public static function is_plugin_active($plugin_name)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        return is_plugin_active($plugin_name) ? true : false;
    }

    public static function is_woocommerce_active()
    {
        return class_exists('woocommerce');
    }
    static function is_edd_active()
    {
        return self::is_plugin_active('easy-digital-downloads/easy-digital-downloads.php');
    }
    static function is_awesome_support_active()
    {
        return class_exists('Awesome_Support');
    }

    public static function get_plugin_version()
    {
        $file_data = get_file_data(WP_MIHANPANEL_MAIN_APP, ['version' => 'version']);
        return isset($file_data['version']) ? $file_data['version'] : false;
    }
    static function do_redirect()
    {
        $query = add_query_arg([]);
        wp_safe_redirect(home_url($query));
        exit;
    }
    static function get_current_page_url()
    {
        $uri = $_SERVER['REQUEST_URI'];
        return home_url($uri);
    }
    static function sanitize_value($value, $type='text_field')
    {
        switch($type)
        {
            case 'email':
                return is_email($value) ? sanitize_email($value) : false;
            break;
            case 'number':
                return intval($value);
            break;
            case 'textarea':
                return sanitize_textarea_field($value);
            break;
            case 'text_field':
            default:
                return sanitize_text_field($value);
        }
    }
    static function get_pro_version_link()
    {
        $locale = get_locale();
        return $locale == 'fa_IR' ? 'https://mihanwp.com/mihanpanel' : 'https://mihanwp.com/en/mihanpanel';
    }
}