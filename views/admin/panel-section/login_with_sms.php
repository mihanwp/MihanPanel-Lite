<div>
    <form class="mp_options_panel" method="post" action="options.php">
        <h2><?php _e("Password Type", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <h2><?php _e("User Phone", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <p>
                <label for="mp_auto_confirm_phone_after_register"><?php _e('Auto confirm phone number after user register', 'mihanpanel'); ?></label>
                <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            </p>
            <p>
                <label for="mp_can_change_phone"><?php _e('User can change phone number', 'mihanpanel'); ?></label>
                <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            </p>
        </div>
        <h2><?php _e("SMS Provider Settings", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <label><?php _e("Select Provider", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            <p class="description"><?php _e("If your sms service provider not exists in this list, ask them to provide you with the relevant plugin.", "mihanpanel"); ?><a href="https://mihanwp.com/docs/mihanpanel-add-sms-provider/" target="_blank"><?php _e("According to this documents", "mihanpanel"); ?></a></p>
        </div>
        <div class="mw_sms_provider_settings">
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        </div>
        <div class="mp_option_single">
            <label><?php _e("Mobile validation code sms content", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            <p><?php _e("You can use this:", "mihanpanel"); ?></p>
            <p>
                <span><?php _e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                <span><?php _e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                <span><?php _e("Activation Code", "mihanpanel"); ?>: </span><span>[[code]]</span>
            </p>
        </div>

        <div class="mp_option_single">
            <label><?php _e("Dynamic login code sms content", "mihanpanel"); ?></label>
            <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
            <p><?php _e("You can use this:", "mihanpanel"); ?></p>
            <p>
                <span><?php _e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                <span><?php _e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                <span><?php _e("Activation Code", "mihanpanel"); ?>: </span><span>[[code]]</span>
            </p>
        </div>
        <?php submit_button(); ?>
    </form>
</div>