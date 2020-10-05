<?php
namespace mwplite\app;

use WC_Shortcode_My_Account;
if(defined('ABSPATH') && !class_exists('mwpl_panel'))
{
    class mwpl_panel
    {
        static function change_view_order_url($url, $order)
        {
            $panel_slug = mwpl_options::get_panel_slug();
            if(is_page($panel_slug))
            {
                return add_query_arg(['order_details' => '1', 'order_id' => $order->get_id()]);
            }
            return $url;
        }
        static function show_order($order_id)
        {
            if(!intval($order_id) || !mwpl_tools::is_woocommerce_active())
            {
                return false;
            }
            ?>
            <div class="main-panel mwtabb">
                <div class="mihanpanel-section-title"><?php _e('Order Details', 'mihanpanel'); ?></div>
                <div class="mp-content mihanpcontent">
                    <?php
                    $my_account = new WC_Shortcode_My_Account();
                    $my_account->view_order($order_id);
                    ?>
                </div>
            </div>
            <?php
        }
        static function get_tabs_count()
        {
            global $wpdb;
            $tbl_name = $wpdb->prefix . 'mihanpaneltabs';
            $sql = "SELECT count(*) from {$tbl_name}";
            return $wpdb->get_var($sql);
        }
    }
}