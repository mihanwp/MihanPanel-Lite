<?php

use mihanpanel\app\live_edit;

final class MihanPanelApp
{
    private static $_instance;
    static function get_instance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    function __construct()
    {
        $this->do_define();
        $this->autoload();
        $this->includes();
        \mihanpanel\pro\mihanpanelProApp::init();
        live_edit::getInstance();
        register_deactivation_hook(WP_MIHANPANEL_MAIN_APP, ['\mihanpanel\app\hooks', 'deactivation_hook']);
    }
    function autoload()
    {
        try {
            spl_autoload_register([$this, 'handle_autoload']);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    function handle_autoload($class_name)
    {
        if (strpos($class_name, 'mihanpanel') !== false)
        {
            $class_name = str_replace('mihanpanel\\', '', $class_name);
            // $class_name = strtolower($class_name);
            $class_file_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
            $class_file_path = MW_MIHANPANEL_DIR . $class_file_path . '.php';
            if (file_exists($class_file_path) && is_readable($class_file_path))
            {
                include_once $class_file_path;
            }
        }
    }
    function includes()
    {
        // include composer vendor
        include_once MW_MIHANPANEL_DIR . 'vendor/autoload.php';
        
        // include classes
        \mihanpanel\app\ajax::init();
        \mihanpanel\app\hooks::init();
        \mihanpanel\app\gutenberg::init();
        \mihanpanel\app\login::init();

        include_once MW_MIHANPANEL_DIR . 'app' . DIRECTORY_SEPARATOR . 'mw_elementor.php';
    }
    function do_define()
    {
        $this->define("MW_MIHANPANEL_DB_VERSION", 11);
        $this->define('MW_MIHANPANEL_DIR', plugin_dir_path(WP_MIHANPANEL_MAIN_APP));
        $this->define('MW_MIHANPANEL_URL', plugin_dir_url(WP_MIHANPANEL_MAIN_APP));
        $this->define('MW_MIHANPANEL_LANG', basename(MW_MIHANPANEL_DIR) . '/lang');
        $this->define('MW_PRO_INC_DIR', MW_MIHANPANEL_DIR . '/pro/inc/');
    }
    function define($name, $value)
    {
        if(!defined($name))
        {
            define($name, $value);
        }
    }
}
MihanPanelApp::get_instance();