<?php
namespace mihanpanel\app;
class shortcode
{
    static function woocommerce_order()
    {
        if (class_exists('WooCommerce')) {
            $user_id = get_current_user_id();
            if ($user_id == 0) {
                return do_shortcode('[woocommerce_my_account]');
            } else {
                ob_start();
                wc_get_template('myaccount/my-orders.php', array(
                    'current_user' => get_user_by('id', $user_id),
                    'order_count' => '-1'
                ));
                return ob_get_clean();
            }
        } else {
            echo '<div class="alert alert-error"><span>'.esc_html__("Please install Woocommerce plugin.", 'mihanpanel').'</span></div>';
        }
    }
    static function woocommerce_downloads()
    {
        if(!\mihanpanel\app\tools::is_woocommerce_active())
        {
            return esc_html__("You need to install woocommerce for use this section!", "mihanpanel");
        }
        $downloads     = WC()->customer->get_downloadable_products();
        $has_downloads = (bool) $downloads;
        do_action( 'woocommerce_before_account_downloads', $has_downloads );
        if ( $has_downloads ) :
            do_action( 'woocommerce_before_available_downloads' );
            do_action( 'woocommerce_available_downloads', $downloads );
            do_action( 'woocommerce_after_available_downloads' );
        else : ?>
          <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
              <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                  <?php esc_html_e( 'Go shop', 'woocommerce' ); ?>
              </a>
              <?php esc_html_e( 'No downloads available yet.', 'woocommerce' ); ?>
          </div>
      <?php endif;
      do_action( 'woocommerce_after_account_downloads', $has_downloads );
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
                        $sidebar = views::get_from_main_app('parts.sidebar');
                        include $sidebar;
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