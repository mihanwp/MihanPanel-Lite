<div>
<form id="mwpl-option-form" class="mp_options_panel" method="post" action="options.php">
    <h2><?php esc_html_e("Notifications", "mihanpanel"); ?></h2>
    <?php
    settings_fields( 'mihanpanelsettings-notification' );
    ?>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 1', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message" name="mp_dashboard_message"><?php echo esc_textarea(get_option('mp_dashboard_message')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 2', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message2" name="mp_dashboard_message2"><?php echo esc_textarea(get_option('mp_dashboard_message2')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 3', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message3" name="mp_dashboard_message3"><?php echo esc_textarea(get_option('mp_dashboard_message3')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 4', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message4" name="mp_dashboard_message4"><?php echo esc_textarea(get_option('mp_dashboard_message4')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 5', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message5" name="mp_dashboard_message5"><?php echo esc_textarea(get_option('mp_dashboard_message5')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 6', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message6" name="mp_dashboard_message6"><?php echo esc_textarea(get_option('mp_dashboard_message6')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 7', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message7" name="mp_dashboard_message7"><?php echo esc_textarea(get_option('mp_dashboard_message7')); ?></textarea>
        </div>
    </div>
    <div class="mp_option_section">
        <h3 class="mp_option_section_title"><?php _e('Notification 8', 'mihanpanel')?></h3>
        <p class="mp_option_section_description"><?php esc_html_e("Create a notification to show to your users in their dashboard.", "mihanpanel"); ?></p>
        <div class="mp_option_field mp_row_field mp_fit_label">
            <label><?php esc_html_e("Notification text", "mihanpanel"); ?></label>
            <textarea id="mp_dashboard_message8" name="mp_dashboard_message8"><?php echo esc_textarea(get_option('mp_dashboard_message8')); ?></textarea>
        </div>
    </div>
</form>
</div>
