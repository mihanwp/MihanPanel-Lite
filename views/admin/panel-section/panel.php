<div>
    <h2><?php _e("User panel settings", "mihanpanel"); ?></h2>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-panel' );
        ?>
        <div class="mp_option_single">
            <label><?php _e("Hide topbar", "mihanpanel"); ?></label>
            <input style="text-align:left;direction:ltr" id="mp_disable_wordpress_bar" type="checkbox" name="mp_disable_wordpress_bar" value="1" <?php checked( '1', get_option( 'mp_disable_wordpress_bar' ) );?> />
            <p class="description"><?php _e("Activate this if you want to hide wp topbar from your site for users", "mihanpanel"); ?></p>
        </div>
        <div class="mp_option_single">
            <label for="mp_use_mihan_avatar"><?php _e("Use Mihan Avatar", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            <p class="description"><?php _e("User can upload profile image in Panel.", 'mihanpanel');?></p>
        </div>
        <div class="mp_option_single">
            <label><?php _e("User panel page", "mihanpanel"); ?></label>
            <?php
            $current_slug_value = \mwplite\app\mwpl_options::get_panel_slug();
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
                    <option <?php selected($slug, $current_slug_value); ?> value="<?php echo esc_attr($slug); ?>"><?php echo get_the_title(); ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="0"><?php _e("No any page exists", 'mihanpanel'); ?></option>
                <?php endif; ?>
                </select>
            <p class="description"><span><?php _e("Select user panel page", "mihanpanel")?></span></p>
        </div>
        <div class="mp_option_single">
            <label for="mp_panelfont"><?php _e("Panel Font Family", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            <p class="description"><span><?php _e("User Panel Font Family", "mihanpanel")?></span></p>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Offer Code", "mihanpanel"); ?></label>
            <input style="text-align:left;direction:ltr" id="mp_offer_code" type="text" name="mp_offer_code" value="<?php echo esc_attr(get_option('mp_offer_code')); ?>" />
            <p class="description"><?php _e("Off Code that you want to show in user panel", "mihanpanel"); ?></p>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Offer Code percentage",'mihanpanel'); ?></label>
            <input id="mp_offer_perc" type="text" name="mp_offer_perc" value="<?php echo esc_attr(get_option('mp_offer_perc')); ?>" />
            <p class="description"><?php _e("Off Code percentage will show in user panel. ex => 25%",'mihanpanel'); ?></p>
        </div>
        <h2><?php _e("Color scheme", "mihanpanel")?></h2>
        <div class="mp_option_single">
            <label for="mp_sidebar_bg_color"><?php _e("Sidebar background color in panel", "mihanpanel")?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Sidebar link color that show in panel", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <h2><?php _e("Dashboard Widgets", "mihanpanel")?></h2>
        <div class="mp_option_single">
            <label><?php _e("Hide register day count widget", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Hide Comment widget", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Hide WooCommerce widget", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Hide EDD widget", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Hide Awesome Support widget", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <h2><?php _e("Roles who can't use admin panel", "mihanpanel")?></h2>
        <div class="mp_option_single">
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <?php submit_button(); ?>
    </form>
</div>