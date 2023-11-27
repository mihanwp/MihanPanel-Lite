<?php
namespace mihanpanel\app;
class tools
{
    public static function vd(...$args)
    {
        echo "<pre style='all: revert;'>";
        foreach($args as $arg)
        {
            var_dump($arg);
        }
        echo "</pre>";
    }
    static function convertNumberLocale($value, $localeToEn=true)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $en = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        return $localeToEn ? str_replace($persian, $en, $value) : str_replace($en, $persian, $value);
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
    static function is_plugin_active($plugin_name)
    {
        $all = get_option('active_plugins');
        return in_array($plugin_name, $all);
    }
    static function is_wpml_active()
    {
        return self::is_plugin_active('sitepress-multilingual-cms/sitepress.php');
    }
    static function is_wpml_not_in_persian()
    {
        return defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE !== null && ICL_LANGUAGE_CODE !== 'fa';
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
    static function is_mihanticket_active()
    {
        return self::is_plugin_active('mihanticket/mihanticket.php');
    }
	static function is_dokan_active()
    {
        return self::is_plugin_active('dokan-lite/dokan.php');
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
    static function is_persian_locale()
    {
        return get_locale() == 'fa_IR';
    }
    static function getBaseRemoteUrl()
    {
        return 'https://mihanwp.com/';
    }
    static function getRemoteServerByLicenseKey($licenseKey)
    {
        return strpos($licenseKey, 'ertano_') === 0 ? 'https://ertano.com/' : 'https://mihanwp.com/';
    }
    static function getRemoteProductId($licenseKey)
    {
        return strpos($licenseKey, 'ertano_') === 0 ? '747' : '962358';
    }
    static function isProVersion()
    {
        return defined('MIHANPANEL_PRO_DIR_PATH');
    }
    static function get_pro_version_link()
    {
        $baseRemoteUrl = self::getBaseRemoteUrl();
        return $baseRemoteUrl . 'mihanpanel';
    }
    static function check_has_minimum_php_version()
    {
        $php_version = phpversion();
        return version_compare($php_version, '7.4') >= 0;
    }
    static function check_has_minimum_wordpress_version()
    {
        require ABSPATH . WPINC . '/version.php';
        $current = get_site_transient('update_core');
        if(is_object($current) && $wp_version != $current->version_checked)
        {
            return false;
        }
        return version_compare($wp_version, '5.6') >= 0;
    }
    static function check_is_panel_page_exists()
    {
        $slug = options::get_panel_slug();
        if(!$slug)
        {
            return false;
        }
        $page = get_page_by_path($slug);
        return $page ? true : false;
    }
    static function get_all_shortcodes()
    {
        global $shortcode_tags;
        ksort( $shortcode_tags );
        return $shortcode_tags;
    }
    static function getOptionPanelUrl()
    {
        return admin_url('admin.php?page=mihanpanel');
    }

    static function sanitize_array_values($array)
    {
        if(!$array)
        {
            return false;
        }
        if(!is_array($array))
        {
            return sanitize_text_field($array);
        }
        foreach($array as $key => $value)
        {
            $array[$key] = is_array($value) ? self::sanitize_array_values($value) : sanitize_text_field($value);
        }
        return $array;
    }

    public static function send_json_response($response){
        die(json_encode($response));
    }
}