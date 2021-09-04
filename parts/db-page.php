<div class="main-panel mwtabb" id="profile">
  <?php
  global $wpdb;
  $tablename = $wpdb->prefix . 'mihanpaneltabs';
  $sql = "SELECT * From {$tablename} where id=%d";
  $menu = $wpdb->get_row($wpdb->prepare($sql, $tab_id));
  if($menu):
    $menu_meta = $menu->meta;
    $menu_meta = $menu_meta ? unserialize($menu_meta) : false;
    $show_by_role = isset($menu_meta['show_by_roles']) ? $menu_meta['show_by_roles'] : false;
    $permission = true;
    if($show_by_role)
    {
      $permission = \mihanpanel\app\users::permision_by_role($show_by_role);
    }
    if($permission):
  ?>
  <div class="mihanpanel-section-title"><?php echo esc_html($menu->name);?></div>
  <div class="mp-content mihanpcontent">
    <div class="mihanpanel-card-content">
      <?php
      $shortcodetorun = stripslashes($menu->content);
      echo do_shortcode( $shortcodetorun );?>
    </div>
  </div>
  <?php else: ?>
    <div class="mihanpanel-section-title"><?php esc_html_e("No Access!", 'mihanpanel');?></div>
    <div class="mp-content mihanpcontent">
      <div class="mihanpanel-card-content">
        <?php esc_html_e("Sorry, you don't have access to this section", 'mihanpanel')?>
      </div>
    </div>
  <?php endif; ?>
  <?php endif; ?>
</div>