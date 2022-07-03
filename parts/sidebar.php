<div class="mp-nav-tabs-mpwrapper nocss">
  <ul class="nav mp-nav-tabs default-menu">
    <li <?php echo $tab_id == null ? 'class="active"' : false;?> >
      <a class="mwtaba" href="<?php echo \mihanpanel\app\options::get_panel_url(); ?>">
        <i class="fas fa-tachometer-alt"></i>
        <p><?php esc_html_e('User Dashboard', 'mihanpanel'); ?></p>
      </a>
    </li>
    <li <?php echo $tab_id == 'edit-profile' ? 'class="active"' : false;?>>
      <a class="mwtaba" href="<?php echo esc_url(add_query_arg(['tab' => 'edit-profile'], remove_query_arg(['order_id', 'order_details']))) ?>">
              <i class="far fa-user"></i>
              <p><?php esc_html_e("Edit Profile", "mihanpanel"); ?></p>
          </a>
      </li>
  </ul>
    <?php
    \mihanpanel\app\panel::render_tabs();
    ?>
    <ul class="nav mp-nav-tabs logout-menu">
      <li>
          <a href="<?php echo wp_logout_url(home_url()) ?>">
            <i class="fas fa-sign-out-alt"></i>
              <p><?php esc_html_e("Logout", "mihanpanel"); ?></p>
          </a>
      </li>
  </ul>
  <div class="clear"></div>
  </div>
