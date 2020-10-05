<div>
    <h2><?php _e("Register Form", "mihanpanel"); ?></h2>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings-register' );
        ?>
        <div class="mp_option_single">
            <label><?php _e("Text that shows in top of register form", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Sender Email", "mihanpanel"); ?></label>
            <input style="text-align:left;direction:ltr" id="mp_send_emails_from" type="text" name="mp_send_emails_from" placeholder="info@siteshoma.com" value="<?php echo esc_attr(get_option('mp_send_emails_from')); ?>" />
            <p class="description"><?php _e("Register and other emails will send from this address", "mihanpanel"); ?></p>
        </div>
        <h2><?php _e("User Roles", "mihanpanel");?></h2>
        <smal><p><?php _e("User can select this roles from list in registration form", "mihanpanel"); ?></p></smal>
        <smal><p><?php _e("For disable this feature leave this section empty!", "mihanpanel"); ?></p></smal>
        <div class="mp_option_single">
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <?php submit_button(); ?>
    </form>
</div>
