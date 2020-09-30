<div>

    <form class="mp_options_panel" method="post" action="options.php">
        <h2><?php _e("Welcome Email", "mihanpanel"); ?></h2>
        <span><?php _e("Text of email that send to new user", "mihanpanel"); ?></span>
        <?php
        settings_fields( 'mihanpanelsettings-email' );
        ?>
        <div>
            <div class="mp_option_single">

                <label><?php _e("Email Content", "mihanpanel"); ?></label>

                <div class="mw_dashicons mw_editor_wrapper"><?php
                wp_editor(\mwplite\app\mw_options::get_email_notify_content(),
                    'mp_new_user_email_notify',
                    [
                        'textarea_rows' => 10,
                    ]
                );
                ?></div>
                <p><?php _e("You can use this:", "mihanpanel"); ?></p>
                <p>
                    <span><?php _e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                    <span><?php _e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                </p>
            </div>

            <h2><?php _e("Reset Password Email", "mihanpanel"); ?></h2>
            <span><?php _e("Subject and Text of reset password email", "mihanpanel"); ?></span>

            <div class="mp_option_single">

                <label><?php _e("Reset password email subject", "mihanpanel"); ?></label>

                <input type="text" name="mp_reset_password_email_subject" id="mp_reset_password_email_subject" value="<?php echo \mwplite\app\mw_options::get_reset_password_email_subject(); ?>">
            </div>

            <div class="mp_option_single">

                <label><?php _e("Email Content", "mihanpanel"); ?></label>

                <div class="mw_dashicons mw_editor_wrapper">
                    <?php wp_editor(\mwplite\app\mw_options::get_reset_password_email_content(), 'mp_reset_password_email_content', ['textarea_rows' => 10]); ?>
                </div>
                <p><?php _e("You can use this:", "mihanpanel"); ?></p>
                <p>
                    <span><?php _e("Activation Link", "mihanpanel")?>: </span><span>[[link]]</span>
                    <span><?php _e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                    <span><?php _e("First Name", "mihanpanel"); ?>: </span><span>[[first_name]]</span>
                    <span><?php _e("Last Name", "mihanpanel"); ?>: </span><span>[[last_name]]</span>
                    <span><?php _e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                </p>
            </div>

            <h2><?php _e("Change Account Status Email", "mihanpanel"); ?></h2>
            <span><?php _e("Subject and Text of change account status email", "mihanpanel"); ?></span>

            <div class="mp_option_single">

                <label><?php _e("Change account status email subject", "mihanpanel"); ?></label>

                <input type="text" name="mp_change_account_status_email_subject" id="mp_change_account_status_email_subject" value="<?php echo \mwplite\app\mw_options::get_change_account_status_email_subject(); ?>">
            </div>

            <div class="mp_option_single">

                <label><?php _e("Email Content", "mihanpanel"); ?></label>

                <div class="mw_dashicons mw_editor_wrapper">
                    <?php wp_editor(\mwplite\app\mw_options::get_change_account_status_email_content(), 'mp_change_account_status_email_content', ['textarea_rows' => 10]); ?>
                </div>
                <p><?php _e("You can use this:", "mihanpanel"); ?></p>
                <p>
                    <span><?php _e("Account Status", "mihanpanel")?>: </span><span>[[status]]</span>
                    <span><?php _e("Username", "mihanpanel"); ?>: </span><span>[[user_login]]</span>
                    <span><?php _e("First Name", "mihanpanel"); ?>: </span><span>[[first_name]]</span>
                    <span><?php _e("Last Name", "mihanpanel"); ?>: </span><span>[[last_name]]</span>
                    <span><?php _e("Display Name", "mihanpanel"); ?>: </span><span>[[display_name]]</span>
                </p>
            </div>

        </div>
        <?php submit_button(); ?>
    </form>
</div>
