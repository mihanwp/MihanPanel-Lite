<?php
namespace mihanpanel\app;

use WC_Shortcode_My_Account;

class panel
{
    static function change_view_order_url($url, $order)
    {
        $panel_slug = options::get_panel_slug();
        if(is_page($panel_slug))
        {
            return add_query_arg(['order_details' => '1', 'order_id' => $order->get_id()]);
        }
        return $url;
    }
    static function show_order($order_id)
    {
        if(!intval($order_id) || !tools::is_woocommerce_active())
        {
            return false;
        }
        ?>
        <div class="main-panel mwtabb">
            <div class="mihanpanel-section-title"><?php esc_html_e('Order Details', 'mihanpanel'); ?></div>
            <div class="mp-content mihanpcontent">
                <?php
                $my_account = new WC_Shortcode_My_Account();
                $my_account->view_order($order_id);
                ?>
            </div>
        </div>
        <?php
    }
    static function panel_content()
    {
        // check is show order details
        $tab_id = isset($_GET['tab']) && $_GET['tab'] ? $_GET['tab'] : false;
        if(isset($_GET['order_details']) && isset($_GET['order_id']) && intval($_GET['order_id']))
        {
            \mihanpanel\app\panel::show_order($_GET['order_id']);
        }else{
            $tab_file = views::get_from_main_app('parts.' . $tab_id);
            if(file_exists($tab_file))
            {
                include $tab_file;
            }elseif($tab_id == null)
            {
                $dashboard = views::get_from_main_app('parts.dashboard');
                include $dashboard;
            }else{
                $db_page = views::get_from_main_app('parts.db-page');
                include $db_page;
            }
        }
    }
    static function get_tabs_count()
    {
        global $wpdb;
        $tbl_name = $wpdb->prefix . 'mihanpaneltabs';
        $sql = "SELECT count(*) from {$tbl_name}";
        return $wpdb->get_var($sql);
    }
}