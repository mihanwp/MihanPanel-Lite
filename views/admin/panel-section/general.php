<div>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings' );
        do_settings_sections( 'mihanpanelsettings' );
        ?>
        <div class="mwpl_live_view_wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
            <div class="mwpl_live_view" id="mwpl_live_view_login_button">
                <div class="mwpl_login_form_wrapper">
                    <div class="login-btn"><span><?php esc_html_e('Login', 'mihanpanel')?></span></div>
                </div>
            </div>
            <div class="mwpl_live_view light" id="mwpl_live_view_login_logo">
                <div class="mwpl_login_form_wrapper">
                    <div class="logo-box"><?php esc_html_e('Logo', 'mihanpanel')?></div>
                </div>
            </div>
            <?php do_action('mwpl_live_view_wrapper_content')?>
        </div>
        <div class="mp_option_section">
            <?php
            $prefix = get_option('permalink_structure') ? trailingslashit(home_url()) : trailingslashit(home_url()) . '?';
            $newLoginSlug = \mihanpanel\app\options::get_login_slug();
			$login_url = $prefix . $newLoginSlug;
            ?>
            <h2><?php _e("Change login url", "mihanpanel"); ?></h2>
            <div class="mp_option_single">
                <label for="rwl-page"><?php esc_html_e('Login page address', 'mihanpanel')?></label>
                <div class="ltr">
                    <code><?php echo esc_html($prefix)?></code><input type="text" id="rwl-page-input" name="rwl_page" type="text" value="<?php echo esc_attr($newLoginSlug)?>"><code>/</code>
                </div>
                <p class="description"><?php _e('Your site login url is', 'mihanpanel')?>
                    <a target="_blank" dir="ltr" href="<?php echo esc_url($login_url)?>"><?php echo esc_url($login_url)?></a>
                </p>
            </div>
        </div>
        <div class="mp_option_single">
            <label for="mp_login_form_top_message_text"><?php esc_html_e('Login form message', 'mihanpanel')?></label>
            <p class="description"><?php esc_html_e('Text that shows in top of login form', 'mihanpanel')?></p>
            <?php \mihanpanel\app\handle_view::option_panel_field_login_top_message(); ?>
        </div>
        <div class="mp_option_section">
            <h2><?php _e("Woocommerce", "mihanpanel"); ?></h2>
            <div class="mp_option_single">
                <p>
                    <label for="mp_wc_redirect_from_checkout_is_not_logged_in"><?php esc_html_e('Redirect checkout page to login page', 'mihanpanel')?></label>
                    <?php \mihanpanel\app\handle_view::option_panel_field_is_redirect_wc_checkout_to_login(); ?>
                </p>
                <p class="description"><?php _e('If user is not logged-in', 'mihanpanel')?></p>
            </div>
        </div>
        <div class="mp_option_section">
            <h2><?php _e("Activation process", "mihanpanel"); ?></h2>
            <div class="mp_option_single">
                <?php
                $activation_types = \mihanpanel\app\options::get_activation_types();
                $current_activation_type = \mihanpanel\app\options::get_account_activation_type();
                foreach ($activation_types as $type_key => $type_name): ?>
                <p>
                    <label for="mp_activation_type_<?php echo esc_attr($type_key); ?>"><?php echo esc_html($type_name); ?></label>
                    <input <?php checked($current_activation_type, $type_key); ?> type="radio" name="mp_account_activation_type" value="<?php echo esc_attr($type_key); ?>" id="mp_activation_type_<?php echo esc_attr($type_key)?>" class="radio-affected-toggle-elements" data-option-targets=".mp-account-activation-type-sub-options" data-elements=".mp-account-activation-type-sub-<?php echo esc_attr($type_key) ?>">
                </p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mp_option_section mp-account-activation-type-sub-options mp-account-activation-type-sub-link" style="display:<?php echo \mihanpanel\app\options::is_send_activation_link_active() ? 'block' : 'none' ?>">
            <h2><?php _e("Activation Link", "mihanpanel"); ?></h2>
            <div class="mp_option_single">
                <p>
                    <label for="mp_resend_activation_email_link"><?php esc_html_e("Resend account activation email", "mihanpanel"); ?></label>
                    <input <?php echo \mihanpanel\app\options::is_active_resend_account_activation_email() ? 'checked' : ''; ?> type="checkbox" name="mp_resend_activation_email_link" value="1" id="mp_resend_activation_email_link">
                </p>
            </div>
        </div>
        <div class="mp_options_section">
            <h2><?php esc_html_e('Login & Register', 'mihanpanel')?></h2>
            <div class="mp_option_single">
                <label><?php esc_html_e("Login Form Theme", "mihanpanel");?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_login_form_theme(); ?>
                <p class="description"><?php esc_html_e("Login and Register form theme", "mihanpanel"); ?></p>
            </div>
            <div class="mp_option_single">
                <label><?php esc_html_e( "Form Position", "mihanpanel" )?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_login_form_position()?>
            </div>
            <div live_view_target_id="mwpl_live_view_login_button" class="mp_option_single">
                <label><?php esc_html_e("Login Button Color", "mihanpanel");?></label>
                <input default_value="#5454ff" type="text" name="login_button_color" id='login_button_color' value="<?php echo esc_attr(\mihanpanel\app\options::get_login_button_bg_color()); ?>" class="my-color-field" />
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
            <div class="mp_option_single">
                <label><?php esc_html_e("Login and Register form Logo", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mp_logo_image" type="text" name="mp_logo_image" value="<?php echo esc_attr(get_option('mp_logo_image')); ?>" />
                <input id="mp_upload_image_button" type="button" class="button-primary" value="<?php esc_attr_e("Upload Image", "mihanpanel"); ?>" />
                <p class="description"><?php esc_html_e("We suggest to use 120 * 120 px for logo size.", "mihanpanel"); ?></p>
            </div>
            <div live_view_target_id="mwpl_live_view_login_logo" class="mp_option_single">
                <label><?php esc_html_e("Logo Width (px)", "mihanpanel"); ?> <small class="range_value"><?php esc_html_e(\mihanpanel\app\options::get_login_logo_width())?></small></label>
                <input min="50" max="250" name="mp_logo_width" value="<?php echo esc_attr(\mihanpanel\app\options::get_login_logo_width())?>" class="mwpl_range" type="range">
            </div>
            <div live_view_target_id="mwpl_live_view_login_logo" class="mp_option_single">
                <label><?php esc_html_e("Logo Height (px)", "mihanpanel"); ?> <small class="range_value"><?php esc_html_e(\mihanpanel\app\options::get_login_logo_height())?></small></label>
                <input class="mwpl_range" type="range" value="<?php echo esc_attr(\mihanpanel\app\options::get_login_logo_height())?>" name="mp_logo_height" min="50" max="150">
            </div>
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

            <div class="mp_option_single">
                <label><?php esc_html_e("Redirect non Admin user to", "mihanpanel"); ?></label>
                <?php \mihanpanel\app\handle_view::option_panel_field_redirect_normal_user(); ?>
                <p class="description"><?php esc_html_e("Users who don't have admin or author caps, redirect to this address after login (with http or https)", "mihanpanel"); ?></p>
            </div>
        </div>
        <?php submit_button(); ?>
    </form>
</div>