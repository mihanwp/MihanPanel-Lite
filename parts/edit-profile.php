<div class="nocss main-panel mwtabb" id="profile">
  			<div class="mihanpanel-section-title"><?php _e('Edit User Profile', 'mihanpanel'); ?></div>
        <div class="mp-content mihanpcontent">
  <div class="mihanpanel-card-content">
    <?php
    $multiple_notice = \mwplite\app\mw_notice::once_get_multiple_notice();
    if($multiple_notice)
    {
        if(is_array($multiple_notice))
        {
            foreach($multiple_notice as $notice)
            {
                echo '<div class="alert alert-'. $notice['type'] . '">' . $notice['msg'] .'</div>';
            }
        }
    }
    $current_user_id = get_current_user_id();
    if (isset($_POST['submit']) || isset($_POST['mw_remove_user_avatar'])) {
        $form_data = [];
        if(isset($_FILES))
        {
            $form_data['files'] = $_FILES;
        }
        if(isset($_POST))
        {
            $form_data['posts'] = $_POST;
        }
        \mwplite\app\form\mw_profile::do($form_data);
    }
    $current_user = wp_get_current_user();
    ?>
    <form method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php _e("First Name", "mihanpanel"); ?></label>
                    <input name="general[first_name]" type="text" id="first_name"
                           value="<?php echo $current_user->first_name; ?>"
                           class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php _e("Last Name", "mihanpanel")?></label>
                    <input name="general[last_name]" type="text" id="last_name"
                           value="<?php echo $current_user->last_name; ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php _e("New password", "mihanpanel"); ?></label>
                    <input name="general[pass1]" type="password" id="pass1" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php _e("Password repeat", "mihanpanel"); ?></label>
                    <input name="general[pass2]" type="password" id="pass2" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php _e("Bio", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <textarea class="form-control" name="general[description]" id="description"
                                  rows="4"
                                  cols="50"><?php echo $current_user->description; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpanelfields';
            $fields = $wpdb->get_results("SELECT * FROM $tablename order by priority");
            foreach ($fields as $field) { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $field->label; ?><?php if ($field->required == 'yes') {
                                printf('(%1$s)', __("Required", "mihanpanel"));
                            } ?></label>
                        <div class="form-group label-floating">
                            <?php \mwplite\app\presenter\user_fields::render_field('edit-profile', $field, $current_user, ['classes' => 'form-control']); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (\mwplite\app\mw_tools::is_woocommerce_active()) : ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label id="email"><?php _e("Email Address", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <input name="wc[email]" type="text" id="email"
                               value="<?php echo \mwplite\app\adapter\mw_woo::get_email(); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_name"><?php _e("Company Name", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <input name="wc[company_name]" type="text" id="company_name"
                               value="<?php echo \mwplite\app\adapter\mw_woo::get_company_name(); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="address_1"><?php _e("Address 1", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <textarea name="wc[address_1]" type="text" id="address_1"
                               class="form-control"><?php echo \mwplite\app\adapter\mw_woo::get_address_1(); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="address_2"><?php _e("Address 2", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <textarea name="wc[address_2]" type="text" id="address_2"
                               class="form-control"><?php echo \mwplite\app\adapter\mw_woo::get_address_2(); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="city"><?php _e("City", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <input name="wc[city]" type="text" id="city"
                               value="<?php echo \mwplite\app\adapter\mw_woo::get_city(); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="zip_code"><?php _e('Zip Code', "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <input name="wc[zip_code]" type="text" id="zip_code"
                               value="<?php echo \mwplite\app\adapter\mw_woo::get_zip_code(); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone"><?php _e("Phone", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <input name="wc[phone]" type="text" id="phone"
                               value="<?php echo \mwplite\app\adapter\mw_woo::get_phone(); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="clear"></div>
        <button name="submit" type="submit" class="btn btn-primary pull-right"><?php _e("Save Changes", "mihanpanel"); ?></button>
        <div class="clearfix"></div>
    </form>
  </div>
    </div>
    </div>
