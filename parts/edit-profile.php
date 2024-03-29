<div class="nocss main-panel mwtabb" id="profile">
  			<div class="mihanpanel-section-title"><?php esc_html_e('Edit User Profile', 'mihanpanel'); ?></div>
        <div class="mp-content mihanpcontent">
  <div class="mihanpanel-card-content">
    <?php
    $multiple_notice = \mihanpanel\app\notice::once_get_multiple_notice();
    if($multiple_notice)
    {
        if(is_array($multiple_notice))
        {
            foreach($multiple_notice as $notice)
            {
                echo '<div class="alert alert-'. esc_attr($notice['type']) . '">' . esc_html($notice['msg']) .'</div>';
            }
        }
    }
    $current_user_id = get_current_user_id();
    if (!empty($_POST)) {
        \mihanpanel\app\form\profile::do();
    }
    $current_user = wp_get_current_user();
    $profile_form_enctype = apply_filters('mwpl_panel/profile/form_enctype', 'application/x-www-form-urlencoded');
    ?>
    <form method="post" enctype="<?php echo esc_attr($profile_form_enctype); ?>">
        <?php
            wp_nonce_field('mwpl_update_user_profile_panel', 'mwpl_nonce');
            do_action('mwpl_panel/profile/before_render_fields');
        ?>
        <div class="row">
            <?php
            \mihanpanel\app\user_fields::renderFirstNameField($current_user);
            \mihanpanel\app\user_fields::renderLastNameField($current_user);
            ?>
        </div>
        <?php
        \mihanpanel\app\user_fields::renderPasswordField();
        do_action('mwpl_panel/profile/after_password_field'); ?>
        <?php \mihanpanel\app\user_fields::renderBioField($current_user); ?>
        <div class="row">
            <?php
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpanelfields';
            $fields = $wpdb->get_results("SELECT * FROM $tablename order by priority");
            foreach ($fields as $field):
                if(!apply_filters('mwpl_user_fields_render_permission', true, $field, 'profile'))
                {
                    continue;
                }
                $meta = unserialize($field->meta);
                $field_icon = isset($meta['data']) && isset($meta['data']['field_icon']) ? $meta['data']['field_icon'] : null;
            ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <?php if (!empty($field_icon)): ?>
                                <?php if (filter_var($field_icon, FILTER_VALIDATE_URL)): ?>
                                    <img src="<?php echo esc_attr($field_icon) ?>" alt="field icon" class="field-icon mw_icon">
                                <?php else: ?>
                                    <i class="field-icon mw_icon <?php echo esc_attr($field_icon) ?>"></i>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php echo apply_filters('mihanpanel/edit_profile/fields_label_text', esc_html($field->label), $field->slug); ?>
                            <?php
                            if ($field->required == 'yes') {
                                printf('(%1$s)', esc_html__("Required", "mihanpanel"));
                            }
                            ?>
                        </label>
                        <div class="form-group label-floating">
                            <?php \mihanpanel\app\presenter\user_fields::render_field('edit-profile', $field, $current_user, ['classes' => 'form-control']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php \mihanpanel\app\presenter\user_fields::render_woocommerce_fields('edit-profile'); ?>
        <div class="clear"></div>
        <button name="submit" type="submit" class="btn btn-primary pull-right"><?php esc_html_e("Save Changes", "mihanpanel"); ?></button>
        <div class="clearfix"></div>
    </form>
  </div>
    </div>
    </div>
