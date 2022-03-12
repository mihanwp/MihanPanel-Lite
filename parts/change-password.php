<div class="nocss main-panel mwtabb" id="change-password">
  			<div class="mihanpanel-section-title"><?php esc_html_e('Change password', 'mihanpanel'); ?></div>
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
    if (!empty($_POST)) {
        $form_data = [];
        if(isset($_POST))
        {
            $form_data['posts'] = $_POST;
        }
        \mihanpanel\app\form\profile::handleChangePasswordForm($form_data);
    }
    $current_user = wp_get_current_user();
    ?>
    <form method="post">
        <?php wp_nonce_field('mwpl_change_password', 'mwpl_nonce');?>
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
        <div class="clear"></div>
        <button name="submit" type="submit" class="btn btn-primary pull-right"><?php esc_html_e("Save Changes", "mihanpanel"); ?></button>
        <div class="clearfix"></div>
    </form>
  </div>
    </div>
    </div>
