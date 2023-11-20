<?php do_action('mwpl_login_before_login_form_start'); ?>
<form class="mwpl-form-wrapper mwpl-login-form <?php echo implode(' ', apply_filters('mwpl_login_form/form_classes', []))?>" method="post">
    <?php do_action('mwpl_login_form_after_start_form'); ?>
    <div class="mwpl-form-fields">
        <div class="mwpl-input-item">
            <label for="username">
                <span class="mwpl-icon">
                    <img src="<?php echo \mihanpanel\app\assets::get_image_url('email-icon', 'svg') ?>" alt="email-icon">
                </span>
                <span class="mwpl-field">
                    <input type="text" name="username" id="username">
                    <span class="mwpl-label-value"><?php _e('Username', 'mihanpanel')?></span>
                </span>

            </label>
        </div>

        <div class="mwpl-input-item">
            <label for="password">
                <span class="mwpl-icon">
                    <img src="<?php echo \mihanpanel\app\assets::get_image_url('password-icon', 'svg') ?>" alt="pass-icon">
                </span>
                <span class="mwpl-field">
                    <input type="password" name="password" id="password">
                    <span class="mwpl-label-value"><?php _e('Password', 'mihanpanel')?></span>
                </span>
                <span class="mwpl-action-icon" mwpl-action="show">
                    <img mwpl-action="show-pass" src="<?php echo \mihanpanel\app\assets::get_image_url('show-pass-icon', 'svg') ?>" alt="show-pass-icon">
                    <img mwpl-action="hide-pass" src="<?php echo \mihanpanel\app\assets::get_image_url('hide-pass-icon', 'svg') ?>" alt="hide-pass-icon">
                </span>
            </label>
            <?php \mihanpanel\app\reset_password::render_reset_password_button(); ?>
        </div>

        <?php do_action('mwpl_login_form_before_submit_button'); ?>
        <div class="mwpl-notice-wrapper">
            <div class="mwpl-notice"></div>
            <div class="mwpl-action-bar"></div>
        </div>
        <div class="mwpl-input-item mwpl-spinner-field">
            <input type="submit" name="login" value="<?php _e('Login', 'mihanpanel')?>">
            <div class="mwpl-spinner-wrapper">
                <span class="mwpl-spinner"></span>
            </div>

        </div>
        <?php if (\mihanpanel\app\options::get_user_can_register() && !isset($_GET['mwpl_register_status']) && !isset($_GET['activation_status'])) : ?>
            <div class="mwpl-input-item">
                <div class="mwpl-register-link mwpl-button mp-toggle-form-btn">
                    <span><?php _e('Create new account', 'mihanpanel') ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</form>
<?php do_action('mwpl_login_after_login_form_end'); ?>