<?php
namespace mihanpanel\app\form;

use mihanpanel\app\user_fields;

class admin_user_fields
{
    static function do($form_data)
    {
        global $wpdb;
        $tabel_name = $wpdb->prefix . 'mihanpanelfields';
        if(isset($form_data['save']))
        {
            // new mode
            self::new_mode($wpdb, $tabel_name, $form_data);
        }
        do_action('mwpl_option_panel/handle_user_fields_form_submit', $form_data);
    }
    static function new_mode($wpdb, $tabel_name, $form_data)
    {
        if (isset($form_data['slug'])) {
            if(!wp_verify_nonce(sanitize_text_field($form_data['mwpl_nonce']), 'mwpl_create_field_item'))
            {
                printf('<p class="alert error">%s</p>', __('The operation failed due to security issues.', 'mihanpanel'));
                return false;
            }
            $meta = '';
            $meta = apply_filters('mwpl_option_panel/user_fields/new_mode/user_fields_meta', $meta, $form_data);
            $data = [
                'slug' => sanitize_text_field($form_data['slug']),
                'label' => sanitize_text_field($form_data['label']),
                'required' => sanitize_text_field($form_data['required']),
                'type' => sanitize_text_field($form_data['type']),
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
        ?>
        <div class="mw_menus_table mw_fields_wrapper" data-mw_type="user_field">
            <div class="mw_head">
                <div class="mw_row">
                    <div class="mw_th"></div>
                    <div class="mw_th"><?php _e('Field Id', 'mihanpanel'); ?></div>
                    <div class="mw_th"><?php _e('Title', 'mihanpanel'); ?></div>
                    <div class="mw_th"><?php _e('Required', 'mihanpanel'); ?></div>
                    <div class="mw_th"><?php _e('Type', 'mihanpanel'); ?></div>
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
                            <div class="mw_td">
                                <span class="mw_drag_handle mw_icon mw_sort_icon dashicons dashicons-menu"></span>
                            </div>
                            <div class="mw_td">
                                <input type="text" name="slug" value="<?php echo esc_attr($field->slug); ?>">
                            </div>
                            <div class="mw_td">
                                <input type="text" name="label" value="<?php echo esc_attr($field->label);?>">
                            </div>
                            <div class="mw_td">
                                <select name="required_field">
                                    <option <?php selected($field->required, 'yes'); ?> value="yes"><?php _e('Required', 'mihanpanel')?></option>
                                    <option <?php selected($field->required, 'no');?> value="no"><?php _e('Optional', 'mihanpanel');?></option>
                                </select>
                            </div>
                            <div class="mw_td">
                                <?php \mihanpanel\app\handle_view::render_user_fields_type_selectbox('type', $field->type); ?>
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
        <?php
    }
    static function render_new_record_fields()
    {
        wp_nonce_field('mwpl_create_field_item', 'mwpl_nonce');
        ?>
        <div class="mw_menus_table new_record">
            <div class="mw_head">
                <div class="mw_row">
                    <div class="mw_th"></div>
                    <div class="mw_th"><?php _e('Field Id', 'mihanpanel'); ?></div>
                    <div class="mw_th"><?php _e('Title', 'mihanpanel'); ?></div>
                    <div class="mw_th"><?php _e('Required', 'mihanpanel'); ?></div>
                    <div class="mw_th"><?php _e('Type', 'mihanpanel'); ?></div>
                    <div class="mw_th"></div>
                </div>
            </div>
            <div class="mw_body">
                <div class="mw_row">
                    <div style="display: none;" class="mw_td"></div>
                    <div class="mw_td"></div>
                    <div class="mw_td">
                        <input type="text" name="slug">
                    </div>
                    <div class="mw_td">
                        <input type="text" name="label">
                    </div>
                    <div class="mw_td">
                        <select name="required">
                            <option value="yes"><?php _e('Required', 'mihanpanel')?></option>
                            <option alue="no"><?php _e('Optional', 'mihanpanel');?></option>
                        </select>
                    </div>
                    <div class="mw_td">
                        <?php \mihanpanel\app\handle_view::render_user_fields_type_selectbox('type'); ?>
                    </div>
                    <div class="mw_th">
                        <input type="submit" name="save" value="<?php _e('Save', 'mihanpanel')?>">
                    </div>
                </div>
                <?php do_action('mwpl_option_panel/user_fields/new_record_form/after_render_fields'); ?>
            </div>
        </div>
        <?php
    }
    
    
}