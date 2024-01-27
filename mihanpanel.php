<?php
/*
Plugin Name: MihanPanel Lite
Plugin URI:  https://ertano.com/mihanpanel/
Author:      Ertano
Author URI:  https://ertano.com
Version:     12.7
Description: The best way to build user interface in WordPress
*/
//if accessed direct
defined('ABSPATH') or die('Hello');
register_activation_hook(__FILE__, function(){
    update_option('mwpl_handle_activation_hook', true);
});

if(!defined('WP_MIHANPANEL_MAIN_APP'))
{
    define("WP_MIHANPANEL_MAIN_APP", __FILE__);
}
require_once plugin_dir_path(WP_MIHANPANEL_MAIN_APP) . DIRECTORY_SEPARATOR . 'main_app.php';
