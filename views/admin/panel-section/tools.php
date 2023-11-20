<?php \mihanpanel\app\admin_menu::handle_tools_menu_submission();?>
<?php if($notice = \mihanpanel\app\notice::once_get_notice()): ?>
    <p class="alert <?php echo esc_attr($notice['type'])?>"><?php echo esc_html($notice['msg'])?></p>
<?php endif; ?>
<div>
    <form class="mp_options_panel" method="post">
        <h2><?php esc_html_e("User panel page", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <label><?php esc_html_e("Create user panel page", "mihanpanel"); ?></label>
            <?php if(!\mihanpanel\app\tools::check_is_panel_page_exists()): ?>
                <input name="create_user_panel_page" type="submit" value="<?php esc_html_e('Create', 'mihanpanel')?>">
            <?php else: ?>
                <p class="description"><?php esc_html_e('User panel page already exists.', 'mihanpanel')?></p>
            <?php endif; ?>
        </div>
        <h2><?php esc_html_e("Create database tables", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <label><?php esc_html_e("Create MihanPanel default tables in database", "mihanpanel"); ?></label>
                <input name="create_mihanpanel_database_tables" type="submit" value="<?php esc_html_e('Create', 'mihanpanel')?>">
                <p class="description"><?php esc_html_e('Just use this when some tables were dropped from database.', 'mihanpanel')?></p>
        </div>
        <h2><?php esc_html_e("Phone numbers", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <div class="field-item">
                <label><?php _e('Transfer digits phone numbers to Mihan Panel', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_transfer_digits_phone_numbers()?>
            </div>
            <div class="field-item">
                <p class="description"><?php esc_html_e("If you're using Digits previously you can use this to transfer phone numbers to Mihan Panel.", 'mihanpanel')?></p>
            </div>
            <div class="field-item">
                <p class="description"><?php esc_html_e("Only the phone number of the users who do not have a phone number in the Mihan Panel will be transferred.", 'mihanpanel')?></p>
            </div>
            <div class="field-item">
                <p class="description danger"><?php esc_html_e("Before using this option, make a backup of your site; changes cannot be reversed!", 'mihanpanel')?></p>
            </div>
        </div>
        <h2><?php esc_html_e("User management", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <div class="field-item">
                <label><?php _e('Delete ghost users', 'mihanpanel')?></label>
                <?php \mihanpanel\app\handle_view::option_panel_delete_ghost_users()?>
            </div>
            <div class="field-item">
                <p class="description"><?php esc_html_e("Delete users who have not any comments, woocommerce orders and posts.", 'mihanpanel')?></p>
            </div>
            <div class="field-item">
                <p class="description danger"><?php esc_html_e("Before using this option, make a backup of your site; changes cannot be reversed!", 'mihanpanel')?></p>
            </div>
        </div>
        <h2><?php esc_html_e("Login page", "mihanpanel"); ?></h2>
        <div class="mp_option_single">
            <div class="field-item">
                <label for="mp_login_header_additional_code"><?php _e('Additional Code', 'mihanpanel');?></label>
                <?php \mihanpanel\app\handle_view::option_panel_login_header_additional_code()?>
            </div>
            <p class="description"><?php _e('This code is render in head tag in Login page', 'mihanpanel')?></p>
        </div>
    <?php submit_button(); ?>
    </form>
</div>
