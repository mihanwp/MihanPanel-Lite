<div class="main-panel mwtabb" id="profile">
  <?php
  global $wpdb;
  $tablename = $wpdb->prefix . 'mihanpaneltabs';
  $sql = "SELECT * From {$tablename} where id=%d";
  $menu = $wpdb->get_row($wpdb->prepare($sql, $tab_id));
  if($menu):
  ?>
  <div class="mihanpanel-section-title"><?php echo esc_html($menu->name);?></div>
  <div class="mp-content mihanpcontent">
    <div class="mihanpanel-card-content">
      <?php
      $shortcodetorun = stripslashes($menu->content);
      echo do_shortcode( $shortcodetorun );?>
    </div>
  </div>
  <?php endif; ?>
</div>