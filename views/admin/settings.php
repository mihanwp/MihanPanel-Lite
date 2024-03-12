<div class="mw_wrap <?php echo is_rtl() ? 'mwpl-rtl' : 'mwpl-ltr'; ?>">
    <div class="mw_admin_panel mihanpanel-admin">
        <div class="mw_menu_icons mw_icon dashicons dashicons-menu"></div>
        <div class="mw_sidebar_wrapper">
            <div class="mw_sidebar">
                <div class="mw_logo_section">
                    <div class="mw_logo">
                        <img src="<?php echo MW_MIHANPANEL_URL . 'img/logo.png'; ?>" width="100" height="100" alt="Mihan Panel Logo">
                    </div>
                    <div class="mw_hello">
                        <span><?php printf('%s %s', __('Welcome to MihanPanel', 'mihanpanel'), '👋')?></span>
                    </div>
                </div>
                <div class="mw_menu_section">
                    <ul>
                        <?php if($menu_items):
                            $white_list = array_keys($menu_items);
                            $active_tab = isset($_GET['tab']) && in_array($_GET['tab'], $white_list) ? sanitize_text_field($_GET['tab']) : $white_list[0];
                            ?>
                        <?php foreach ($menu_items as $itemID => $item): ?>
                            <li <?php echo $itemID == $active_tab ? 'class="active"' : ''; ?>>
                                <a href="<?php echo esc_url(add_query_arg(['tab' => $itemID])); ?>">
                                    <span class="menu-icon" tab="<?php echo $item['icon']?>"></span>
                                    <span class="menu-name"><?php echo esc_html($item['title']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="mw_copyright">
                    <?php
                    $baseUrl = \mihanpanel\app\tools::getBaseRemoteUrl();
                    $mwp_link = sprintf('<a href="%s">%s</a>', esc_url($baseUrl), esc_html__("MihanWp", "mihanpanel"));
                    $airline_text = __("New Airlines", "mihanpanel");
                    $text = is_rtl() ? sprintf("<span>%s <span>%s</span></span>", $airline_text, $mwp_link) : sprintf("<span>%s <span>%s</span></span>", $mwp_link, $airline_text);
                    echo $text;
                    ?>
                </div>
            </div>
        </div>

        <div class="mw_content_wrapper">
            <div class="mw_content">
                <?php \mihanpanel\app\handle_view::handle_option_panel_view($active_tab); ?>
            </div>
            <div class="mwpl_submit_wrapper">
                <?php submit_button(__('Save Changes', 'mihanpanel'), 'primary', 'submit', true, ['form' => 'mwpl-option-form']); ?>
            </div>
        </div>
    </div>
</div>
