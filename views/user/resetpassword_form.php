<?php do_action('mwpl_reset_password_before_form_start'); ?>
    <form class="mwpl-form-wrapper mwpl-reset-password-form" style="display:none" method="post">
        <?php do_action('mwpl_reset_password_after_start_form'); ?>
        <div class="mwpl-form-fields">
            <?php \mihanpanel\app\reset_password::render_reset_password_form_fields(); ?>

            <?php do_action('mwpl_reset_password_form_before_submit_button'); ?>
            <div class="mwpl-notice-wrapper">
                <div class="mwpl-notice"></div>
                <div class="mwpl-action-bar"></div>
            </div>
            <div class="mwpl-input-item mwpl-spinner-field">
                <input type="submit" name="reset_pass" value="<?php _e('Send Code', 'mihanpanel') ?>">
                <div class="mwpl-spinner-wrapper">
                    <span class="mwpl-spinner"></span>
                </div>
            </div>
            <div class="mwpl-input-item">
                <div class="mwpl-back-to-login mwpl-button mp-toggle-form-btn">
                    <span><?php _e('Login with your account', 'mihanpanel') ?></span>
                </div>
            </div>
        </div>
    </form>
<?php do_action('mwpl_reset_password_after_form_end'); ?>