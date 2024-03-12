<div>
    <form id="mwpl-option-form" class="mp_options_panel" method="post" action="options.php">
        <h2><?php esc_html_e("Register Form Options", "mihanpanel"); ?></h2>
        <?php
        settings_fields('mihanpanelsettings-register');
        ?>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('Top form message text', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Text that shows in top of register form", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_register_text(); ?>
            </div>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Sender email', 'mihanpanel')?></h3>
            <p class="mp_option_section_description"><?php esc_html_e("Register and other email send from this address", "mihanpanel"); ?></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Sender Email", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mp_send_emails_from" type="text" name="mp_send_emails_from" placeholder="info@siteshoma.com" value="<?php echo esc_attr(get_option('mp_send_emails_from')); ?>" />
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e("User Role", "mihanpanel"); ?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php esc_html_e("User can select this roles from list in registration form", "mihanpanel"); ?></p>
            <p class="mp_option_section_description"><?php esc_html_e("For disable this feature leave this section empty!", "mihanpanel"); ?></p>
            <?php \mihanpanel\app\handle_view::option_panel_field_roles_user_can_select(); ?>
        </div>
    </form>
</div>