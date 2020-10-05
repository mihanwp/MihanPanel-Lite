<div class="mw_wrap">
    <div class="mw_admin_panel mihanpanel-admin">
        <div class="mw_menu_icons mw_icon dashicons dashicons-menu"></div>
        <div class="mw_sidebar">
            <div class="mw_logo_section">
                <img src="<?php echo esc_url(MW_MIHANPANEL_LITE_URL . 'img/logo.svg'); ?>" alt="Mihan Panel Logo">
            </div>
            <div class="mw_menu_section">
                <ul>
                    <?php if($menu_items):
                        $white_list = array_keys($menu_items);
                        $active_tab = isset($_GET['tab']) && in_array($_GET['tab'], $white_list) ? $_GET['tab'] : $white_list[0];
                        ?>
                    <?php foreach ($menu_items as $item => $name): ?>
                        <li <?php echo $item == $active_tab ? 'class="active"' : ''; ?>><a href="<?php echo esc_url(add_query_arg(['tab' => $item])); ?>"><?php echo esc_html($name); ?></a></li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="mw_copyright">
                <?php
                    $mwp_link = sprintf('<a href="https://mihanwp.com">%s</a>', __("MihanWP", "mihanpanel"));
                    $airline_text = __("New Airlines", "mihanpanel");
                    $text = is_rtl() ? sprintf("<span>%s <span>%s</span></span>", $airline_text, $mwp_link) : sprintf("<span>%s <span>%s</span></span>", $mwp_link, $airline_text);
                    echo $text;
                ?>
            </div>
        </div>

        <div class="mw_content_wrapper">
            <div class="mw_content">
                <?php
                $pro_version_link = \mwplite\app\mwpl_tools::get_pro_version_link();
                $view = \mwplite\app\mwpl_views::get('admin.panel-section.' . $active_tab);
                include $view;
                ?>
            </div>
        </div>


    </div>
</div>