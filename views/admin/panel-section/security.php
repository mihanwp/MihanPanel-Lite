<div>
    <form id="mwpl-option-form" class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-security' );
        ?>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php esc_html_e('Two Factor Authentication', 'mihanpanel')?></h3>
            <p class="mp_option_section_description"><?php esc_html_e('Use authenticator apps', 'mihanpanel')?></p>

            <div class="mp_option_field">
                <p>
                    <label for="mp_smart_login_2fa"><?php esc_html_e('Two factor authentication', 'mihanpanel')?></label>
                    <input type="checkbox" value="1" <?php checked(1, \mihanpanel\app\options::get_smart_login_2fa_status()); ?> name="mp_smart_login_2fa" id="mp_smart_login_2fa">
                    <span class="description"><?php esc_html_e('Use authenticator apps', 'mihanpanel')?></span>
                </p>
            </div>
        </div>
        
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Wordpress login form', 'mihanpanel')?></h3>
            <div class="mp_option_field">
                <p>
                    <label for="mwpl_redirect_wp_login_tp_mwpl_login"><?php _e('Redirect wordpress default login to MihanPanel login', 'mihanpanel')?></label>
                    <input type="checkbox" value="1" <?php checked(1, \mihanpanel\app\options::get_mwpl_redirect_wp_login_to_mwpl_login_status()); ?> name="mwpl_redirect_wp_login_tp_mwpl_login" id="mwpl_redirect_wp_login_tp_mwpl_login">
                </p>
            </div>
        </div>
        
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Login Guard', 'mihanpanel')?></h3>
            <p class="mp_option_section_description"><?php _e('Strong shield for login system', 'mihanpanel')?></p>
            <div class="mp_option_field">
                <p>
                    <label for="mwpl_login_guard_status"><?php _e('Guard status', 'mihanpanel')?></label>
                    <input type="checkbox" value="1" <?php checked(1, \mihanpanel\app\options::getLoginGuardStatus()); ?> name="mwpl_login_guard_status" id="mwpl_login_guard_status">
                </p>
                <p>
                    <label for="mwpl_login_guard_failed_attempt_count_tolerance"><?php esc_html_e("Number of wrong try", "mihanpanel"); ?></label>
                    <input mwpl_depends_on="mwpl_login_guard_status" id="mwpl_login_guard_failed_attempt_count_tolerance" type="number" name="mwpl_login_guard_failed_attempt_count_tolerance" value="<?php echo esc_attr(\mihanpanel\app\options::getLoginGuardFailedAttemptCount()); ?>" />
                    <p class="description"><?php esc_html_e("Block the user after making this number of mistakes", "mihanpanel"); ?></p>
                </p>
                <p>
                    <label for="mwpl_login_guard_unblock_timer_minutes"><?php esc_html_e("Unblock user after this time", "mihanpanel"); ?></label>
                    <input mwpl_depends_on="mwpl_login_guard_status" id="mwpl_login_guard_unblock_timer_minutes" type="number" name="mwpl_login_guard_unblock_timer_minutes" value="<?php echo esc_attr(\mihanpanel\app\options::getLoginGuardUnblockTimerMinutesValue()); ?>" />
                    <p class="description"><?php esc_html_e("The user will be unblocked after this period ( enter minutes value )", "mihanpanel"); ?></p>
                </p>
            </div>
        </div>
        
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("Captcha", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\handle_view::option_panel_custom_captcha(); ?>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("Google Recaptcha", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\handle_view::option_panel_google_recaptcha(); ?>
        </div>

        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("Files allowed to upload", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\handle_view::option_panel_files_allowed_upload(); ?>
        </div>
    </form>
</div>