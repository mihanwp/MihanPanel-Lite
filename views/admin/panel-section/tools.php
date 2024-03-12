<?php \mihanpanel\app\admin_menu::handle_tools_menu_submission();?>
<?php if($notice = \mihanpanel\app\notice::once_get_notice()): ?>
    <p class="alert <?php echo esc_attr($notice['type'])?>"><?php echo esc_html($notice['msg'])?></p>
<?php endif; ?>
<div>
    <form id="mwpl-option-form" class="mp_options_panel" method="post">
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php esc_html_e("User panel page", "mihanpanel"); ?></h3>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Create user panel page", "mihanpanel"); ?></label>
                <?php if(!\mihanpanel\app\tools::check_is_panel_page_exists()): ?>
                    <input name="create_user_panel_page" type="submit" value="<?php esc_html_e('Create', 'mihanpanel')?>">
                <?php else: ?>
                    <p class="description"><?php esc_html_e('User panel page already exists.', 'mihanpanel')?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php esc_html_e("Create database tables", "mihanpanel"); ?></h3>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Create MihanPanel default tables in database", "mihanpanel"); ?></label>
                <input name="create_mihanpanel_database_tables" type="submit" value="<?php esc_html_e('Create', 'mihanpanel')?>">
            </div>
            <p class="mp_option_section_description"><?php esc_html_e('Just use this when some tables were dropped from database.', 'mihanpanel')?></p>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("Phone numbers", "mihanpanel"); ?></h3>
            <div class="mp_option_field">
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
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("User management", "mihanpanel"); ?></h3>
            <div class="mp_option_field">
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
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <h3 class="mp_option_section_title"><?php esc_html_e("Login page", "mihanpanel"); ?></h3>
            <p class="mp_option_section_description"><?php _e('This code is render in head tag in Login page', 'mihanpanel')?></p>
            <div class="mp_option_field">
                <label for="mp_login_header_additional_code"><?php _e('Additional Code', 'mihanpanel');?></label>
                <?php \mihanpanel\app\handle_view::option_panel_login_header_additional_code()?>
            </div>
        </div>
    </form>
</div>
