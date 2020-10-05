<?php
namespace mwplite\app;
if(defined('ABSPATH') && !class_exists('mwpl_assets'))
{
    class mwpl_assets
    {
        public static function get_js_url($file_name)
        {
            $js = MW_MIHANPANEL_LITE_URL . 'js/' . $file_name . '.js';
            return $js;
        }
        public static function get_css_url($file_name)
        {
            $css = MW_MIHANPANEL_LITE_URL . 'css/' . $file_name . '.css';
            return $css;
        }
        static function load_admin_assets()
        {
            $plugin_version = \mwplite\app\mwpl_tools::get_plugin_version();
            wp_register_style('mihanpanel-admin-styles', MW_MIHANPANEL_LITE_URL . 'css/admin.css', '', $plugin_version);
            wp_register_style('mihanpanel-admin-fa', MW_MIHANPANEL_LITE_URL . 'css/fa/css/all.css', '', $plugin_version);
            wp_enqueue_style('mihanpanel-admin-styles');
            wp_enqueue_style('mihanpanel-admin-fa');
            if (!is_rtl())
            {
                wp_enqueue_style('mihanpanel-admin-ltr-style', MW_MIHANPANEL_LITE_URL . 'css/admin-ltr.css', '', $plugin_version);
            }
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('my-script-handle', MW_MIHANPANEL_LITE_URL . 'js/color-picker.js', array('wp-color-picker'), false, true);
        }
        public static function load_panel_js()
        {
            $panel_js = self::get_js_url('panel');
            $version = mwpl_tools::get_plugin_version();
            wp_enqueue_script('mwpl_panel', $panel_js, ['jquery'], $version, true);
        }
        static function load_admin_panel_assets()
        {
            $version = mwpl_tools::get_plugin_version();
            self::load_admin_panel_css($version);
            self::load_admin_panel_js($version);
        }
        static function load_admin_panel_css($version)
        {
            $select_2 = self::get_css_url('select2.min');
            wp_enqueue_style('select2', $select_2, [], $version);
        }
        public static function load_admin_panel_js($version)
        {
            $select_2 = self::get_js_url('select2.min');
            $uploader_js = self::get_js_url('uploader.js');
            $panel_js = self::get_js_url('admin-panel');
            wp_enqueue_script('select2', $select_2, [], $version, true);
            wp_enqueue_script('mwplite_uploader_js', $uploader_js, ['jquery'], true);
            wp_enqueue_script('mw_admin_panel', $panel_js, ['jquery'], $version, true);
            wp_localize_script('mw_admin_panel', 'mwp_data', ['au' => admin_url('admin-ajax.php')]);
        }
        static function load_admin_user_profile($page)
        {
            if($page !== 'profile.php' && $page !== 'user-edit.php')
            {
                return false;
            }
            $admin_user_profile = self::get_js_url('admin-user-profile');
            $version = mwpl_tools::get_plugin_version();
            wp_enqueue_script('mw_admin_user_profile', $admin_user_profile, ['jquery'], $version, true);
        }
        static function load_user_field_menu_assets()
        {
            self::load_sortable_script();
        }
        public static function load_menus_management_assets()
        {
            self::load_sortable_script();
        }
        public static function load_sortable_script()
        {
            $version = \mwplite\app\mwpl_tools::get_plugin_version();
            $jquery_ui = self::get_js_url('jquery_ui');
            $mw_drag_and_drop = self::get_js_url('mw_drag_and_drop');
            wp_enqueue_script('mw_jquery_ui', $jquery_ui, ['jquery'], $version, true);
            wp_enqueue_script('mw_drag_and_drop', $mw_drag_and_drop, ['jquery', 'mw_jquery_ui'], $version, true);
    
            wp_localize_script('mw_drag_and_drop', 'mwp_data', ['au' => admin_url('admin-ajax.php')]);
        }
        public static function load_media_uploader()
        {
            wp_enqueue_media();
            $uploader = self::get_js_url('uploader');
            wp_register_script('media-uploader', $uploader);
            wp_enqueue_script('media-uploader');
        }
        static function login_assets()
        {
            $mp_bg_image = \mwplite\app\mwpl_options::get_login_bg();
            $mp_logo = \mwplite\app\mwpl_options::get_logo();
            $plugin_version = \mwplite\app\mwpl_tools::get_plugin_version();
            wp_enqueue_style('custom-login', MW_MIHANPANEL_LITE_URL . 'css/login.css', '', $plugin_version);
            if (!is_rtl())
            {
                wp_enqueue_style('mw-custom-login-ltr', MW_MIHANPANEL_LITE_URL . 'css/login-ltr.css', '', $plugin_version);
            }
            ?>
            <style type="text/css">
                body.login {
                    background: url('<?php echo $mp_bg_image; ?>') no-repeat center top;
                }
                #login h1 a, .login h1 a {
                    background: url('<?php echo $mp_logo;?>') no-repeat;
                    width: <?php echo get_option('mp_logo_width');?>px;
                    height: <?php echo get_option('mp_logo_height');?>px;
                }
                <?php if( get_option('login_button_color') != null ):?>
                .login form input[type=submit]{
                background-color:<?php echo get_option( 'login_button_color' );?> !important;
                box-shadow:0 5px 10px <?php echo get_option( 'login_button_color' );?>60 !important
                }
                <?php endif;?>
            </style>
            <?php
        }
        static function load_front_assets()
        {
            global $post;
            $plugin_version = \mwplite\app\mwpl_tools::get_plugin_version();
            if (isset($post->post_content) && is_singular(array('post', 'page')) && has_shortcode($post->post_content, 'mihanpanel')) {
                wp_enqueue_style('mwstyle-css', MW_MIHANPANEL_LITE_URL . 'css/style.css', '', $plugin_version);
                if(!is_rtl())
                {
                    wp_enqueue_style('mwstyle-ltr-css', MW_MIHANPANEL_LITE_URL . 'css/style-ltr.css', '', $plugin_version);
                }
                \mwplite\app\mwpl_assets::load_panel_js();
            }
            wp_enqueue_style('mw_fontawesome_css', MW_MIHANPANEL_LITE_URL . 'css/fa/css/all.css', '', $plugin_version);
            wp_enqueue_style('mw-profile-widget', MW_MIHANPANEL_LITE_URL . 'css/profile-widget.css', '', $plugin_version);
        }
    }
}