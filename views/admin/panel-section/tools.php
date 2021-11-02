<?php \mihanpanel\app\admin_menu::handle_tools_menu_submission();?>
<div>
    <h2><?php esc_html_e("User panel page", "mihanpanel"); ?></h2>
    <form action="options.php" class="mp_options_panel" method="post">
        <?php
        settings_fields('mihanpanelsettings-tools');
        ?>
        <div class="mp_option_single">
            <label><?php esc_html_e("Create user panel page", "mihanpanel"); ?></label>
            <?php if(!\mihanpanel\app\tools::check_is_panel_page_exists()): ?>
                <input name="create_user_panel_page" type="submit" value="<?php esc_html_e('Create', 'mihanpanel')?>">
            <?php else: ?>
                <p class="description"><?php esc_html_e('User panel page already exists.', 'mihanpanel')?></p>
            <?php endif; ?>
        </div>
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
