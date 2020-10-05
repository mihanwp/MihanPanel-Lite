<div>
    <form class="mp_options_panel" method="post" action="options.php">
        <?php
        settings_fields( 'mihanpanelsettings' );
        do_settings_sections( 'mihanpanelsettings' );
        ?>
        <div class="mp_option_section">
            <h2><?php _e("User activation process", "mihanpanel"); ?></h2>
            <div class="mp_option_single">
                <?php
                $activation_types = \mwplite\app\mwpl_options::get_activation_types();
                $current_activation_type = \mwplite\app\mwpl_options::get_account_activation_type();
                foreach ($activation_types as $type_key => $type_name): ?>
                <p>
                    <label for="mp_activation_type_<?php echo esc_attr($type_key); ?>"><?php echo esc_html($type_name); ?></label>
                    <input <?php checked($current_activation_type, $type_key); ?> type="radio" name="mp_account_activation_type" value="<?php echo esc_attr($type_key); ?>" id="mp_activation_type_<?php echo esc_attr($type_key)?>">
                </p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mp_options_section">
            <h2><?php _e('Login & Register', 'mihanpanel')?></h2>
            <div class="mp_option_single">
                <label><?php _e("Login Form Theme", "mihanpanel");?></label>
                <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
                <p class="description"><?php _e("Login and Register form theme", "mihanpanel"); ?></p>
            </div>
            <div class="mp_option_single">
                <label><?php _e("Login Button Color", "mihanpanel");?></label>
                <input type="text" name="login_button_color" id='login_button_color' value="<?php echo esc_attr(get_option('login_button_color')); ?>" class="my-color-field" />
                <p class="description"><?php _e("Color of Login and Register button", "mihanpanel"); ?></p>
            </div>
            <div class="mp_option_single">
                <label><?php _e("Login and Register form Logo", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mp_logo_image" type="text" name="mp_logo_image" value="<?php echo esc_attr(get_option('mp_logo_image')); ?>" />
                <input id="mp_upload_image_button" type="button" class="button-primary" value="<?php esc_attr_e("Upload Image", "mihanpanel"); ?>" />
                <p class="description"><?php _e("We suggest to use 120 * 120 px for logo size.", "mihanpanel"); ?></p>
            </div>
            <div class="mp_option_single">
                <label><?php _e("Logo Width (px)", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" type="number" name="mp_logo_width" value="<?php echo esc_attr( get_option('mp_logo_width') ); ?>" />
            </div>
            <div class="mp_option_single">
                <label><?php _e("Logo Height (px)", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" type="number" name="mp_logo_height" value="<?php echo esc_attr( get_option('mp_logo_height') ); ?>" />
            </div>
            <div class="mp_option_single">
                <label><?php _e("Login and Register form background", "mihanpanel"); ?></label>
                <input style="text-align:left;direction:ltr" id="mp_bg_image" type="text" name="mp_bg_image" value="<?php echo esc_attr(get_option('mp_bg_image')); ?>" />
                <input id="mp_upload_bg_image_button" type="button" class="button-primary" value="<?php esc_attr_e("Upload Image", "mihanpanel"); ?>" />
            </div>
            <div class="mp_option_single">
                <label><?php _e("Redirect non Admin user to", "mihanpanel"); ?></label>
                <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
                <p class="description"><?php _e("Users who don't have admin or author caps, redirect to this address after login (with http or https)", "mihanpanel"); ?></p>
            </div>
        </div>
        <?php submit_button(); ?>
    </form>
</div>