<div>
    <form id="mwpl-option-form" class="mp_options_panel" method="post" action="options.php">
        <h2><?php esc_html_e("User panel settings", "mihanpanel"); ?></h2>
        <?php
        settings_fields( 'mihanpanelsettings-panel' );
        ?>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Topbar', 'mihanpanel')?></h3>
            <div class="mp_option_field mp_row_field">
                <label for="mp_disable_wordpress_bar"><?php esc_html_e("Hide topbar", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mp_disable_wordpress_bar" type="checkbox" name="mp_disable_wordpress_bar" value="1" <?php checked( '1', get_option( 'mp_disable_wordpress_bar' ) );?> />
            </div>
            <p class="description"><?php esc_html_e("Activate this if you want to hide wp topbar from your site for users", "mihanpanel"); ?></p>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('Mihan avatar', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <div class="mp_option_field mp_row_field">
                <label for="mp_use_mihan_avatar"><?php esc_html_e("Use mihan avatar", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_use_mihan_avatar(); ?>
            </div>
            <p class="description"><?php esc_html_e("User can choose avatar image in panel", 'mihanpanel');?></p>
        </div>

        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Fonts', 'mihanpanel')?></h3>
            <div class="mp_option_field mp_row_field">
                <label for="mwpl_disable_font_family"><?php esc_html_e("Don't use MihanPanel fonts", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mwpl_disable_font_family" type="checkbox" name="mwpl_disable_font_family" value="1" <?php checked( '1', \mihanpanel\app\options::disable_mihanpanel_fonts() );?> />
            </div>
            <p class="description"><?php esc_html_e("Use theme or other plugins fonts instead of MihanPanel fonts.", "mihanpanel"); ?></p>
            <div class="mp_option_field mp_row_field">
                <label for="mwpl_disable_mwpl_fontawesome"><?php esc_html_e("Disable MihanPanel fontawesome", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mwpl_disable_mwpl_fontawesome" type="checkbox" name="mwpl_disable_mwpl_fontawesome" value="1" <?php checked( '1', \mihanpanel\app\options::disable_mihanpanel_fontawesome() );?> />
            </div>
            <p class="description"><?php esc_html_e("Don't load mihanpanel fontawesome", "mihanpanel"); ?></p>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('User panel page', 'mihanpanel')?></h3>
            <p class="mp_option_section_description description"><span><?php esc_html_e("Select user panel page", "mihanpanel")?></span></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Pages", "mihanpanel"); ?></label>
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
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('Panel Theme', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php esc_html_e("Choose User Panel Theme", "mihanpanel")?></p>
            <div class="mp_option_field mp_row_field">
                <label for="mwpl_panel_theme"><?php esc_html_e("Panel Theme", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_panel_theme(); ?>
            </div>
        </div>
        
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('User panel font', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php esc_html_e("User Panel Font Family", "mihanpanel")?></p>
            <div class="mp_option_field mp_row_field">
                <label for="mp_panelfont"><?php esc_html_e("Panel Font Family", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_panel_font(); ?>
            </div>
            
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Off Code', 'mihanpanel')?></h3>
            <p class="mp_option_section_description"><?php esc_html_e("Off Code that you want to show in user panel", "mihanpanel"); ?></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Off Code", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mp_offer_code" type="text" name="mp_offer_code" value="<?php echo esc_attr(get_option('mp_offer_code')); ?>" />
            </div>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Off Code percentage",'mihanpanel'); ?></label>
                <input id="mp_offer_perc" type="text" name="mp_offer_perc" value="<?php echo esc_attr(\mihanpanel\app\options::get_offer_code_percentage_value()); ?>" />
            </div>
            <p class="mp_option_section_description"><?php esc_html_e("Off Code percentage will show in user panel. ex => 25%",'mihanpanel'); ?></p>
            <div class="mp_option_field mp_row_field">
                <label for="mp_off_code_text"><?php esc_html_e('Off Code text', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_off_code_text()?>
            </div>
            <p class="mp_option_section_description"><?php esc_html_e('Text that will show in user panel as a Off Code text.', 'mihanpanel')?></p>
            <p class="mp_option_section_description"><?php esc_html_e('Use [[number]] to replace with off code percentage.', 'mihanpanel')?></p>
        </div>
        

        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e("Color scheme", "mihanpanel")?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <div class="mp_option_field">
                <label for="mp_sidebar_bg_color"><?php esc_html_e("Sidebar background color in panel", "mihanpanel")?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_sidebar_bg_color(); ?>
            </div>
            <div class="mp_option_field">
                <label><?php esc_html_e("Sidebar link color that show in panel", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_sidebar_color(); ?>
            </div>
        </div>
        
        
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e("Dashboard Widgets", "mihanpanel")?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <div class="mp_option_field">
                <p>
                    <label><?php esc_html_e("Show register day count widget", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_show_register_day_count_widget(); ?>
                </p>
                <p>
                    <label><?php esc_html_e("Show Comment widget", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_show_comment_widget(); ?>
                </p>
                <p>
                    <label><?php esc_html_e("Show Woocommerce widget", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_show_woocommerce_widget(); ?>
                </p>
                <p>
                    <label><?php esc_html_e("Show EDD widget", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_show_edd_widget(); ?>
                </p>
                <p>
                    <label><?php esc_html_e("Show Awesome Support widget", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_show_awesome_support_widget(); ?>
                </p>
                <p>
                    <label><?php esc_html_e("Show Mihan Ticket widget", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_show_mihanticket_widget(); ?>
                </p>
            </div>
        </div>

        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e('Blog Section', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <div class="mp_option_field mp_row_field">
                <label for="mp_is_blog_section_enable"><?php esc_html_e('Is blog section enable?', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_is_blog_section_enable();?>
            </div>
            <div class="mp_option_field mp_row_field">
                <label for="mp_blog_section_title"><?php esc_html_e('Section Title', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_blog_section_title();?>
            </div>
            <div class="mp_option_field mp_row_field">
                <label for="mp_blog_section_title_link"><?php esc_html_e('Title Link', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_blog_section_title_link();?>
            </div>
            <div class="mp_option_field mp_row_field">
                <label for="mp_blog_section_posts_category"><?php esc_html_e('Posts category', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_blog_section_posts_category();?>
            </div>
            <div class="mp_option_field mp_row_field">
                <label for="mp_blog_section_posts_count"><?php esc_html_e('Posts count', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_blog_section_posts_count();?>
            </div>
        </div>

        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('Recent products', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel()?>
            <div class="mp_option_field">
                <?php if(\mihanpanel\app\tools::isProVersion() && !\mihanpanel\app\tools::is_woocommerce_active()): ?>
                    <div class="mp_option_notice_wrapper">
                        <p class="alert error"><?php _e('If the WooCommerce plugin is not active, the settings in this section will not be applied to the user panel.', 'mihanpanel')?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mp_option_field mp_row_field">
                <label for="mp_is_wc_recent_products_section_enable"><?php _e('Show recent products', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_is_wc_recent_products_enable(); ?>
            </div>
            <div class="mp_option_field mp_row_field" mwpl_show_depends_on="mp_is_wc_recent_products_section_enable">
                <label for="mp_wc_recent_products_section_title"><?php esc_html_e('Section Title', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_wc_recent_products_section_title();?>
            </div>
            <div class="mp_option_field mp_row_field" mwpl_show_depends_on="mp_is_wc_recent_products_section_enable">
                <label for="mp_wc_recent_products_section_link"><?php esc_html_e('Section Link', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_wc_recent_products_section_link();?>
            </div>
            <div class="mp_option_field mp_row_field" mwpl_show_depends_on="mp_is_wc_recent_products_section_enable">
                <label for="mp_wc_recent_products_section_items_categories"><?php esc_html_e('Products Category', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_wc_recent_products_section_items_category();?>
            </div>
            <div class="mp_option_field mp_row_field" mwpl_show_depends_on="mp_is_wc_recent_products_section_enable">
                <label for="mp_wc_recent_products_section_items_count"><?php esc_html_e('Products count', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_wc_products_section_items_count();?>
            </div>
        </div>
        
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('User roles', "mihanpanel")?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php _e("Roles who can't use admin panel", "mihanpanel")?></p>
            <?php \mihanpanel\app\handle_view::option_panel_field_ban_roles(); ?>
        </div>
    </form>
</div>