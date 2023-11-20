<?php
/*
Plugin Name: MihanPanel Pro
Plugin URI:  https://mihanwp.com/mihanpanel/
Author:      MihanWP
Author URI:  https://mihanwp.com
Version:     12.1.2
Description: The best way to build user interface in WordPress
*/
//if accessed direct
defined('ABSPATH') or die('Hello');
function disable_lite_version()
{
    if(!function_exists('is_plugin_active'))
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    if(is_plugin_active('mihanpanel-lite/mihanpanel.php'))
    {
        deactivate_plugins('mihanpanel-lite/mihanpanel.php');
    }
}
disable_lite_version();
register_activation_hook(__FILE__, function(){
    update_option('mwpl_handle_activation_hook', true);
});

if(!defined('WP_MIHANPANEL_MAIN_APP'))
{
    define("WP_MIHANPANEL_MAIN_APP", __FILE__);
}
require_once plugin_dir_path(WP_MIHANPANEL_MAIN_APP) . DIRECTORY_SEPARATOR . 'main_app.php';
