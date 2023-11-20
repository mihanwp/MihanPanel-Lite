<form class="mwpl-form-wrapper mwpl-register-form-wrapper" style="display: none" method="post">
    <?php do_action('mwpl_register_form_after_start_form'); ?>
    <div class="mwpl-form-fields">
        <?php \mihanpanel\app\register::renderRegisterFormFields(); ?>
        <?php do_action('mwpl_register_form_before_submit_button'); ?>
        <div class="mwpl-notice-wrapper">
            <div class="mwpl-notice"></div>
            <div class="mwpl-action-bar"></div>
        </div>
        <div class="mwpl-input-item mwpl-spinner-field">
            <input type="submit" name="register" value="<?php _e('Register', 'mihanpanel')?>">
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