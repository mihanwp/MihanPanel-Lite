<div>
<h2><?php _e("Notifications", "mihanpanel"); ?></h2>
<form class="mp_options_panel" method="post" action="options.php">
    <?php
    settings_fields( 'mihanpanelsettings-notification' );
    ?>
    <div>
        <div class="mp_option_single">

            <label><?php _e("Notification 1", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message" name="mp_dashboard_message"><?php echo esc_textarea(get_option('mp_dashboard_message')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 2", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message2" name="mp_dashboard_message2"><?php echo esc_textarea(get_option('mp_dashboard_message2')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 3", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message3" name="mp_dashboard_message3"><?php echo esc_textarea(get_option('mp_dashboard_message3')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 4", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message4" name="mp_dashboard_message4"><?php echo esc_textarea(get_option('mp_dashboard_message4')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 5", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message5" name="mp_dashboard_message5"><?php echo esc_textarea(get_option('mp_dashboard_message5')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 6", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message6" name="mp_dashboard_message6"><?php echo esc_textarea(get_option('mp_dashboard_message6')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 7", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message7" name="mp_dashboard_message7"><?php echo esc_textarea(get_option('mp_dashboard_message7')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>

        <div class="mp_option_single">

            <label><?php _e("Notification 8", "mihanpanel"); ?></label>

            <textarea id="mp_dashboard_message8" name="mp_dashboard_message8"><?php echo esc_textarea(get_option('mp_dashboard_message8')); ?></textarea>

            <p class="description"><?php _e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>

        </div>
    </div>
    <?php submit_button(); ?>
</form>
</div>
