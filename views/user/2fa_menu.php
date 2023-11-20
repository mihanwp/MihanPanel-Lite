<div class="nocss main-panel mwtabb" id="2fa_menu">
    <div class="mihanpanel-section-title"><?php esc_html_e('Two factor authentication', 'mihanpanel'); ?></div>
    <div class="mp-content mihanpcontent">
        <div class="mihanpanel-card-content">
            <?php
            $multiple_notice = \mihanpanel\app\notice::once_get_multiple_notice();
            if ($multiple_notice) {
                if (is_array($multiple_notice)) {
                    foreach ($multiple_notice as $notice) {
                        echo '<div class="alert alert-' . esc_attr($notice['type']) . '">' . esc_html($notice['msg']) . '</div>';
                    }
                }
            }
            $current_user_id = get_current_user_id();
            $is_2fa_active = \mihanpanel\app\users::isActive2FA();
            $status_message = $is_2fa_active
                ? esc_html__('Two factor authentication is active.', 'mihanpanel')
                : esc_html__('Two factor authentication is inactive.', 'mihanpanel');


            $msg = '';
            $msgType = 'error';
            if (isset($_POST['submit_2fa'])) {
                if (wp_verify_nonce(sanitize_text_field($_POST['mwpl_nonce']), 'mwpl_update_user_2fa_options')) {
                    $_2fa_status = isset($_POST['is_active_2fa']) ? intval($_POST['is_active_2fa']) : false;
                    if (!$_2fa_status) {
                        \mihanpanel\app\users::deactivate2FA();
                        $msg = __('Two factor authentication successfully deactiavted', 'mihanpanel');
                        $msgType = 'success';
                    } else {
                        // vlidate new code
                        $_2fa_code = isset($_POST['_2fa_code']) ? sanitize_text_field($_POST['_2fa_code']) : false;
                        $_2fa_verification = \mihanpanel\app\google_otp::verifyOtpCode($_2fa_code, $current_user_id);
                        if ($_2fa_verification) {
                            \mihanpanel\app\users::activate2FA();
                            $msg = __('Two factor authentication successfully activated', 'mihanpanel');
                            $msgType = 'success';
                        }else{
                            $msg = __('Two factor authentication not activated', 'mihanpanel');
                            $msgType = 'error';
                        }
                    }
                }else{
                    // error nonce verification
                    $msg = __('The operation failed due to security issues.', 'mihanpanel');
                    $msgType = 'error';
                }
                \mihanpanel\app\notice::add_multiple_notice($msgType, $msg);

                $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : false;
                $location = add_query_arg(['tab' => $tab], get_the_permalink());
                wp_safe_redirect($location);
            }


            ?>
            <div class="notification-box <?php echo $is_2fa_active ? 'success' : 'error'; ?>"><?php echo $status_message; ?></div>

            <div class="two-factor-user-panel-wrapper">
                <form method="post">
                    <?php wp_nonce_field('mwpl_update_user_2fa_options', 'mwpl_nonce');?>

                    <div class="input-item">
                        <div class="form-group label-floating">
                            <p class="radio-mode">
                                <input <?php checked(1, $is_2fa_active) ?> type="checkbox" name="is_active_2fa" id="is_active_2fa" value="1">
                                <label for="is_active_2fa"><?php esc_html_e('Activate', 'mihanpanel') ?></label>
                            </p>
                        </div>
                    </div>
                    <div class="qr-code-wrapper">
                        <div class="help-msg"><?php esc_html_e('Scan this code via your authenticator application, then enter authentication code.', 'mihanpanel') ?></div>
                        <div class="qr-code"><?php \mihanpanel\app\google_otp::renderQrcode() ?></div>
                        <?php if ($is_2fa_active) : ?>
                            <span class="_2fa-active-mode"><?php esc_html_e('Two factor authentication is currently active.', 'mihanpanel') ?></span>
                        <?php else : ?>
                            <div>
                                <input type="text" name="_2fa_code" id="otp_code_field">
                            </div>
                        <?php endif; ?>
                    </div>

                    <button name="submit_2fa" type="submit" class="btn btn-primary pull-right"><?php esc_html_e("Save Changes", "mihanpanel"); ?></button>

                </form>
            </div>
        </div>
    </div>
</div>