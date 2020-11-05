<div>
    <h2><?php esc_html_e("Register Form", "mihanpanel"); ?></h2>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-register' );
        ?>
        <div class="mp_option_single">
            <label><?php esc_html_e("Text that shows in top of register form", "mihanpanel"); ?></label>
            <?php \mihanpanel\app\handle_view::option_panel_field_register_text(); ?>
        </div>
        <div class="mp_option_single">
            <label><?php esc_html_e("Sender Email", "mihanpanel"); ?></label>
            <input style="text-align:left;direction:ltr" id="mp_send_emails_from" type="text" name="mp_send_emails_from" placeholder="info@siteshoma.com" value="<?php echo esc_attr(get_option('mp_send_emails_from')); ?>" />
            <p class="description"><?php esc_html_e("Register and other email send from this address", "mihanpanel"); ?></p>
        </div>
        <h2><?php esc_html_e("User Role", "mihanpanel");?></h2>
        <smal><p><?php esc_html_e("User can select this roles from list in registration form", "mihanpanel"); ?></p></smal>
        <smal><p><?php esc_html_e("For disable this feature leave this section empty!", "mihanpanel"); ?></p></smal>
        <?php \mihanpanel\app\handle_view::option_panel_field_roles_user_can_select(); ?>
        <?php submit_button(); ?>
    </form>
</div>
