<div class="mp-nav-tabs-mpwrapper nocss">
    <?php
    \mihanpanel\app\panel::render_default_tabs();
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
