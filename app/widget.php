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
        \mihanpanel\app\assets::enqueue_style('admin-dashboard-widget', \mihanpanel\app\assets::get_css_url('admin/dashboard-widget'));
        \mihanpanel\app\assets::enqueue_script('admin-dashboard-widget', \mihanpanel\app\assets::get_js_url('admin/dashboard-widget'));
        
        $baseUrl = \mihanpanel\app\tools::getBaseRemoteUrl();
        ?>

        <div class="mwp_mihanwp_feed_dashboard">
            <strong><?php printf(__('Latest news in %s', 'mihanpanel'), sprintf('<a target="_blank" href="%s">%s</a>', esc_url($baseUrl), __('MihanWp', 'mihanpanel'))); ?></strong>
            <hr style="border:0; height: 1px; background-color: #dfdfdf">
            <div class="mwpl-loading-spinner-wrapper show">
                <span class="dashicons dashicons-update"></span>
            </div>
            <ul class="mwpl-rss-items"></ul>
            <a target="_blank" class="more_articles" href="<?php echo esc_url('https://mihanwp.com/tutorials/')?>"><?php esc_html_e('More Articles', 'mihanpanel')?></a>
        </div>
        <?php
    }
}