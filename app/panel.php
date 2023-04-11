<?php
namespace mihanpanel\app;

use WC_Shortcode_My_Account;

class panel
{
    protected static $_current_tab;
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
        $tab_id = self::get_current_tab();
        if(isset($_GET['order_details']) && isset($_GET['order_id']) && intval($_GET['order_id']))
        {
            \mihanpanel\app\panel::show_order(intval($_GET['order_id']));
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
    static function get_tabs()
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpaneltabs';
        return $wpdb->get_results("SELECT * FROM {$tablename} ORDER BY priority ASC");
    }
    static function get_current_tab()
    {
        if(!self::$_current_tab)
        {
            self::$_current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : false;            
        }
        return self::$_current_tab;
    }
    static function render_sidebar_items()
    {
        $tab_id = self::get_current_tab();
        $sidebar = views::get_from_main_app('parts.sidebar');
        include $sidebar;
    }
    private static function render_attrs($attrs)
    {
        $res = [];
        foreach($attrs as $attr => $value)
        {
            if(is_array($value))
            {
                $value = implode(' ', $value);
            }
            $value = $attr == 'href' ? esc_url($value) : esc_attr($value);
            $res[] = $attr . '="' . $value . '"';
        }
        return implode(' ', $res);
    }
    static function render_tabs()
    {
        $tab_id = self::get_current_tab();
        $menus = self::get_tabs();
        echo '<ul class="nav mp-nav-tabs menu-tabs-items">';
        foreach ($menus as $menu):
            $item_url = $menu->link == null ? esc_url(add_query_arg(['tab' => $menu->id], \mihanpanel\app\options::get_panel_url())) : esc_url($menu->link);
            $link_attrs = [
                'class' => ['mwtaba'],
                'href' => $item_url,
            ];
            if($menu->link)
            {
                $link_attrs['target'] = '_blank';
            }
            $li_attrs = [
                'tab-id' => $menu->id
            ];
            if($tab_id == $menu->id)
            {
                $li_attrs['class'][] = 'active';
            }
            $li_attrs = apply_filters('mwpl_panel/sidebar/item_attrs', $li_attrs, $menu);
            $link_attrs = apply_filters('mwpl_panel/sidebar/link_attrs', $link_attrs, $menu);

            $permission = apply_filters('mwpl_panel/sidebar/permission', true, $menu);
            if(!$permission)
            {
                continue;
            }
            ?>
            <li <?php echo self::render_attrs($li_attrs)?>>
                <a <?php echo self::render_attrs($link_attrs)?>>
                    <?php \mihanpanel\app\presenter\tabs_menu::render_tab_item_icon($menu->icon); ?>
                    <p><?php echo apply_filters('mihanpanel/panel/tabs_menu_item_label_text', esc_html($menu->name)); ?></p>
                </a>
            </li>
        <?php
        endforeach;
        echo '</ul>';
    }
}