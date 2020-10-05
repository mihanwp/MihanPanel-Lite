<?php
if (isset($_GET['tab']))
{
    $tab_id = $_GET['tab'];
}
else
{
    $tab_id = null;
}
?>
<div class="mp-nav-tabs-mpwrapper nocss">
  <ul class="nav mp-nav-tabs">
    <li <?php if ($tab_id == null): ?> class="active"<?php
endif; ?>>
      <a class="mwtaba" href="<?php echo esc_url(mihanpanelpanelurl); ?>">
<i class="fas fa-tachometer-alt"></i>
        <p><?php _e('User Dashboard', 'mihanpanel'); ?></p>
      </a>
    </li>
    <li <?php if ($tab_id == 'edit-profile'): ?> class="active"<?php
endif; ?>>
      <a class="mwtaba" href="<?php echo esc_url(add_query_arg(['tab' => 'edit-profile'], remove_query_arg(['order_id', 'order_details']))) ?>">
              <i class="far fa-user"></i>
              <p><?php _e("Edit Profile", "mihanpanel"); ?></p>
          </a>
      </li>
<?php global $wpdb;
$tablename = $wpdb->prefix . 'mihanpaneltabs';
$menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
foreach ($menus as $menu)
{ ?>
  <li <?php if ($tab_id == $menu->id): ?> class="active"<?php
    endif; ?>>
      <a class="mwtaba" <?php if ($menu->link == null): ?>href="<?php echo esc_url(add_query_arg(['tab' => $menu->id], remove_query_arg(['order_id', 'order_details']))); ?>"<?php
    else: ?>href="<?php echo esc_url($menu->link); ?>" target="_blank" <?php endif; ?>>
          <i class="<?php echo esc_attr($menu->icon); ?>"></i>
          <p><?php echo esc_html($menu->name); ?></p>
      </a>
  </li>
<?php
} ?>
      <li>
          <a href="<?php echo esc_url(wp_logout_url(home_url())) ?>">
            <i class="fas fa-sign-out-alt"></i>
              <p><?php _e("Logout", "mihanpanel"); ?></p>
          </a>
      </li>
  </ul>
  <div class="clear"></div>
  </div>
