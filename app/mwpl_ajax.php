<?php
namespace mwplite\app;
if(defined('ABSPATH') && !class_exists('mwpl_ajax'))
{
    class mwpl_ajax
    {
    
        public static function init()
        {
            add_action('wp_ajax_update_user_field_priority', __CLASS__ . '::devmode_update_user_field_priority');
            add_action('wp_ajax_update_tabs_priority', __CLASS__ . '::devmode_update_tabs_priority');
        
            add_action('wp_ajax_mwpl_update_tabs_fields_data', [__CLASS__, 'update_tabs_fields_data']);
            add_action('wp_ajax_mwpl_update_user_field_fields_data', [__CLASS__, 'update_user_field_fields_data']);
            add_action('wp_ajax_mw_delete_field_row', [__CLASS__, 'delete_field_row']);
        }
    
        public static function devmode_update_user_field_priority()
        {
            $fields_data = isset($_POST['fields_data']) && $_POST['fields_data'] ? $_POST['fields_data'] : false;
            $table_name = 'mihanpanelfields';
            $nonce = isset($_POST['nonce']) && $_POST['nonce'] ? sanitize_text_field($_POST['nonce']) : false;
            if(!$nonce || !wp_verify_nonce($nonce, 'mwpl_ajax_update_fields_priority'))
            {
                $res['status'] = 400;
                $res['msg'] = __('The operation failed due to security issues.', 'mihanpanel');
            }else{
                $res = self::update_priority($fields_data, $table_name);
            }
            die(json_encode($res));
        }
    
        public static function devmode_update_tabs_priority()
        {
            $fields_data = isset($_POST['fields_data']) && $_POST['fields_data'] ? $_POST['fields_data'] : false;
            $table_name = 'mihanpaneltabs';
            $nonce = isset($_POST['nonce']) && $_POST['nonce'] ? sanitize_text_field($_POST['nonce']) : false;
            if(!$nonce || !wp_verify_nonce($nonce, 'mwpl_ajax_update_tabs_priority'))
            {
                $res['status'] = 400;
                $res['msg'] = __('The operation failed due to security issues.', 'mihanpanel');
            
            }else{
                $res = self::update_priority($fields_data, $table_name);
            }
            die(json_encode($res));
        }
        private static function update_priority($fields_data, $table_name)
        {
            if (!$fields_data)
                die(json_encode(['stats' => 400]));
    
    
            $priority = 0;
            $success = '';
            global $wpdb;
            $table_name = $wpdb->prefix . $table_name;
            foreach ($fields_data as $item) {
                $update_res = $wpdb->update($table_name, ['priority' => $priority], ['id' => sanitize_text_field($item)]);
                if ($update_res && !$success)
                    $success = $update_res;
    
                $priority++;
            }
            $res = [];
            $res['status'] = $success ? 200 : 500;
            $res['msg'] = $success ? __('Successfully updated!', 'mihanpanel') : __("Nothing updated.", 'mihanpanel');
            return $res;
        }
        static function update_tabs_fields_data()
        {
            $res = [
                'status' => 400,
                'msg' => __('Has error', 'mihanpanel')
            ];
            $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : false;
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
                $field_id = isset($item['id']) ? sanitize_text_field($item['id']) : false;
                if(!$field_id)
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
                    ['id' => $field_id]
                );
                if($update_res)
                {
                    $success = $update_res;
                }
            }
            $res['status'] = $success ? 200 : 400;
            $res['msg'] = $success ? __('Successfully updated!', 'mihanpanel') : __("Nothing updated.", 'mihanpanel');
            die(json_encode($res));    
        }
        static function update_user_field_fields_data()
        {
            $res = [
                'status' => 400,
                'msg' => __("Has error", "mihanpanel")
            ];
            $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : false;
            if(!wp_verify_nonce($nonce, 'mwpl_ajax_update_user_field_fields_data'))
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
                $field_id = isset($parse_data['id']) ? $parse_data['id'] : false;
                if(!$field_id)
                {
                    continue;
                }
                $slug = isset($parse_data['slug']) && $parse_data['slug'] ? sanitize_text_field($parse_data['slug']) : false;
                $label = isset($parse_data['label']) && $parse_data['label'] ? sanitize_text_field($parse_data['label']) : false;
                $required_field = isset($parse_data['required_field']) && $parse_data['required_field'] ? sanitize_text_field($parse_data['required_field']) : false;
                $type = isset($parse_data['type']) && $parse_data['type'] ? sanitize_text_field($parse_data['type']) : false;

                $data = [];
                $slug ? $data['slug'] = $slug : false;
                $label ? $data['label'] = $label : false;
                $required_field ? $data['required'] = $required_field : false;
                $type ? $data['type'] = $type : false;
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
            $res['msg'] = $success ? __('Successfully updated!', 'mihanpanel') : __("Nothing updated.", 'mihanpanel');
            die(json_encode($res));
        }
        static function delete_field_row()
        {
            $id = isset($_POST['id']) && $_POST['id'] ? intval(sanitize_text_field($_POST['id'])) : false;
            $type = isset($_POST['type']) && $_POST['type'] ? sanitize_text_field($_POST['type']) : false;
            $res = [
                'status' => 400,
                'msg' => __('Has error', 'mihanpanel')
            ];
            if(!$id || !$type)
            {
                die(json_encode($res));
            }
            $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : false;
            $nonce_action = 'mwpl_ajax_modify_' . $type . '_record';
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
            $res['msg'] = $delete_res ? __("Successfully deleted!", 'mihanpanel') : __('Not deleted.', 'mihanpanel');
            die(json_encode($res));

        }
    }
}