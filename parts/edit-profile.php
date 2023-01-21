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
        $form_data = [];
        if(isset($_FILES))
        {
            $form_data['files'] = $_FILES;
        }
        if(isset($_POST))
        {
            $form_data['posts'] = $_POST;
        }
        \mihanpanel\app\form\profile::do($form_data);
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
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php esc_html_e("First Name", "mihanpanel"); ?></label>
                    <input name="general[first_name]" type="text" id="first_name"
                           value="<?php echo esc_attr($current_user->first_name); ?>"
                           class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php esc_html_e("Last Name", "mihanpanel")?></label>
                    <input name="general[last_name]" type="text" id="last_name"
                           value="<?php echo esc_attr($current_user->last_name); ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php esc_html_e("New password", "mihanpanel"); ?></label>
                    <input name="general[pass1]" type="password" id="pass1" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php esc_html_e("Password repeat", "mihanpanel"); ?></label>
                    <input name="general[pass2]" type="password" id="pass2" class="form-control">
                </div>
            </div>
        </div>
        <?php do_action('mwpl_panel/profile/after_password_field'); ?>
        <div class="row" id="bio-field-row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php esc_html_e("Bio", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <textarea class="form-control" name="general[description]" id="description"
                                  rows="4"
                                  cols="50"><?php echo esc_textarea($current_user->description); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
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
            ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo esc_html($field->label); ?><?php if ($field->required == 'yes') {
                                printf('(%1$s)', esc_html__("Required", "mihanpanel"));
                            } ?></label>
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
