<?php
namespace mihanpanel\app;

class ajax
{

    public static function init()
    {
        add_action('wp_ajax_update_tabs_fields_data', [__CLASS__, 'update_tabs_fields_data']);
        add_action('wp_ajax_mw_delete_field_row', [__CLASS__, 'delete_field_row']);
        add_action('wp_ajax_update_user_field_fields_data', [__CLASS__, 'update_user_field_fields_data']);
    }
    static function update_tabs_fields_data()
    {
        $res = [
            'status' => 400,
            'msg' => __('Has error!', 'mihanpanel')
        ];
        $nonce = isset($_POST['mwpl_nonce']) ? sanitize_text_field($_POST['mwpl_nonce']) : false;
        if(!wp_verify_nonce($nonce, 'mwpl_ajax_update_tabs_fields_data'))
        {
            $res['msg'] = __('The operation failed due to security issues.', 'mihanpanel');
            die(json_encode($res));
        }
        $fields_data = isset($_POST['fields_data']) ? $_POST['fields_data'] : false;
        if(!$fields_data)
        {
            die(json_encode($res));
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpaneltabs';
        $success = false;
        foreach($fields_data as $index => $item)
        {
            $item_id = isset($item['id']) ? sanitize_text_field($item['id']) : false;
            if(!$item_id)
            {
                continue;
            }
            $name = sanitize_text_field($item['name']);
            $link_or_content = sanitize_text_field($item['link_or_content']);
            $link = filter_var($link_or_content, FILTER_VALIDATE_URL);
            $link = $link ? $link : false;
            $content = $link ? "" : $link_or_content;
            $icon = sanitize_text_field($item['icon']);
            $update_res = $wpdb->update(
                $table_name,
                [
                    'name' => $name,
                    'link' => $link,
                    'content' => $content,
                    'icon' => $icon,
                    'priority' => $index
                ],
                ['id' => $item['id']]
            );
            if($update_res)
            {
                $success = $update_res;
            }
        }
        $res['status'] = $success ? 200 : 400;
        $res['msg'] = $success ? esc_html__('Successfully updated!', 'mihanpanel') : esc_html__("Nothing updated.", 'mihanpanel');
        die(json_encode($res));
    }
    static function delete_field_row()
    {
        $id = isset($_POST['id']) && $_POST['id'] ? intval(sanitize_text_field($_POST['id'])) : false;
        $type = isset($_POST['type']) && $_POST['type'] ? sanitize_text_field($_POST['type']) : false;
        $res = [
            'status' => 400,
            'msg' => __('Has error!', 'mihanpanel')
        ];
        if(!$id || !$type)
        {
            die(json_encode($res));
        }
        $nonce_action = $type == 'tabs' ? $type : 'user';
        $nonce_action = 'mwpl_ajax_update_' . $nonce_action . '_fields_data';
        $nonce = isset($_POST['mwpl_nonce']) ? sanitize_text_field($_POST['mwpl_nonce']) : false;
        if(!wp_verify_nonce($nonce, $nonce_action))
        {
            $res['msg'] = __('The operation failed due to security issues.', 'mihanpanel');
            die(json_encode($res));
        }
        $type = $type == 'tabs' ? $type : 'fields';
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpanel' . $type;
        $delete_res = $wpdb->delete($table_name, ['id' => $id]);

        $res['status'] = $delete_res ? 200 : 400;
        $res['msg'] = $delete_res ?esc_html__("Successfully deleted!", 'mihanpanel') :esc_html__('Could not be deleted.', 'mihanpanel');
        die(json_encode($res));
    }
    static function update_user_field_fields_data()
    {
        $res = [
            'status' => 400,
            'msg' => __("Has error!", "mihanpanel")
        ];
        $nonce = isset($_POST['mwpl_nonce']) ? sanitize_text_field($_POST['mwpl_nonce']) : false;
        if(!wp_verify_nonce($nonce, 'mwpl_ajax_update_user_fields_data'))
        {
            $res['msg'] = __('The operation failed due to security issues.', 'mihanpanel');
            die(json_encode($res));
        }
        $fields_data = isset($_POST['fields_data']) ? $_POST['fields_data'] : false;
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpanelfields';
        $success = false;
        foreach($fields_data as $index => $field_data)
        {
            $parse_data = [];
            parse_str($field_data, $parse_data);
            $field_id = isset($parse_data['id']) ? sanitize_text_field($parse_data['id']) : false;
            if(!$field_id)
            {
                continue;
            }
            $meta = '';
            $meta = apply_filters('mwpl_option_panel/user_fields/new_mode/user_fields_meta', $meta, $parse_data);
            $slug = isset($parse_data['slug']) && $parse_data['slug'] ? sanitize_text_field($parse_data['slug']) : false;
            $label = isset($parse_data['label']) && $parse_data['label'] ? sanitize_text_field($parse_data['label']) : false;
            $required_field = isset($parse_data['required_field']) && $parse_data['required_field'] ? sanitize_text_field($parse_data['required_field']) : false;
            $type = isset($parse_data['type']) && $parse_data['type'] ? sanitize_text_field($parse_data['type']) : false;

            $data = [];
            $slug ? $data['slug'] = $slug : false;
            $label ? $data['label'] = $label : false;
            $required_field ? $data['required'] = $required_field : false;
            $type ? $data['type'] = $type : false;
            $data['meta'] = $meta ? $meta : '';
            $data['priority'] = $index;
            $update_res = $wpdb->update(
                $table_name,
                $data,
                ['id' => $field_id]
            );
            if($update_res)
            {
                $success = $update_res;
            }
        }
        $res['status'] = $success ? 200 : 400;
        $res['msg'] = $success ? esc_html__('Successfully updated!', 'mihanpanel') : esc_html__("Nothing updated.", 'mihanpanel');
        die(json_encode($res));
    }
}