<?php
namespace mihanpanel\app\form;

use mihanpanel\app\middlewares;

class admin_tabs
{
    static function do()
    {
        global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpaneltabs';
            // if add
            if (isset($_POST['add'])) {
                $middleware = middlewares::handle_middleware('tabs_field/new_record');
                if ($middleware && isset($_POST['name']) && wp_verify_nonce(sanitize_text_field($_POST['mwpl_nonce']), 'mwpl_create_tab_item')) {
                    $needle = ['"', '\\'];
                    $replacement = ["'", ''];
                    $menucontent = str_replace($needle, $replacement, $_POST['content']);
                    $data = [
                        'name' => sanitize_text_field($_POST['name']),
                        'link' => sanitize_text_field($_POST['link']),
                        'content' => sanitize_text_field($menucontent),
                        'icon' => sanitize_text_field($_POST['icon']),
                    ];
                    $meta = apply_filters('mwpl_option_panel/panel_tabs/tabs_field_meta', '', $_POST);
                    if($meta)
                    {
                        $data['meta'] = $meta;
                    }
                    $success = $wpdb->insert(
                        $tablename,
                        $data
                    );
                    if ($success) {
                        echo '<p class="alert success">'.__("Successfully added! Watch user panel", 'mihanpanel').'</p>';
                    } else {
                        echo '<p class="alert error">'.__("An error occurred!", "mihanpanel").'</p>';
                    }
                }
            }
    }
    static function render_fields()
    {
        $table_header = [
            [
                'title' => esc_html__("Menu title", "mihanpanel"),
                'class' => 'mw_large_width',
            ],
            [
                'title' => esc_html__("Link / Shortcode", "mihanpanel"),
                'class' => 'mw_large_width',
            ],
            [
                'title' => esc_html__("Icon", "mihanpanel"),
                'class' => 'mw_large_width',
            ],
        ];
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpaneltabs';
        $menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
        
        ?>
        <div class="fields_wrapper">
            <h2><?php esc_html_e('Tabs', 'mihanpanel'); ?><span class="open_new_field_section action_btn"><?php esc_html_e('New Field', 'mihanpanel')?></span></h2>
            <div class="mw_menus_table mw_fields_wrapper" data-mw_type="tabs">
                <div class="mw_head">
                    <div class="mw_row">
                        <div class="mw_th mw_small_width"></div>
                        <?php if($table_header && is_array($table_header)):
                            foreach($table_header as $header_data): ?>
                            <div class="mw_th <?php echo $header_data['class'] ? $header_data['class'] : '';?>"><?php echo esc_html($header_data['title']) ?></div>
                        <?php endforeach; endif; ?>
                        <div class="mw_th"></div>
                    </div>
                </div>

                <div class="mw_body mw_sortable">
                <?php foreach ($menus as $menu):
                    $link_or_content = $menu->link ? $menu->link : $menu->content;
                    ?>
                    <form method="post" class="mw_rows mw_field_item">
                        <div class="mw_row">
                            <div class="mw_td mw_small_width">
                                <span class="mw_icon mw_sort_icon dashicons dashicons-menu"></span>
                            </div>
                            <div style="display: none;" class="mw_td">
                                <input type="hidden" name="id" value="<?php echo esc_attr($menu->id); ?>"/>
                            </div>
                            <div class="mw_td mw_large_width">
                                <input type="text" name="name" value="<?php echo esc_attr($menu->name); ?>"/>
                            </div>
                            <div class="mw_td mw_large_width">
                                <input type="text" name="link_or_content" value="<?php echo esc_attr($link_or_content); ?>">
                            </div>
                            <div class="mw_td mw_large_width">
                                <?php do_action('mwpl_option_panel/tabs_menu/edit_section/before_render_action_buttons'); ?>
                                <span tooltip="<?php esc_attr_e('FontAwesome', 'mihanpanel')?>" class="mwpl_action_btn fontawesome-handle">FA</span>
                                <span class="preview_icon"><?php \mihanpanel\app\presenter\tabs_menu::render_tab_item_icon($menu->icon); ?></span>
                                <input type="hidden" class="field-icon-<?php echo esc_attr($menu->id); ?>" name="icon" value="<?php echo esc_attr($menu->icon); ?>"/>
                            </div>
                            <div drop_down_target_id="mw_field_content_<?php echo $menu->id;?>" class="mw_td mwpl_drop_down">
                                <span class="mw_drop_down_btn mw_icon dashicons dashicons-arrow-down-alt2"></span>
                            </div>
                            <div class="mw_td">
                                <input class="mihanpanelremove" type="submit" name="delete" value="x"/>
                            </div>
                        </div>
                        <div id="mw_field_content_<?php echo $menu->id;?>" mwpl_drop_down_content class="meta_fields_wrapper extra_fields_wrapper">
                            <div class="row">
                                <label class="block" for="field_choosen_id_<?php echo $menu->id;?>"><?php esc_html_e('Field ID', 'mihanpanel')?></label>
                                <?php \mihanpanel\app\handle_view::render_panel_tabs_fields_field_id($menu);?>
                            </div>
                            <div class="row">
                                <label for="is_show_by_roles_<?php echo $menu->id;?>"><?php esc_html_e('Show by roles', 'mihanpanel')?></label>
                                <?php \mihanpanel\app\handle_view::render_panel_tabs_fields_is_show_by_role($menu);?>
                            </div>
                        </div>
                    </form>
                <?php endforeach;?>
                </div>

            </div>
        </div>
        <?php
    }
    static function render_new_record_fields()
    {
        wp_nonce_field('mwpl_create_tab_item', 'mwpl_nonce');
        ?>
        <div class="mw_menus_table new_record">
            <div class="row">
                <label for="menu_title"><?php esc_html_e('Menu Title', 'mihanpanel');?></label>
                <input placeholder="<?php esc_attr_e("Menu Title", "mihanpanel")?>" id="menu_title" type="text" name="name">
            </div>
            <div class="row">
                <label for="linkorcontent"><?php esc_html_e('Content', 'mihanpanel')?></label>
                <select id="linkorcontent">
                    <option selected disabled><?php esc_html_e("Choose", "mihanpanel"); ?></option>
                    <option value="optionlink"><?php esc_html_e("Link to other page", "mihanpanel")?></option>
                    <option value="optioncontent"><?php esc_html_e("Shortcode", "mihanpanel"); ?></option>
                </select>
            </div>
            <div class="row" depends="linkorcontent" depends_value='optionlink'>
                <label for="islink"><?php esc_html_e('Link', 'mihanpanel')?></label>
                <input type="text" id="islink" style="direction:ltr;text-align:left" name="link" placeholder="http://"/>
            </div>
            <div class="row" depends="linkorcontent" depends_value='optioncontent'>
                <?php $shortcodes = \mihanpanel\app\tools::get_all_shortcodes();?>
                <label for="iscontent"><?php esc_html_e('Shortcode', 'mihanpanel')?></label>
                <select id="iscontent">
                    <?php if($shortcodes):?>
                        <option value="0" selected disabled><?php esc_html_e('Choose your item', 'mihanpanel')?></option>
                        <option value="mwp_custom_shortcode"><?php esc_html_e('Custom Shortcode', 'mihanpanel')?></option>
                        <?php foreach( $shortcodes as $code => $function ): ?>
                            <option value="[<?php echo esc_attr($code); ?>]"><?php echo esc_html($code); ?></option>
                        <?php endforeach;?>
                    <?php else: ?>
                        <option value="0"><?php esc_html_e('No any shortcodes found.', 'mihanpanel')?></option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="row" depends="iscontent" depends_value="mwp_custom_shortcode">
                <label for="custom_shortcode_value"><?php esc_html_e('Custom Shortcode', 'mihanpanel')?></label>
                <input type='text' name="content" id="custom_shortcode_value" placeholder="[your_shortcode]">
            </div>
            <div class="row">
                <label for="new_record_icon"><?php esc_html_e('Icon', 'mihanpanel')?></label>
                <input type="text" id="new_record_icon" class="mw-new-tab-record-icon" name="icon" placeholder="<?php esc_attr_e("Icon", "mihanpanel"); ?>"/>
            </div>
            <?php do_action('mwpl_option_panel/panel_tabs/new_record_form/before_submit_button');?>
            <div class="row">
                <input type="submit" name="add" value="+"/>
            </div>
        </div>
        <?php
    }
}