<?php

final class mwp_lite
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
        $this->requires();

        register_activation_hook(MWP_LITE_MAIN_APP, ['\mwplite\app\mw_hooks', 'activation_hook']);
        register_deactivation_hook(MWP_LITE_MAIN_APP, ['\mwplite\app\mw_hooks', 'deactivation_hook']);
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
        if (strpos($class_name, 'mwplite') !== false)
        {
            $class_name = str_replace('mwplite\\', '', $class_name);
            $class_name = strtolower($class_name);
            $class_file_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
            $class_file_path = MW_MIHANPANEL_LITE_DIR . $class_file_path . '.php';
            if (file_exists($class_file_path) && is_readable($class_file_path))
            {
                include_once $class_file_path;
            }
        }
    }
    function includes()
    {
        // include classes
        \mwplite\app\mw_ajax::init();
        \mwplite\app\mw_hooks::init();
        include MW_MIHANPANEL_LITE_DIR . 'app' . DIRECTORY_SEPARATOR . 'mw_elementor.php';
    }
    function requires()
    {
        //include rename_wp_login file
        require_once MW_MIHANPANEL_LITE_DIR . 'rename-wp-login.php';
    }
    function do_define()
    {
        if ($panel_slug = get_option('mp_panelslug')) {
            $this->define('mihanpanelpanelurl', get_bloginfo('url') . '/' . $panel_slug . '/');
        } else {
            $this->define('mihanpanelpanelurl', get_bloginfo('url') . '/' . 'panel' . '/');
        }

        $this->define("MW_MIHANPANEL_DB_VERSION", 6);
        $this->define("MW_MIHANPANEL_BASE_NAME", plugin_basename(MWP_LITE_MAIN_APP));
        $this->define('MW_MIHANPANEL_LITE_DIR', plugin_dir_path(MWP_LITE_MAIN_APP));
        $this->define('MW_MIHANPANEL_LITE_URL', plugin_dir_url(MWP_LITE_MAIN_APP));
        $this->define('MW_MIHANPANEL_LANG', basename(__DIR__) . '/lang');
    }
    function define($name, $value)
    {
        if(!defined($name))
        {
            define($name, $value);
        }
    }
}
mwp_lite::get_instance();