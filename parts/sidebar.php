<?php
if (isset($_GET['tab']))
{
    $tab_id = sanitize_text_field($_GET['tab']);
}
else
{
    $tab_id = null;
}
?>
<div class="mp-nav-tabs-mpwrapper nocss">
  <ul class="nav mp-nav-tabs">
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
<?php global $wpdb;
$tablename = $wpdb->prefix . 'mihanpaneltabs';
$menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
foreach ($menus as $menu)
{ ?>
  <li <?php echo $tab_id == $menu->id ? 'class="active"' : false; ?>>
      <a class="mwtaba" <?php if ($menu->link == null): ?>href="<?php echo esc_url(add_query_arg(['tab' => $menu->id], remove_query_arg(['order_id', 'order_details']))); ?>"<?php
    else: ?>href="<?php echo esc_url($menu->link); ?>" target="_blank" <?php
    endif; ?>>
          <i class="<?php echo esc_attr($menu->icon); ?>"></i>
          <p><?php echo esc_html($menu->name); ?></p>
      </a>
  </li>
<?php
} ?>
      <li>
          <a href="<?php echo wp_logout_url(home_url()) ?>">
            <i class="fas fa-sign-out-alt"></i>
              <p><?php esc_html_e("Logout", "mihanpanel"); ?></p>
          </a>
      </li>
  </ul>
  <div class="clear"></div>
  </div>
