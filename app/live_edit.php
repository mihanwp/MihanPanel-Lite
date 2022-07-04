<?php

namespace mihanpanel\app;

class live_edit
{
    static protected $menu_name = 'mihanpanel_live_edit';
    protected $hook_suffix;
    protected $current_page;
    static private $_instance;
    static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    function __construct()
    {
        $this->hooks();
    }
    function hooks()
    {
        add_action('admin_menu', [$this, 'register_sub_menus'], 2);
        add_action('admin_init', [$this, 'handle_tabs_menu_content']);
        // add_filter('show_admin_bar', '__return_false');

        if(!tools::isProVersion())
        {
            add_action('mwpl_live_edit_before_header_actions', [$this, 'headerActions']);
            add_action('mwpl_live_edit_before_footer_actions', [$this, 'footerActions']);
        }
    }
    function headerActions()
    {
        wp_enqueue_style('live-edit-panel-lite-version', assets::get_css_url('live-edit-panel'));
    }
    function footerActions()
    {
        $version = tools::get_plugin_version();
        wp_enqueue_script('live-edit-panel-lite-version', assets::get_js_url('admin-live-edit-panel'), ['jquery'], $version, true);
        $data = [
            'au' => admin_url('admin-ajax.php'),
            'texts' => [
                'pro_version' => esc_html__('This feature is just accessible in pro version', 'mihanpanel'),
                'labels' => [
                    'content' => esc_html__('Content', 'mihanpanel'),
                ],
                'new_item' => esc_html__('New Item', 'mihanpanel'),
                'delete_msg' => esc_html__('Are you sure you want to delete this item?', 'mihanpanel'),
                'item_name' => esc_html__('Item Name', 'mihanpanel'),
                'edit_content_btn_text' => esc_html__('Edit Content', 'mihanpanel'),
            ],
        ];
        wp_localize_script('live-edit-panel-lite-version', 'mwp_data_lite', $data);
    }

    function register_sub_menus()
    {
        // add_submenu_page()
        $title = sprintf('%s <span style="color: #f84747;"> ( %s ) </span>', esc_html__('Live Edit', 'mihanpanel'), esc_html__('Beta', 'mihanpanel'));
        $this->hook_suffix = add_submenu_page('mihanpanel', $title, $title, 'manage_options', self::$menu_name, [$this, 'handle_tabs_menu_content']);
    }
    function getTitle()
    {
        return sprintf('%s [ %s ]', esc_html__('Mihan Panel', 'mihanpanel'), esc_html__('Live Edit', 'mihanpanel'));
    }
    function header()
    {
        if ($this->hook_suffix) {
            set_current_screen($this->hook_suffix);
        }
?>
        <!DOCTYPE html>
        <html <?php language_attributes() ?>>

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->getTitle(); ?></title>
            <?php
            do_action('mwpl_live_edit_before_header_actions');
            do_action('admin_print_styles');
            do_action('admin_print_scripts');
            do_action('admin_head');
            ?>
        </head>

        <body>


        <?php
    }
    function body()
    {
        $panelUrl = options::get_panel_url();
        ?>

            <div class="live-edit-wrapper" mwpl-nonce="<?php echo wp_create_nonce('mwpl_ajax_update_live_edit_options')?>">
                <div class="toolbar">
                    <div class="title-section"><?php echo $this->getTitle(); ?></div>
                    <a href="<?php echo \mihanpanel\app\tools::getOptionPanelUrl()?>" class="live-edit-btn"><?php esc_html_e('Exit from live edit', 'mihanpanel')?></a>
                </div>
                <div class="content preload-mode">
                    <div class="preloader">
                        <img src="<?php echo MW_MIHANPANEL_URL . 'img/logo.png'; ?>" width="100" height="100" alt="Mihan Panel Logo">
                    </div>
                    <iframe id="live-edit-content" src="<?php echo $panelUrl; ?>" frameborder="0"></iframe>
                </div>
            </div>

        <?php

    }
    function footer()
    {
        do_action('mwpl_live_edit_before_footer_actions');
        do_action('admin_footer');
        do_action('admin_print_footer_scripts');
        ?>
        </body>

        </html>
<?php
    }

    function handle_tabs_menu_content()
    {
        if (empty($_GET['page']) || $_GET['page'] !== self::$menu_name) {
            return;
        }
        add_filter('show_admin_bar', '__return_false');
        $this->header();
        $this->body();
        $this->footer();
        exit;
    }
}
