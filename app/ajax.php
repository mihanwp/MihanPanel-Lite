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

        // custom login form
        add_action('wp_ajax_nopriv_mwpl_login_form_normal_login', [__CLASS__, 'handle_normal_login_process']);

        add_action('wp_ajax_nopriv_mwpl_register_form', [__CLASS__, 'handle_mwpl_register_form_process']);
    }
    static function checkNonce($action = 'mwpl_ajax_update_live_edit_options', $query = 'mwpl_nonce')
    {
        check_ajax_referer($action, $query);
    }
    static function handle_normal_login_process()
    {
        self::checkNonce('mwpl_login_nonce', 'nonce');
        $res['code'] = 409;
        $username = isset($_POST['username']) && $_POST['username'] ? sanitize_text_field($_POST['username']) : false;
        $password = isset($_POST['password']) && $_POST['password'] ? sanitize_text_field($_POST['password']) : false;

        do_action('mwpl_login_start_process_response', $res);

        if(!$username)
        {
            $res['msg'] = esc_html__('Please fill username field', 'mihanpanel');
            self::send_res($res);
        }

        if(!$password)
        {
            $res['msg'] = esc_html__('Please fill password field', 'mihanpanel');
            self::send_res($res);
        }

        // get user data
        $user = get_user_by('login', $username);
        if(!$user)
        {
            do_action('mwpl_login/normal_login/wrong_username');
            $res['msg'] = esc_html__('User not found', 'mihanpanel');
            self::send_res($res);
        }

        // check password
        if(!wp_check_password($password, $user->user_pass, $user->ID))
        {
            do_action('mwpl_login/normal_login/wrong_password');
            $res['msg'] = esc_html__('Invalid username or password', 'mihanpanel');
            self::send_res($res);
        }

        // check account status
        if(!\mihanpanel\app\users::is_active_account($user->ID))
        {
            $res['msg'] = esc_html__('Your account is disable!', 'mihanpanel');
            self::send_res($res);
        }

        // check is 2fa otp active in options and active for this user
        if(\mihanpanel\app\options::get_smart_login_2fa_status() && users::isActive2FA($user->ID))
        {
            $otpFieldValue = isset($_POST['otp_active']) && intval($_POST['otp_active']);
            if($otpFieldValue)
            {
                // validate otp field value
                $otpCodeValue = isset($_POST['otp_field']) && $_POST['otp_field'] ? sanitize_text_field($_POST['otp_field']) : false;
                if(!google_otp::verifyOtpCode($otpCodeValue, $user->ID))
                {
                    $res['msg'] = __('2FA code is invalid', 'mihanpanel');
                    self::send_res($res);
                }
            }else{

                // show otp field
                $res['handle_2fa'] = true;
                $res['msg'] = __('Please enter authentication code', 'mihanpanel');
                $res['code'] = 200;
                self::send_res($res);
            }
            
        }

        do_action('mwpl_login_end_process_response', $res);

        // handle login process
        login::doLogin($user->ID, true);
        $res['redirect_to'] = apply_filters('mwpl_after_login_redirect_url', \mihanpanel\app\options::get_panel_url(), $user->ID);
        $res['msg'] = esc_html__('Successfully logged in...', 'mihanpanel');
        $res['code'] = 200;

        $res = apply_filters('mwpl_login_process_success_response_filter', $res);
        
        self::send_res($res);
    }

    static function handle_mwpl_register_form_process()
    {
        self::checkNonce('mwpl_login_nonce', 'nonce');
        $res['code'] = 409;

        $username = isset($_POST['username']) && $_POST['username'] ? sanitize_text_field($_POST['username']) : false;
        $email = isset($_POST['email']) && $_POST['email'] ? sanitize_email($_POST['email']) : false;
        $password = isset($_POST['password']) && $_POST['password'] ? sanitize_text_field($_POST['password']) : false;

        do_action('mwpl_register_start_process_response', $res);

        // check if has username
        if(register::set_filter_for_register_data_validation('username-exists', username_exists($username)))
        {
            $res['msg'] = __('This username has already been chosen by someone else.', 'mihanpanel');
            self::send_res($res);
        }

        // validate email address
        if(register::set_filter_for_register_data_validation('email-check', !filter_var($email, FILTER_VALIDATE_EMAIL)))
        {
            $res['msg'] = __('Invalid email address', 'mihanpanel');
            self::send_res($res);
        }

        // check account with this email address
        if(register::set_filter_for_register_data_validation('email-check', email_exists($email)))
        {
            $res['msg'] = __('There is another account with this email in the system', 'mihanpanel');
            self::send_res($res);
        }

        // check password
        if(register::set_filter_for_register_data_validation('password-check', empty($password)))
        {
            $res['msg'] = __('Enter your password.', 'mihanpanel');
            self::send_res($res);
        }

        // validate extra fields
        do_action('mwpl_register_form_before_create_new_user', $res);
        $error = apply_filters('mwpl_register_form_fields_validation_error', false);
        if(is_wp_error($error))
        {
            $res['msg'] = $error->get_error_message();
            self::send_res($res);
        }
        
        // create user
        $newUserData = [
            'user_pass' => $password,
            'user_email' => $email,
            'user_login' => $username,
        ];
        $newUserData = apply_filters('mwpl_register_user_data', $newUserData);
        $newUserID = wp_insert_user($newUserData);
        if(is_wp_error($newUserID))
        {
            $res['msg'] = __('Has error in create your account', 'mihanpanel');
            self::send_res($res);
        }
        
        // save extra fields data
        do_action('mwpl_register_form_after_create_new_user', $newUserID);

        do_action('mwpl_register_end_process_response', $res);

        // check account activation type and handle message for showing in login view
        $activationAccountType = options::get_account_activation_type();
        $redirectArgs = [];
        if($activationAccountType)
        {
            $redirectArgs['mwpl_register_status'] = $activationAccountType;
        }

        // handle current redirect_to value in query string
        $redirectTo = isset($_POST['redirect_to_value']) && $_POST['redirect_to_value'] ? sanitize_url($_POST['redirect_to_value']) : false;
        if($redirectTo)
        {
            $redirectArgs['redirect_to'] = $redirectTo;
        }
        
        // check account activation type and handle message for showing in login view
        $res['redirect_data'] = [
            'redirect_to_url' => \mihanpanel\app\options::getMihanPanelLoginUrl(),
        ];
        if($redirectArgs)
        {
            $res['redirect_data']['params'] = $redirectArgs;
        }
        
        $res['msg'] = __('Account successfully created', 'mihanpanel');
        $res['code'] = 200;
        self::send_res($res);
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

    public static function reset_password_by_verification_code_callback(){
        $username = sanitize_text_field($_POST['username']);
        $formState = sanitize_text_field($_POST['form_state']);
        $allowedStates = ['normal_login', 'dynamic_login', 'smart_login'];
        $res = ['code' => 400];
        $sendBy = apply_filters('mp_reset_password_method', 'email', $username);

        do_action('mwpl_reset_password_start_process_response', $res);

        if (empty($formState) || !in_array($formState, $allowedStates)){
            $res['msg'] = __('Request is invalid!', 'mihanpanel');
            self::send_res($res);
        }

        $user = username_exists($username) ? get_user_by('login', $username) : (email_exists($username) ? get_user_by('email', $username) : 0);
        $user = apply_filters('mp_reset_password_get_user_data', $user, $username);

        do_action('mwpl_reset_password_between_process_response', $res);

        if($user){
            do_action('mp_reset_password_before_set_code', $user, $sendBy);

            $setNewCodeData = login::setUserDynamicCode($user->ID);
            $res['expiration'] = date('Y-m-d H:i:s', $setNewCodeData['expiration']);
            $res['code'] = 200;

            if ($setNewCodeData){
                do_action('mp_reset_password_before_send_code', $user, $setNewCodeData['code'], $sendBy);

                $res['msg'] = __('Verification code has been sent to email.', 'mihanpanel');
                $send = reset_password::send_reset_password_code_by_email($user, $setNewCodeData['code']);

                if (empty($send)){
                    $res['code'] = 400;
                    $res['msg'] = __('An error occurred!', 'mihanpanel');
                }
            }
        } else {
            $res['msg'] = __('User not exists!', 'mihanpanel');
        }

        do_action('mwpl_reset_password_end_process_response', $res);

        self::send_res($res);
    }

    public static function verify_code_for_reset_password_callback(){
        $code = sanitize_text_field($_POST['dynamic_code']);
        $username = sanitize_text_field($_POST['username']);
        $formState = sanitize_text_field($_POST['form_state']);

        $res = ['code' => 400];

        do_action('mwpl_reset_password_start_verify_code_process_response', $res);

        if (empty($formState) || $formState != 'code' || empty($code)){
            $res['msg'] = __('Request is invalid!', 'mihanpanel');
            self::send_res($res);
        }

        $user = username_exists($username) ? get_user_by('login', $username) : (email_exists($username) ? get_user_by('email', $username) : 0);
        $user = apply_filters('mp_reset_password_user_data_for_verify_code', $user, $username);

        if (!$user){
            $res['msg'] = __('User not exists!', 'mihanpanel');
            self::send_res($res);
        }

        session::unset('required_change_password');

        $verifyCode = login::validateDynamicCode($code, $user->ID);

        if (!$verifyCode){
            $res['msg'] = __('The verification code is invalid.', 'mihanpanel');
        } else {
            $res['code'] = 200;
            $res['msg'] = __('Verification done, transferring...', 'mihanpanel');
            $res['redirect_to'] = reset_password::get_change_password_url();

            login::doLogin($user->ID, true);
            session::store('required_change_password', true);
        }

        self::send_res($res);
    }
}