<?php
namespace mwplite\app;
use Elementor\Plugin;

defined('ABSPATH') || die("No Access");
final class MW_Elementor
{
    const VERSION = 1;
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '7.0';
    private static $_instance;

    public static function instance()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        if( !did_action('elementor/loaded'))
        {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>='))
        {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<'))
        {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
    }

    public function admin_notice_missing_main_plugin()
    {

    }

    public function admin_notice_minimum_elementor_version()
    {

    }

    public function admin_notice_minimum_php_version()
    {

    }

    public function init_widgets()
    {
        Plugin::instance()->widgets_manager->register_widget_type(new \mwplite\app\emwidg\mw_emwidg_mihanpanel());
    }
}
MW_Elementor::instance();
