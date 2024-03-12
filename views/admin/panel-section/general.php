<div>
    <form id="mwpl-option-form" class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields('mihanpanelsettings');
        do_settings_sections('mihanpanelsettings');
        ?>
        <div class="mwpl_live_view_wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
            <div class="mwpl_live_view" id="mwpl_live_view_login_button">
                <div class="mwpl_login_form_wrapper">
                    <div class="login-btn"><span><?php esc_html_e('Login', 'mihanpanel') ?></span></div>
                </div>
            </div>
            <div class="mwpl_live_view light" id="mwpl_live_view_login_logo">
                <div class="mwpl_login_form_wrapper">
                    <div class="logo-box"><?php esc_html_e('Logo', 'mihanpanel') ?></div>
                </div>
            </div>
            <?php do_action('mwpl_live_view_wrapper_content') ?>
        </div>
        <div class="mp_option_section">
            <?php
            $prefix = get_option('permalink_structure') ? trailingslashit(home_url()) : trailingslashit(home_url()) . '?';
            $newLoginSlug = \mihanpanel\app\options::get_login_slug();
            $login_url = $prefix . $newLoginSlug;
            ?>
            <h2><?php _e("Change login url", "mihanpanel"); ?></h2>
            <div class="mp_option_field" id="login_url_section">
                <label for="rwl-page"><?php esc_html_e('Login page address', 'mihanpanel') ?></label>
                <div id="login-url-input-wrapper">
                    <span><?php echo esc_html($prefix) ?></span><input type="text" id="rwl-page-input" name="rwl_page" type="text" value="<?php echo esc_attr($newLoginSlug) ?>"><span>/</span>
                </div>
                <p class="description"><?php _e('Your site login url is', 'mihanpanel') ?>
                    <a target="_blank" dir="ltr" href="<?php echo esc_url($login_url) ?>"><?php echo esc_url($login_url) ?></a>
                </p>
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass();?>">
            <h2><?php esc_html_e('Login form message', 'mihanpanel') ?></h2>
            <div class="mp_option_field mp_row_field">
                <label for="mp_login_form_top_message_text"><?php esc_html_e('Text that shows in top of login form', 'mihanpanel') ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_login_top_message(); ?>
            </div>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h2><?php _e("Woocommerce", "mihanpanel"); ?></h2>
            <div class="mp_option_field">
                <p>
                    <label for="mp_wc_redirect_from_checkout_is_not_logged_in"><?php esc_html_e('Redirect checkout page to login page', 'mihanpanel') ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_redirect_wc_checkout_to_login(); ?>
                </p>
                <p class="description"><?php _e('If user is not logged-in', 'mihanpanel') ?></p>
            </div>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h2><?php _e("Menus", "mihanpanel"); ?></h2>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <div class="mp_option_single">
                <p>
                    <label for="mp_is_add_mihanpanel_item_to_wp_nav_menu"><?php esc_html_e('Add MihanPanel item in menu', 'mihanpanel') ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_add_mihanpanel_to_wp_nav_menu(); ?>
                </p>
                <p class="description"><?php _e('Add Login button ( if user not logged-in ) and Panel button ( if user is logged-in ) in menu', 'mihanpanel') ?></p>

                <p>
                    <label for="mp_mihanpanel_nav_menus_item_menu"><?php esc_html_e("Menus", "mihanpanel"); ?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_mihanpanel_wp_nav_menu(); ?>
                </p>
                <p class="description"><span><?php esc_html_e("Choose menu location that you want to add MihanPanel item to it.", "mihanpanel") ?></span></p>
            </div>
        </div>
        <div class="mp_option_section">
            <h2><?php _e("Activation process", "mihanpanel"); ?></h2>
            <div class="mp_option_field mp_row_field" style="gap: 55px">
                <?php
                $activation_types = \mihanpanel\app\options::get_activation_types();
                $current_activation_type = \mihanpanel\app\options::get_account_activation_type();
                foreach ($activation_types as $type_key => $type_name) : ?>
                    <p class="mwpl_solid_checkbox">
                        <label for="mp_activation_type_<?php echo esc_attr($type_key); ?>"><?php echo esc_html($type_name); ?></label>
                        <input <?php checked($current_activation_type, $type_key); ?> type="radio" name="mp_account_activation_type" value="<?php echo esc_attr($type_key); ?>" id="mp_activation_type_<?php echo esc_attr($type_key) ?>" class="radio-affected-toggle-elements" data-option-targets=".mp-account-activation-type-sub-options" data-elements=".mp-account-activation-type-sub-<?php echo esc_attr($type_key) ?>">
                    </p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mp_option_section mp-account-activation-type-sub-options mp-account-activation-type-sub-link" style="display:<?php echo \mihanpanel\app\options::is_send_activation_link_active() ? 'block' : 'none' ?>">
            <h2><?php _e("Activation Link", "mihanpanel"); ?></h2>
            <div class="mp_option_field">
                <p>
                    <label for="mp_resend_activation_email_link"><?php esc_html_e("Resend account activation email", "mihanpanel"); ?></label>
                    <input <?php echo \mihanpanel\app\options::is_active_resend_account_activation_email() ? 'checked' : ''; ?> type="checkbox" name="mp_resend_activation_email_link" value="1" id="mp_resend_activation_email_link">
                </p>
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e('Login & Register', 'mihanpanel') ?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php esc_html_e("Login and Register form theme", "mihanpanel"); ?></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Login Form Theme", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_login_form_theme(); ?>
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php esc_html_e('Form Position', 'mihanpanel') ?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php _e('Choose Login & Register form position', 'mihanpanel')?></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Form Position", "mihanpanel") ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_login_form_position() ?>
            </div>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php esc_html_e('Color Options', 'mihanpanel') ?></h3>

            <div live_view_target_id="mwpl_live_view_login_button" class="mp_option_field">
                <label><?php esc_html_e("Login Button Color", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\presenter\admin_fields::renderColorPickerField(\mihanpanel\app\options::get_login_button_bg_color(), 'login_button_color', 'login_button_color')?>
                <p class="description"><?php esc_html_e("Color of Login and Register button", "mihanpanel"); ?></p>
            </div>
            <?php
            \mihanpanel\app\handle_view::option_panel_field_login_button_text_color();
            \mihanpanel\app\handle_view::option_panel_field_register_button_bg_color();
            \mihanpanel\app\handle_view::option_panel_field_register_button_text_color();
            \mihanpanel\app\handle_view::option_panel_field_login_labels_color();
            \mihanpanel\app\handle_view::option_panel_field_login_fields_bg_color();
            \mihanpanel\app\handle_view::option_panel_field_login_fields_text_color();
            \mihanpanel\app\handle_view::option_panel_field_login_info_messagebox_text_color();
            \mihanpanel\app\handle_view::option_panel_field_login_info_messagebox_bg_color();
            ?>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Upload Login & Register form logo', 'mihanpanel')?></h3>
            <p class="mp_option_section_description"><?php _e("Upload the login and registration form logo file", "mihanpanel"); ?></p>
            <div class="mp_upload_field">
                <input id="mp_logo_image" type="hidden" name="mp_logo_image" value="<?php echo esc_attr(get_option('mp_logo_image')); ?>" />

                <img src="<?php echo \mihanpanel\app\assets::get_image_url('cloud-icon', 'svg')?>" alt="cloud-icon">
                <span class="mp_upload_field_texts">
                    <span class="mp_upload_field_title"><?php _e('Click here and choose your logo file', 'mihanpanel')?></span>
                    <span class="mp_upload_field_description"><?php _e('We suggest to use 120 * 120 px for logo size.', 'mihanpanel')?></span>
                </span>
                <input id="mp_upload_image_button" type="button" class="button-primary" value="<?php esc_attr_e("Upload Image", "mihanpanel"); ?>" />
            </div>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Logo Width', 'mihanpanel')?></h3>

            <div live_view_target_id="mwpl_live_view_login_logo" class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Logo Width (px)", "mihanpanel"); ?> <small class="range_value"><?php esc_html_e(\mihanpanel\app\options::get_login_logo_width()) ?></small></label>
                <input min="50" max="250" name="mp_logo_width" value="<?php echo esc_attr(\mihanpanel\app\options::get_login_logo_width()) ?>" class="mwpl_range" type="range">
            </div>
        </div>
        <div class="mp_option_section">
            <h3 class="mp_option_section_title"><?php _e('Background options', 'mihanpanel')?></h3>
            <div class="mp-sub-tab-wrap">
                <ul class="mp-sub-tabs">
                    <li>
                        <a href="#" class="mp-sub-tab-item active" data-target="#mp-tab-content-body-bg"><?php echo __('Login page Background', 'mihanpanel') ?></a>
                    </li>
                    <li>
                        <a href="#" class="mp-sub-tab-item" data-target="#mp-tab-content-form-bg"><?php echo __('Login form Background', 'mihanpanel') ?></a>
                    </li>
                </ul>
                <div class="mp-sub-tabs-content">
                    <div class="mp-sub-tab-content" id="mp-tab-content-body-bg">
                        <?php \mihanpanel\app\handle_view::option_panel_login_body_background_fields(); ?>
                    </div>
                    <div class="mp-sub-tab-content" id="mp-tab-content-form-bg" style="display:none">
                        <?php \mihanpanel\app\handle_view::option_panel_login_form_background_fields(); ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('Redirect non admin users', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php printf('%s. ( %s )', __("Users who don't have admin or author caps, redirect to this address after login", "mihanpanel"), __('With http or https', 'mihanpanel')); ?></p>
            <div class="mp_option_field mp_row_field">
                <label><?php esc_html_e("Redirect non Admin user to", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_redirect_normal_user(); ?>
            </div>
        </div>
        <div class="mp_option_section <?php \mihanpanel\app\tools::handleOptionPanelSectionVersionStateClass()?>">
            <h3 class="mp_option_section_title"><?php _e('Location after logout', 'mihanpanel')?></h3>
            <?php \mihanpanel\app\tools::handleProVersionBoxInOptionPanel();?>
            <p class="mp_option_section_description"><?php printf('%s. ( %s )', __("Users after logout redirect to this url", "mihanpanel"), __('With http or https', 'mihanpanel')); ?></p>
            <div class="mp_option_field mp_row_field">
                <label for="mp_after_logout_redirect_url"><?php esc_html_e("Redirect after logout to", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_redirect_url_after_logout(); ?>
            </div>
        </div>
    </form>
</div>