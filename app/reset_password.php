<?php
namespace mihanpanel\app;

use mihanpanel\app\views;

class reset_password
{
    public static function init(){
        if (!options::is_active_reset_password())
            return false;

        add_action('login_enqueue_scripts', [__CLASS__, 'load_login_page_scripts']);
        add_action('mwpl_reset_password_after_start_form', ['\mihanpanel\app\login', 'addLogoBeforeTitle']);
        add_action('mwpl_login_after_login_form_end', [__CLASS__, 'addResetPasswordFormContentToLoginForm']);

        add_action('wp_ajax_nopriv_mwpl_reset_password_by_verification_code', ['\mihanpanel\app\ajax', 'reset_password_by_verification_code_callback']);
        add_action('wp_ajax_nopriv_mwpl_verify_code_for_reset_password', ['\mihanpanel\app\ajax', 'verify_code_for_reset_password_callback']);
    }

    static function addResetPasswordFormContentToLoginForm()
    {
        $registerView = views::get('user.resetpassword_form');
        $registerView ? include_once $registerView : null;
    }

    public static function load_login_page_scripts(){
        assets::enqueue_script('reset_password', assets::get_js_url('reset_password'));
        $data = [
            'au' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mwpl_reset_password'),
            'messages' => [
                'send_code' => __('Send code', 'mihanpanel'),
                'reset_password' => __('Reset Password', 'mihanpanel'),
                'sending_dynamic_code_process' => esc_html__('Sending dynamic code...', 'mihanpanel'),
                'back' => esc_html__('Back', 'mihanpanel'),
                'errors' => [
                    'empty_username' => esc_html__('Username must not be empty!', 'mihanpanel'),
                    'has_error' => esc_html__('Has error', 'mihanpanel'),
                ]
            ]
        ];
        assets::localize_script('reset_password', 'mwp_data', $data);
    }

    public static function get_fields(){
        $fields = [
                'username' => [
                    'icon' => \mihanpanel\app\assets::get_image_url('email-icon', 'svg'),
                    'label' => __('Username or Email', 'mihanpanel'),
                    'state' => 'normal_login'
                ]
        ];
        return apply_filters('mp_reset_password_fields', $fields);
    }

    public static function render_reset_password_form_fields()
    {
        $fields = self::get_fields();
        ?>
        <?php
        foreach ($fields as $field_name => $field_data):
            $id = isset($field_data['id']) ? $field_data['id'] : $field_name;
            ?>
        <div class="mwpl-input-item">
            <label for="username" class="form-state" data-state="<?php echo isset($field_data['state']) ? esc_attr($field_data['state']) : '' ?>">
                <span class="mwpl-icon">
                    <img src="<?php echo esc_attr($field_data['icon']) ?>" alt="<?php echo esc_attr($field_name) ?>">
                </span>
                <span class="mwpl-field">
                    <input type="text" name="<?php echo esc_attr($field_name) ?>" id="<?php echo esc_attr($id) ?>">
                    <span class="mwpl-label-value"><?php echo esc_attr($field_data['label']) ?></span>
                </span>
            </label>
        </div>
        <?php endforeach; ?>

        <div class="mwpl-input-item mwpl-code-field-wrapper mwpl-hide form-state" data-state="code">
            <label for="dynamic_code">
                <span class="mwpl-icon">
                    <img src="<?php echo \mihanpanel\app\assets::get_image_url('password-icon', 'svg') ?>" alt="password-icon">
                </span>
                <span class="mwpl-field">
                    <input type="text" name="dynamic_code" id="dynamic_code">
                    <span class="mwpl-label-value"><?php _e('Dynamic code', 'mihanpanel') ?></span>
                </span>
            </label>
        </div>

        <div class="mwpl-input-item mwpl-timer mwpl-hide" mwpl-exp="">
            <span class="mwpl-countdown">00:00</span>
        </div>
    <?php
    }

    public static function render_reset_password_button(){
        if (!options::is_active_reset_password())
            return false;

        echo sprintf('<a href="#" class="resetpassword-btn">%s</a>', __('Forgot your password?', 'mihanpanel'));
    }

    public static function send_reset_password_code_by_email($user, $code){
        if (!$user || empty($user->user_email) || empty($code))
            return false;

        $msg = sprintf(__('Verification code for reset password: %s', 'mihanpanel'), $code);
        return email::sendEmail($user->user_email, __('Verification Code', 'mihanpanel'), $msg, true);
    }

    public static function get_change_password_url(){
        $panelUrl = options::get_panel_url();
        $args = [
            'tab' => 'change-password'
        ];
        return add_query_arg($args, $panelUrl);
    }
}