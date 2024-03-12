<div>
    <form id="mwpl-option-form" class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-professional_login_options' );
        ?>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e('Smart Login', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php esc_html_e('Automatic login by phone number, email or username', 'mihanpanel')?></p>
            <div class="mp_option_field">
                <p>
                    <label for="mp_smart_login"><?php esc_html_e('Activate smart login', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_smart_login(); ?>
                </p>
                <p>
                    <label for="mp_smart_login_generate_random_username"><?php esc_html_e('Generate random username', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_smart_login_generate_random_username(); ?>
                </p>
                <p>
                    <label for="mp_force_get_phone_number_in_panel"><?php esc_html_e('Obtain phone number in panel', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_force_get_phone_number_in_smart_login_mode(); ?>
                </p>
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>

            <h3 class="mp_option_section_title"><?php esc_html_e("Password Type", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\handle_view::option_panel_field_password_type(); ?>
        </div>

        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php esc_html_e("Reset Password", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\handle_view::option_panel_reset_password(); ?>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php _e('Texts', 'mihanpanel')?></h3>
            <div class="mp_option_field">
                <p>
                    <label for="mp_edit_username_field_label_in_login_form"><?php _e('Change username label in login form', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_edit_username_field_label_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_username_field_label_value_in_login_form"><?php _e('Username label', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_username_field_label_value_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_edit_phone_field_label_in_login_form"><?php _e('Change phone label in login form', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_edit_phone_field_label_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_phone_field_label_value_in_login_form"><?php _e('Phone label', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_phone_field_label_value_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_edit_password_field_label_in_login_form"><?php _e('Change password label in login form', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_edit_password_field_label_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_password_field_label_value_in_login_form"><?php _e('Password label', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_password_field_label_value_in_login_form() ?>
                </p>
                <p>
                    <label for="mp_edit_dynamic_code_field_label_in_login_form"><?php _e('Change dynamic code label in login form', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_edit_dynamic_code_field_label_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_dynamic_code_field_label_value_in_login_form"><?php _e('Dynamic code label', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_dynamic_code_field_label_value_in_login_form() ?>
                </p>

                <p>
                    <label for="mp_edit_login_button_text_in_login_form"><?php _e('Change Login button text in login form', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_edit_login_button_text_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_login_button_text_value_in_login_form"><?php _e('Login button text', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_login_button_text_value_in_login_form() ?>
                </p>

                <p>
                    <label for="mp_edit_register_button_text_in_login_form"><?php _e('Change register button text in login form', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_edit_register_button_text_in_login_form(); ?>
                </p>
                <p>
                    <label for="mp_register_button_text_value_in_login_form"><?php _e('Register button text', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_register_button_text_value_in_login_form() ?>
                </p>
            </div>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php esc_html_e('WPML Settings', 'mihanpanel')?></h3>
            <div class="mp_option_field mp_row_field">
                <label for="mp_wpml_sms_just_in_persian"><?php esc_html_e('Active sms settings just in persian language', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_wpml_sms_just_in_persian(); ?>
            </div>
        </div>

        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e('Disable register form fields', 'mihanpanel')?></h3>
            <div class="mp_option_field">
                <p>
                    <label for="mp_hide_email_field_in_register_form"><?php esc_html_e('Disable email field', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_hide_email_field_in_register_form();?>
                </p>
                <p class="description"><?php esc_html_e('You can disable email field in register form if login with sms dynamic code is active.', 'mihanpanel')?></p>
                <p>
                    <label for="mp_hide_username_field_in_register_form"><?php esc_html_e('Disable username field', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_hide_username_field_in_register_form();?>
                </p>
                <p class="description"><?php esc_html_e('You can disable username field in register form if login with sms dynamic code is active.', 'mihanpanel')?></p>
                <p>
                    <label for="mp_hide_password_field_in_register_form"><?php esc_html_e('Disable password field', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_hide_password_field_in_register_form();?>
                </p>
                <p class="description"><?php esc_html_e('You can disable password field in register form if login with sms dynamic code is active.', 'mihanpanel')?></p>
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("User Phone", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\handle_view::option_panel_user_phone_section(); ?>
        </div>

        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("SMS Provider Settings", "mihanpanel"); ?></h3>
            <p class="mp_option_section_description"><?php esc_html_e("If your sms service provider not exists in this list, ask theme to provide you with the relevant plugin.", "mihanpanel"); ?><a href="https://mihanwp.com/docs/mihanpanel-add-sms-provider/" target="_blank"><?php _e("According to this documents", "mihanpanel"); ?></a></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Select Provider", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_sms_provider(); ?>
            </div>
        </div>
        <div class="mw_sms_provider_settings">
            <?php do_action('mwpl_option_panel/sms_provider_settings');?>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php _e('Mobile validation sms', 'mihanpanel')?></h3>
            <div class="mp_option_field mp_row_field mp_fit_label">
                <label><?php esc_html_e("Mobile validation code sms content", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_phone_validation_sms_content(); ?>
            </div>
            <p class="mp_option_section_description"><?php esc_html_e("You can use this:", "mihanpanel"); ?></p>
            <p class="mp_option_section_description">
                <span><?php esc_html_e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                <span><?php esc_html_e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                <span><?php esc_html_e("Activation Code", "mihanpanel"); ?>: </span><span>[[code]]</span>
            </p>
            <p class="mp_option_section_description"><?php _e('In some case of sms provider system you shoud put Laghv11 in sms content', 'mihanpanel') ?></p>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php _e('Dynamic code sms', 'mihanpanel')?></h3>
            <div class="mp_option_field mp_row_field mp_fit_label">
                <label><?php esc_html_e("Dynamic login code sms content", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_dynamic_code_sms_content(); ?>
            </div>
            <p class="mp_option_section_description"><?php esc_html_e("You can use this:", "mihanpanel"); ?></p>
            <p class="mp_option_section_description">
                <span><?php esc_html_e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                <span><?php esc_html_e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                <span><?php esc_html_e("Activation Code", "mihanpanel"); ?>: </span><span>[[code]]</span>
            </p>
            <p class="mp_option_section_description"><?php _e('In some case of sms provider system you shoud put Laghv11 in sms content', 'mihanpanel') ?></p>
        </div>
    </form>
</div>