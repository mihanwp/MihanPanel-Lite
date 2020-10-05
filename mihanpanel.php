<?php
/*
Plugin Name: MihanPanel
Plugin URI:  https://mihanwp.com/mihanpanel/
Author:      MihanWP
Author URI:  https://mihanwp.com
Version: 	 7.4
Description: The best way to build user interface in WordPress
*/
//if accessed direct
defined('ABSPATH') or die('Hello');

if(!defined('MWP_LITE_MAIN_APP'))
{
    define("MWP_LITE_MAIN_APP", __FILE__);
}

require_once plugin_dir_path(__FILE__) . DIRECTORY_SEPARATOR . 'main_app.php';














