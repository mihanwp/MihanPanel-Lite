<?php
/*
Plugin Name: MihanPanel
Plugin URI:  https://mihanwp.com/mihanpanel/
Author:      MihanWP
Author URI:  https://mihanwp.com
Version:     9.3.1
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
