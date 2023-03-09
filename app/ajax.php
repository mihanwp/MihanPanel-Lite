<?php
namespace mihanpanel\app;

use mihanpanel\app\users;

class ajax
{

    public static function init()
    {
        add_action('wp_ajax_update_tabs_fields_data', [__CLASS__, 'update_tabs_fields_data']);
        add_action('wp_ajax_mw_delete_field_row', [__CLASS__, 'delete_field_row']);
        add_action('wp_ajax_update_user_field_fields_data', [__CLASS__, 'update_user_field_fields_data']);
		add_action('wp_ajax_mw_update_user_account_status', [__CLASS__, 'update_user_account_status_callback']);
		add_action('wp_ajax_mwp_resend_user_account_activation_email', [__CLASS__, 'resend_user_account_activation_email_callback']);
		add_action('wp_ajax_nopriv_mwp_resend_user_account_activation_email', [__CLASS__, 'resend_user_account_activation_email_callback']);

        // live edit
        add_action('wp_ajax_mwpl_live_edit_tabs_fields_get_items', [__CLASS__, 'handle_live_edit_tabs_fields_get_items']);
    }
    static function checkNonce($action = 'mwpl_ajax_update_live_edit_options', $query = 'mwpl_nonce')
    {
        check_ajax_referer($action, $query);
    }
    static function handle_live_edit_tabs_fields_get_items()
    {
        self::checkNonce();
        $res = [
            'status' => 400,
            'msg' => __('Has error!', 'mihanpanel'),
        ];
        $items = \mihanpanel\app\panel::get_tabs();
        if (!$items) {
            $res['msg'] = esc_html__('No any items found', 'mihanpanel');
            self::send_res($res);
        }
        ob_start();
        $base = \mihanpanel\app\options::get_panel_url();
        foreach ($items as $item) :
            $item_url = esc_url(add_query_arg(['tab' => $item->id], $base));
?>
            <li tab-id="<?php echo esc_attr($item->id); ?>">
                <span class="movement-icon <?php echo !tools::isProVersion() ? 'pro-version-notice-emmit' : '';?>"></span>
                <a class="mwtaba" mwpl-href="<?php echo esc_attr($item_url) ?>">
                    <?php \mihanpanel\app\presenter\tabs_menu::render_tab_item_icon($item->icon); ?>
                    <p>
                        <input type="text" value="<?php echo esc_attr($item->name); ?>">
                    </p>
                </a>
                <span class="edit-icon"></span>
                <span class="remove-icon <?php echo !tools::isProVersion() ? 'pro-version-notice-emmit' : '';?>"></span>
            </li>
<?php endforeach;
        $data = ob_get_clean();
        $res['msg'] = 'ok';
        $res['status'] = 200;
        $res['items'] = $items;
        $res['data'] = $data;
        self::send_res($res);
    }
    static function send_res($res)
    {
        die(json_encode($res));
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
        if(!isset($_POST['fields_data']))
        {
            die(json_encode($res));
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpaneltabs';
        $success = false;
        foreach($_POST['fields_data'] as $index => $field_data)
        {
            $parsed_data = [];
            parse_str($field_data, $parsed_data);
            $parsed_data = \mihanpanel\app\tools::sanitize_array_values($parsed_data);
            $item_id = isset($parsed_data['id']) ? sanitize_text_field($parsed_data['id']) : false;
            if(!$item_id)
            {
                continue;
            }
            $name = sanitize_text_field($parsed_data['name']);
            $link_or_content = sanitize_text_field($parsed_data['link_or_content']);
            $link = filter_var($link_or_content, FILTER_VALIDATE_URL);
            $link = $link ? $link : false;
            $content = $link ? "" : str_replace('\\', '', $link_or_content);
            $icon = sanitize_text_field($parsed_data['icon']);
            $data = [
                'name' => $name,
                'link' => $link,
                'content' => $content,
                'icon' => $icon,
                'priority' => $index
            ];
            $meta = apply_filters('mwpl_option_panel/panel_tabs/tabs_field_meta', '', \mihanpanel\app\tools::sanitize_array_values($parsed_data));
            if($meta)
            {
                $data['meta'] = $meta;
            }
            $update_res = $wpdb->update(
                $table_name,
                $data,
                ['id' => $parsed_data['id']]
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
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpanelfields';
        $success = false;
        foreach($_POST['fields_data'] as $index => $field_data)
        {
            $parsed_data = [];
            parse_str($field_data, $parsed_data);
            $parsed_data = \mihanpanel\app\tools::sanitize_array_values($parsed_data);
            $field_id = isset($parsed_data['id']) ? sanitize_text_field($parsed_data['id']) : false;
            if(!$field_id)
            {
                continue;
            }
            $slug = isset($parsed_data['slug']) && $parsed_data['slug'] ? sanitize_text_field($parsed_data['slug']) : false;
            $label = isset($parsed_data['label']) && $parsed_data['label'] ? sanitize_text_field($parsed_data['label']) : false;
            $required_field = isset($parsed_data['required_field']) && $parsed_data['required_field'] ? sanitize_text_field($parsed_data['required_field']) : false;
            $type = isset($parsed_data['type']) && $parsed_data['type'] ? sanitize_text_field($parsed_data['type']) : false;

            $data = [];
            $slug ? $data['slug'] = $slug : false;
            $label ? $data['label'] = $label : false;
            $required_field ? $data['required'] = $required_field : false;
            $type ? $data['type'] = $type : false;
            $meta = apply_filters('mwpl_option_panel/user_fields/new_mode/user_fields_meta', '', \mihanpanel\app\tools::sanitize_array_values($parsed_data));
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
	
	public static function update_user_account_status_callback(){
		check_ajax_referer('mw_nonce', 'nonce');
		$user_id = sanitize_text_field($_POST['user_id']);
		$status = sanitize_text_field($_POST['status']);
		$statuses = users::get_user_account_statuses();
		$user_is_active = users::is_active_account($user_id);
		$success = false;
		
		if(!current_user_can('edit_user', get_current_user_id())){
            wp_send_json_error();
        }
		
		if(intval($user_id) && in_array($status, array_keys($statuses))){
			if($status === 'activate' && !$user_is_active){
				// Activate account
				$active = users::remove_activation_code($user_id);
				if($active){
					$success = true;
					do_action('mp_change_user_account_activation_status', $user_id, $status);
				}
			} elseif($status === 'deactivate' && $user_is_active) {
				// Deactivate account
				$deactive = users::create_activation_link($user_id);
				if($deactive){
					$success = true;
					do_action('mp_change_user_account_activation_status', $user_id, $status);
				}
			}
			if($success){
				wp_send_json_success();
			} else {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}
	}

    public static function resend_user_account_activation_email_callback()
    {
        $username = sanitize_text_field($_POST['username']);
        $user = username_exists($username) ? get_user_by('login', $username) : (email_exists($username) ? get_user_by('email', $username) : 0);

        if($user){
            users::set_activation_process($user->ID);
            wp_send_json_success([
                'msg' => __('The account activation link has been sent to the user email.', 'mihanpanel')
            ]);
        } else {
            wp_send_json_error([
                'msg' => __('Invalid Username or Email.', 'mihanpanel')
            ]);
        }
    }
}