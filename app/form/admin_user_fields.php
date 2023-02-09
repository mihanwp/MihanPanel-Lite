<?php
namespace mihanpanel\app\form;

use mihanpanel\app\user_fields;

class admin_user_fields
{
    static function do()
    {
        global $wpdb;
        $tabel_name = $wpdb->prefix . 'mihanpanelfields';
        if(isset($_POST['save']))
        {
            // new mode
            self::new_mode($wpdb, $tabel_name);
        }
        do_action('mwpl_option_panel/handle_user_fields_form_submit');
    }
    static function new_mode($wpdb, $tabel_name)
    {
        $slug = isset($_POST['slug']) && $_POST['slug'] ? sanitize_text_field($_POST['slug']) : false;
        $label = isset($_POST['label']) && $_POST['label'] ? sanitize_text_field($_POST['label']) : false;
        $required = isset($_POST['required']) && $_POST['required'] ? sanitize_text_field($_POST['required']) : false;
        $type = isset($_POST['type']) && $_POST['type'] ? sanitize_text_field($_POST['type']) : false;
        if ($slug && $label && $required && $type) {
            if(!wp_verify_nonce(sanitize_text_field($_POST['mwpl_nonce']), 'mwpl_create_field_item'))
            {
                printf('<p class="alert error">%s</p>', __('The operation failed due to security issues.', 'mihanpanel'));
                return false;
            }
            $meta = apply_filters('mwpl_option_panel/user_fields/new_mode/user_fields_meta', '', null);
            $data = [
                'slug' => sanitize_text_field($slug),
                'label' => sanitize_text_field($label),
                'required' => sanitize_text_field($required),
                'type' => sanitize_text_field($type),
                'priority' => 0
            ];
            if($meta)
            {
                $data['meta'] = $meta;
            }
            $success = $wpdb->insert(
                $tabel_name,
                $data
            );
            if ($success) {

                echo '<p class="alert success">'.__("Settings successfully updated!", 'mihanpanel').'</p>';

            } else {

                echo '<p class="alert error">'.__('An error occurred!', 'mihanpanel').'</p>';

            }

        } else {

            echo '<p class="alert error">'.__('Please fill all the fields', 'mihanpanel').'</p>';

        }
    }
    
    static function render_user_fields()
    {
        $fields = user_fields::get_fields();
        $table_header = [
            [
                'title' => esc_html__('Field Id', 'mihanpanel'),
                'class' => 'mw_large_width',
            ],
            [
                'title' => esc_html__('Title', 'mihanpanel'),
                'class' => 'mw_large_width',
            ],
            [
                'title' => esc_html__('Required', 'mihanpanel'),
                'class' => 'mw_medium_width',
            ],
            [
                'title' => esc_html__('Type', 'mihanpanel'),
                'class' => 'mw_medium_width',
            ],
        ];
        ?>
        <div class="fields_wrapper">
            <h2><?php esc_html_e('Fields', 'mihanpanel'); ?><span class="open_new_field_section action_btn"><?php esc_html_e('New Field', 'mihanpanel')?></span></h2>
            <div class="mw_menus_table mw_fields_wrapper" data-mw_type="user_field">
                <div class="mw_head">
                    <div class="mw_row">
                        <div class="mw_th mw_small_width"></div>
                        <?php if($table_header && is_array($table_header)):
                            foreach($table_header as $header_data):?>
                            <div class="mw_th <?php echo $header_data['class'] ? esc_attr($header_data['class']) : '';?>"><?php echo esc_html($header_data['title']) ?></div>
                        <?php endforeach; endif; ?>
                        <div class="mw_th"></div>
                    </div>
                </div>
                <div class="mw_body mw_sortable">
                    <?php foreach($fields as $field): ?>
                        <form method="post" class="mw_rows mw_field_item">
                            <div class="mw_row">
                                <div style="display: none;" class="mw_td">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($field->id); ?>">
                                </div>
                                <div class="mw_td mw_small_width">
                                    <span class="mw_drag_handle mw_icon mw_sort_icon dashicons dashicons-menu"></span>
                                </div>
                                <div class="mw_td mw_large_width">
                                    <input type="text" name="slug" value="<?php echo esc_attr($field->slug); ?>">
                                </div>
                                <div class="mw_td mw_large_width">
                                    <input type="text" name="label" value="<?php echo esc_attr($field->label);?>">
                                </div>
                                <div class="mw_td mw_medium_width">
                                    <select name="required_field">
                                        <option <?php selected($field->required, 'yes'); ?> value="yes"><?php _e('Required', 'mihanpanel')?></option>
                                        <option <?php selected($field->required, 'no');?> value="no"><?php _e('Optional', 'mihanpanel');?></option>
                                    </select>
                                </div>
                                <div class="mw_td mw_medium_width">
                                    <?php \mihanpanel\app\handle_view::render_user_fields_type_selectbox('type', $field->type); ?>
                                </div>
                                <div drop_down_target_id="meta_fields_wrapper_<?php echo esc_attr($field->id); ?>" class="mw_td mwpl_drop_down">
                                    <span class="mw_drop_down_btn mw_icon dashicons dashicons-arrow-down-alt2"></span>
                                </div>
                                <div class="mw_th">
                                    <input type="submit" class="mihanpanelremove" name="delete" value="x">
                                </div>
                                
                            </div>
                            <?php do_action('mwpl_option_panel/user_fields/after_render_field', $field);?>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
    static function render_new_record_fields()
    {
        wp_nonce_field('mwpl_create_field_item', 'mwpl_nonce');
        ?>
        <div class="mw_menus_table new_record">
            <div class="row">
                <label for="new_record_slug"><?php esc_html_e('Field Id', 'mihanpanel');?></label>
                <input id="new_record_slug" type="text" name="slug">
            </div>
            <div class="row">
                <label for="new_record_title"><?php esc_html_e('Title', 'mihanpanel')?></label>
                <input type="text" id="new_record_title" name="label">
            </div>
            <div class="row">
                <label for="new_record_is_required"><?php esc_html_e('Required', 'mihanpanel')?></label>
                <select id="new_record_is_required" name="required">
                    <option value="yes"><?php _e('Required', 'mihanpanel')?></option>
                    <option value="no"><?php _e('Optional', 'mihanpanel');?></option>
                </select>
            </div>
            <?php do_action('mwpl_option_panel/user_fields/new_record_form/before_render_submit_button'); ?>
            <div class="row">
                <label for="new_record_type"><?php esc_html_e('Type', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::render_user_fields_type_selectbox('type'); ?>
            </div>
            <?php do_action('mwpl_option_panel/user_fields/new_record_form/after_render_fields'); ?>
            <input type="submit" name="save" value="<?php _e('Save', 'mihanpanel')?>">
        </div>
        <?php
    }
}