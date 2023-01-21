<?php
namespace mihanpanel\app;
class widget
{
    static function init()
    {
        register_widget('\mihanpanel\app\widget\profile');
    }
    static function dashboard_widget()
    {
        wp_add_dashboard_widget('mwp_mihanpanel_status', esc_html__('MihanPanel Status', 'mihanpanel'), [__CLASS__, 'mihanpanel_status_dashboard_widget']);
    }
    static function mihanpanel_status_dashboard_widget()
    {
        $check = [];
        // check php version
        $check_php_version = tools::check_has_minimum_php_version();
        // check wordpress version
        $check_wp_version = tools::check_has_minimum_wordpress_version();
        // check is panel page exists
        $check_panel_page = tools::check_is_panel_page_exists();
        if(!$check_php_version)
        {
            $check['php_version']['msg'] = esc_html__('PHP version must be higher than 7.4', 'mihanpanel');
            $check['php_version']['type'] = 'error';
        }
        if(!$check_wp_version)
        {
            $check['wp_version']['msg'] = esc_html__('Wordpress version must be higher than 5.6', 'mihanpanel');
            $check['wp_version']['type'] = 'error';
        }
        if(!$check_panel_page)
        {
            $tools_url = add_query_arg(['page'=>'mihanpanel','tab'=>'tools'], admin_url('admin.php'));
            $tools_link_tag = sprintf('<a href="%s">%s</a>',$tools_url, esc_html__('Tools', 'mihanpanel'));
            $check['panel_page']['msg'] = sprintf(esc_html__('Panel page not exists. You can create it from this section: %s', 'mihanpanel'), $tools_link_tag);
            $check['panel_page']['type'] = 'error';
        }
        if(!$check)
        {
            $check['checked']['msg'] = esc_html__('Everything on your site is OK.', 'mihanpanel');
            $check['checked']['type'] = 'success';
        }else{
            $check_title = esc_html__('You must check:', 'mihanpanel');
        }
        ?>
        <div class="mwp_check_site_dashboard_widget">
            <?php if(isset($check_title)): ?>
                <h3><?php echo esc_html($check_title); ?></h3>
            <?php endif; ?>
            <?php foreach($check as $check_item):?>
                <div class="check_item">
                    <span class="<?php echo esc_attr($check_item['type']);?>"><span><?php echo esc_html($check_item['msg']); ?></span></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        // mihanwp feed
        include_once ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'feed.php';
        $is_persian = tools::is_persian_locale();
        $baseUrl = \mihanpanel\app\tools::getBaseRemoteUrl();
        $feed = $baseUrl . 'feed/';
        $rss = fetch_feed($feed);
        if(is_wp_error($rss))
        {
            return false;
        }
        $max_item = $rss->get_item_quantity(3);
        $items = $rss->get_items(0, $max_item);
        $mihanwp = sprintf('<a target="_blank" href="%s">%s</a>', $baseUrl, __('ertano.com', 'mihanpanel'));
        $header = sprintf(__('Latest news in %s', 'mihanpanel'), $mihanwp);
        $more_articles_url = $is_persian ? 'https://mihanwp.com/tutorials/' : 'https://ertano.com/';
        ?>
        <div class="mwp_mihanwp_feed_dashboard">
            <strong><?php echo esc_html($header); ?></strong>
            <hr style="border:0; height: 1px; background-color: #dfdfdf">
            <ul>
                <?php if($max_item == 0): ?>
                    <li><?php esc_html_e('No item', 'mihanpanel')?></li>
                <?php else:
                    foreach($items as $item):
                        $item_date = human_time_diff($item->get_date('U'), current_time('timestamp')) . ' ' . esc_html__('ago', 'mihanpanel');
                        ?>
                        <li>
                            <a target="_blank" title="<?php echo esc_attr($item_date)?>" href="<?php echo esc_url($item->get_permalink())?>"><?php echo esc_html($item->get_title())?></a>
                            <p><?php echo esc_html($item->get_description(false))?></p>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <a target="_blank" class="more_articles" href="<?php echo esc_url($more_articles_url)?>"><?php esc_html_e('More Articles', 'mihanpanel')?></a>
        </div>
        <?php
    }
}