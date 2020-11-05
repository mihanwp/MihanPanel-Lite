<div>
    <h2><?php esc_html_e("User panel settings", "mihanpanel"); ?></h2>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-panel' );
        ?>
        <div class="mp_option_single">
            <label><?php esc_html_e("Hide topbar", "mihanpanel"); ?></label>
            <input style="text-align:left;direction:ltr" id="mp_disable_wordpress_bar" type="checkbox" name="mp_disable_wordpress_bar" value="1" <?php checked( '1', get_option( 'mp_disable_wordpress_bar' ) );?> />
            <p class="description"><?php esc_html_e("Activate this if you want to hide wp topbar from your site for users", "mihanpanel"); ?></p>
        </div>
        <div class="mp_option_single">
            <label for="mp_use_mihan_avatar"><?php esc_html_e("Use mihan avatar", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_use_mihan_avatar(); ?>
            <p class="description"><?php esc_html_e("User can choose avatar image in panel", 'mihanpanel');?></p>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("User panel page", "mihanpanel"); ?></label>
            <?php
            $current_slug_value = \mihanpanel\app\options::get_panel_slug();
            $all_pages_args = [
                'post_type' => 'page',
                'posts_per_page' => '-1',
                'post_status' => 'publish'
            ];
            $all_pages = new WP_Query($all_pages_args);
            ?>
            <select name="mp_panelslug" class="mw_select2" id="mp_panelslug">
                <?php if($all_pages->have_posts()):
                    while($all_pages->have_posts()):
                        $all_pages->the_post();
                        $slug = get_post_field('post_name', get_the_ID());
                    ?>
                    <option <?php selected($slug, $current_slug_value); ?> value="<?php echo esc_attr($slug); ?>"><?php echo esc_html(get_the_title()); ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="0"><?php esc_html_e("No any page exists", 'mihanpanel'); ?></option>
                <?php endif; ?>
                </select>
            <p class="description"><span><?php esc_html_e("Select user panel page", "mihanpanel")?></span></p>
        </div>
        <div class="mp_option_single">
            <label for="mp_panelfont"><?php esc_html_e("Panel Font Family", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_panel_font(); ?>
            <p class="description"><span><?php esc_html_e("User Panel Font Family", "mihanpanel")?></span></p>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Off Code", "mihanpanel"); ?></label>
            <input style="text-align:left;direction:ltr" id="mp_offer_code" type="text" name="mp_offer_code" value="<?php echo esc_attr(get_option('mp_offer_code')); ?>" />
            <p class="description"><?php esc_html_e("Off Code that you want to show in user panel", "mihanpanel"); ?></p>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Off Code percentage",'mihanpanel'); ?></label>
            <input id="mp_offer_perc" type="text" name="mp_offer_perc" value="<?php echo esc_attr(get_option('mp_offer_perc')); ?>" />
            <p class="description"><?php esc_html_e("Off Code percentage will show in user panel. ex => 25%",'mihanpanel'); ?></p>
        </div>
        <h2><?php esc_html_e("Color scheme", "mihanpanel")?></h2>
        <div class="mp_option_single">
            <label for="mp_sidebar_bg_color"><?php esc_html_e("Sidebar background color in panel", "mihanpanel")?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_sidebar_bg_color(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Sidebar link color that show in panel", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_sidebar_color(); ?>
        </div>
        <h2><?php esc_html_e("Dashboard Widgets", "mihanpanel")?></h2>
        <div class="mp_option_single">
            <label><?php esc_html_e("Hide register day count widget", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_hide_register_day_count_widget(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Hide Comment widget", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_hide_comment_widget(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Hide Woocommerce widget", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_hide_woocommerce_widget(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Hide EDD widget", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_hide_edd_widget(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Hide Awesome Support widget", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_hide_awesome_support_widget(); ?>
        </div>
        <h2><?php esc_html_e("Roles who can't use admin panel", "mihanpanel")?></h2>
        <?php \mihanpanel\app\handle_view::option_panel_field_ban_roles(); ?>        
        <?php submit_button(); ?>
    </form>
</div>