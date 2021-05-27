<div>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-dynamic_password' );
        ?>
        <h2><?php esc_html_e('Smart Login', 'mihanpanel')?></h2>
        <div class="mp_option_single">
            <p>
                <label for="mp_smart_login"><?php esc_html_e('Activate smart login', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_smart_login(); ?>
            </p>
        </div>
        <h2><?php esc_html_e("Password Type", "mihanpanel"); ?></h2>
        <?php \mihanpanel\app\handle_view::option_panel_field_password_type(); ?>
        <h2><?php esc_html_e('Disable register form fields', 'mihanpanel')?></h2>
        <div class="mp_option_single">
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
        <h2><?php esc_html_e("User Phone", "mihanpanel"); ?></h2>
        <?php \mihanpanel\app\handle_view::option_panel_user_phone_section(); ?>
        <h2><?php esc_html_e("SMS Provider Settings", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <label><?php esc_html_e("Select Provider", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_sms_provider(); ?>
            <p class="description"><?php esc_html_e("If your sms service provider not exists in this list, ask theme to provide you with the relevant plugin.", "mihanpanel"); ?><a href="https://mihanwp.com/docs/mihanpanel-add-sms-provider/" target="_blank"><?php _e("According to this documents", "mihanpanel"); ?></a></p>
        </div>
        <div class="mw_sms_provider_settings">
            <?php do_action('mwpl_option_panel/sms_provider_settings');?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Mobile validation code sms content", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_phone_validation_sms_content(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Dynamic login code sms content", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_dynamic_code_sms_content(); ?>
        </div>
        <?php submit_button(); ?>
    </form>
</div>