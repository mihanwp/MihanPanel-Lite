<?php
namespace mihanpanel\app;

class shortcode
{
    private static function get_not_pro_version_error()
    {
        return '<div class="alert alert-error"><span>'.esc_html__("This shortcode is active in MihanPanel Pro.", 'mihanpanel').'</span></div>';
    }
    static function woocommerce_order()
    {
        if(tools::isProVersion())
        {
            return \mihanpanel\pro\app\shortcode::handle_woocommerce_shortcode();
        }else{
            return self::get_not_pro_version_error();
        }
    }
    static function woocommerce_downloads()
    {
        if(tools::isProVersion())
        {
            return \mihanpanel\pro\app\shortcode::handle_woocommer_downloads_shortcode();
        }else{
            return self::get_not_pro_version_error();
        }
    }
    static function panel()
    {
        ob_start();
        if (is_user_logged_in()) {
            if (is_singular(['page', 'post'])):
            ?>
            <div class="mpwrapper mihanpanelpanel <?php echo is_rtl() ? 'mp_wrapper_rtl' : 'mp_wrapper_ltr'; ?>">
                <div class="mp_menu_bars fa fa-bars fa-lg"><small style="padding: 0 5px;"><?php esc_html_e("Open Menu", "mihanpanel"); ?></small></div>
                <div class="mpsidebar <?php echo is_rtl() ? 'mp_rtl' : 'mp_ltr'; ?>">
                    <div class="mpavatar">
                        <?php
                        $current_user = wp_get_current_user();
                        echo get_avatar($current_user->ID, 250);
                        ?>
                    </div>
                    <div class="clear"></div>
                    <div class="mpsidebar-wrapper">
                        <?php
                        \mihanpanel\app\panel::render_sidebar_items();
                        ?>
                    </div>
                </div>
                <?php
                \mihanpanel\app\handle_view::handle_panel_content();
                ?>
                <div class="clear"></div>
            </div>
            <?php
            endif;
        } else {
            esc_html__("Please login to your account", "mihanpanel");
        }
        $data = ob_get_clean();
        return $data;
    }
}