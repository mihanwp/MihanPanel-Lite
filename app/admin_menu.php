<?php
namespace mihanpanel\app;

use mihanpanel\app\form\admin_user_fields;

class admin_menu
{
    public static function init()
    {
        self::mw_settings();
        self::tabs();
        self::user_fields();
    }

    public static function mw_settings()
    {
        $title = apply_filters('mwpl_option_panel_title', __('MihanPanel', 'mihanpanel'));
        $mw_settings_page = add_menu_page($title, $title, 'manage_options', 'mihanpanel', [__CLASS__, 'settings_c'], MW_MIHANPANEL_URL . 'img/mp-menu.png');
        add_action("load-{$mw_settings_page}", ['mihanpanel\app\assets', 'load_admin_panel_assets']);
    }

    public static function settings_c()
    {
        $menu_items = [
            'general' => __('Login & Register', 'mihanpanel'),
            'panel' => __('User Panel', 'mihanpanel'),
            'register' => __('Register Form', 'mihanpanel'),
            'notification' => __('Notifications', 'mihanpanel'),
            'login_with_sms' =>  __("Login with SMS", "mihanpanel"),
            'email' => __('Emails', 'mihanpanel'),
            'tools' => esc_html__('Tools', 'mihanpanel'),
        ];
        $settings = views::get('admin.settings');
        include $settings;
    }
    static function handle_tools_menu_submission()
    {
        if(isset($_POST['create_user_panel_page']))
        {
            $user_panel_page = array(
                'post_title' =>    __('User Panel','mihanpanel'),
                'post_content' => '[mihanpanel]',
                'post_status' => 'publish',
                'post_name' => 'panel',
                'post_type' => 'page'
            );
            $post_id = wp_insert_post($user_panel_page);
            $slug = get_post_field('post_name', $post_id);
            update_option('mp_panelslug', $slug);
        }
    }
    static function user_fields()
    {
        $user_fields_menu_title = __('User Fields', 'mihanpanel');
        $mw_user_field_menu = add_submenu_page('mihanpanel', $user_fields_menu_title, $user_fields_menu_title, 'manage_options', 'mihanpanel_fields', [__CLASS__, 'user_fields_c']);
        add_action("load-{$mw_user_field_menu}", ['mihanpanel\app\assets', 'load_user_field_menu_assets']);
    }
    static function user_fields_c()
    {
        if(!empty($_POST))
        {
            \mihanpanel\app\form\admin_user_fields::do($_POST);
        }
        ?>
        <div class="mihanpanel-admin">
            <?php
            do_action('mwpl_option_panel/before_render_user_fields_list');
            ?>
                <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                    <p>
                        <span><?php _e('Do you want to save changes?', 'mihanpanel')?></span>
                        <span><input mwpl_nonce="<?php echo wp_create_nonce('mwpl_ajax_update_user_fields_data')?>" class="mw_submit mw_ajax_update_fields_data" data-mw_type="user_field" type="submit" name="save_priority" value="<?php esc_attr_e('Yes', 'mihanpanel')?>"></span>
                    </p>
                </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <?php
            admin_user_fields::render_user_fields();
            ?>
            <h2><?php esc_html_e('Create new field', 'mihanpanel'); ?></h2>
    
            <form method="post">
                <?php admin_user_fields::render_new_record_fields()?>
            </form>
    
        </div>
        <?php
    }
    static function tabs()
    {
        $mw_menus_management_title = __('Panel Menus', 'mihanpanel');
        $mw_menus_management = add_submenu_page('mihanpanel', $mw_menus_management_title, $mw_menus_management_title, 'manage_options', 'mihanpanel_menus', [__CLASS__, 'tabs_c']);
        add_action("load-{$mw_menus_management}", ['mihanpanel\app\assets', 'load_menus_management_assets']);
    }
    static function tabs_c()
    {
        if (!empty($_POST)) {
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpaneltabs';
            // if add
            if (isset($_POST['add'])) {
                $middleware = middlewares::handle_middleware('tabs_field/new_record');
                if ($middleware && isset($_POST['name']) && wp_verify_nonce(sanitize_text_field($_POST['mwpl_nonce']), 'mwpl_create_tab_item')) {
                    $menucontent = str_replace('"', "'", $_POST['content']);
                    $success = $wpdb->insert(
                        $tablename,
                        array(
                            'name' => sanitize_text_field($_POST['name']),
                            'link' => sanitize_text_field($_POST['link']),
                            'content' => sanitize_text_field($menucontent),
                            'icon' => sanitize_text_field($_POST['icon']),
                        )
                    );
                    if ($success) {
                        echo '<p class="alert success">'.__("Successfully added! Watch user panel").'</p>';
                    } else {
                        echo '<p class="alert error">'.__("An error occurred!", "mihanpanel").'</p>';
                    }
                }
            }
        }
        ?>
        <div class="mihanpanel-admin">
            <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                <p>
                    <span><?php esc_html_e('Do you want to save changes?', 'mihanpanel')?></span>
                    <span><input mwpl_nonce="<?php echo wp_create_nonce('mwpl_ajax_update_tabs_fields_data')?>" class="mw_submit mw_ajax_update_fields_data" data-mw_type="tabs" type="submit" name="save_priority" value="<?php esc_attr_e("yes", "mihanpanel")?>"></span>
                </p>
            </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <table class="mihanmenustable mw_fields_wrapper" data-mw_type="tabs">
                <thead>
                    <tr>
                    <th></th>
                    <th><?php esc_html_e("Menu title", "mihanpanel")?></th>
                    <th><?php esc_html_e("Link / Shortcode", "mihanpanel")?></th>
                    <th><span><?php esc_html_e("Icon", "mihanpanel"); ?> </span></th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="mw_sortable">
                <?php
                global $wpdb;
                $tablename = $wpdb->prefix . 'mihanpaneltabs';
                $menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
                foreach ($menus as $menu):
                    $link_or_content = $menu->link ? $menu->link : $menu->content;
                    ?>
                    <tr class="mihanpanelmenulist mw_field_item">
                        <td><span class="mw_icon mw_sort_icon dashicons dashicons-menu"></span></td>
                        <td style="display:none"><input name="id" value="<?php echo esc_attr($menu->id); ?>"/></td>
                        <td><input name="name" value="<?php echo esc_attr($menu->name); ?>"/></td>
                        <td><input type="text" name="link_or_content" value="<?php echo esc_attr($link_or_content); ?>"></td>
                        <td class="field-icon-col">
                            <?php do_action('mwpl_option_panel/tabs_menu/edit_section/before_render_action_buttons'); ?>
                            <span tooltip="<?php esc_attr_e('FontAwesome', 'mihanpanel')?>" class="mwpl_action_btn fontawesome-handle">FA</span>
                            <span class="preview_icon"><?php \mihanpanel\app\presenter\tabs_menu::render_tab_item_icon($menu->icon); ?></span>
                            <input type="hidden" class="field-icon-<?php echo esc_attr($menu->id); ?>" name="icon" value="<?php echo esc_attr($menu->icon); ?>"/>
                        </td>
                        <th><input class="mihanpanelremove" type="submit" name="delete" value="x"/></th>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <h2><?php esc_html_e("Create new item", "mihanpanel")?></h2>
            <table class="mihanmenustable">
                <form method="post">
                    <?php wp_nonce_field('mwpl_create_tab_item', 'mwpl_nonce'); ?>
                    <tr>
                        <th><?php esc_html_e("Menu Title", "mihanpanel")?></th>
                        <th><?php esc_html_e("Content", "mihanpanel")?></th>
                        <th><span><?php esc_html_e("Icon", "mihanpanel")?> </span></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><input name="name" placeholder="<?php esc_attr_e("Menu Title", "mihanpanel")?>"/></td>
                        <td>
                            <select id="linkorcontent" onchange="yesnoCheck();">
                                <option><?php esc_html_e("Choose", "mihanpanel"); ?></option>
                                <option value="optionlink"><?php esc_html_e("Link to other page", "mihanpanel")?></option>
                                <option value="optioncontent"><?php _e("Shortcode", "mihanpanel"); ?></option>
                            </select>
                            <input class="hiddenoutput" id="islink" style="direction:ltr;text-align:left" name="link"
                                   placeholder="http://"/>
                            <select class="hiddenoutput" id="iscontent">
                            <?php
                            global $shortcode_tags;
                            $shortcodes = $shortcode_tags;
                            ksort( $shortcodes );
                            if($shortcodes):
                                ?>
                                <option value="mwp_custom_shortcode"><?php esc_html_e('Custom Shortcode', 'mihanpanel')?></option>
                                <?php
                                foreach( $shortcodes as $code => $function ): ?>
                                    <option value="[<?php echo esc_attr($code); ?>]"><?php echo esc_html($code); ?></option>
                            <?php endforeach;endif; ?>
                            </select>
                            <input class="hiddenoutput" name="content" id="custom_shortcode_value" placeholder="[your_shortcode]">
                            <script>
                                function yesnoCheck() {
                                    if (document.getElementById("linkorcontent").value == "optionlink") {
                                        document.getElementById("islink").style.display = "block";
                                    } else {
                                        document.getElementById("islink").style.display = "none";
                                    }
                                    if (document.getElementById("linkorcontent").value == "optioncontent") {
                                        document.getElementById("iscontent").style.display = "block";
                                        document.getElementById("custom_shortcode_value").style.display = "block";
                                    } else {
                                        document.getElementById("iscontent").style.display = "none";
                                        document.getElementById("custom_shortcode_value").style.display = "none";
                                    }
                                }
                            </script>
                        </td>
                        <td><input class="mw-new-tab-record-icon" name="icon" placeholder="<?php esc_attr_e("Icon", "mihanpanel"); ?>"/></td>
                        <td><input type="submit" name="add" value="+"/></td>
                    </tr>
                </form>
            </table>
        </div>
        <?php
    }
}