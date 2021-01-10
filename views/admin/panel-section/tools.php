<?php \mihanpanel\app\admin_menu::handle_tools_menu_submission();?>
<div>
    <h2><?php esc_html_e("User panel page", "mihanpanel"); ?></h2>
    <form class="mp_options_panel" method="post">
        <div class="mp_option_single">
            <label><?php esc_html_e("Create user panel page", "mihanpanel"); ?></label>
            <?php if(!\mihanpanel\app\tools::check_is_panel_page_exists()): ?>
                <input name="create_user_panel_page" type="submit" value="<?php esc_html_e('Create', 'mihanpanel')?>">
            <?php else: ?>
                <p class="description"><?php esc_html_e('User panel page already exists.', 'mihanpanel')?></p>
            <?php endif; ?>
        </div>
    </form>
</div>
