<?php
namespace mihanpanel\app\widget;
class profile extends \WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'mpp_widget', __('User Profile - Mihanpanel', 'mihanpanel'),
            array('description' => __('Show User Profile', 'mihanpanel'),)
        );
    }

    function widget($args, $instance)
    {
        //widget showing codes
        ?>
        <div class="mihanpanel-profile-widget <?php echo is_rtl() ? 'mp_rtl_widget' : 'mp_ltr_widget' ; ?>">
            <div class="mpavatar">
                <?php
                global $current_user;
                wp_get_current_user();
                echo get_avatar($current_user->ID, 250);
                ?>
            </div>
            <div class="clear"></div>
            <div class="mp-nav-tabs-mpwrapper">
                <ul class="mp-nav-tabs">
                    <?php if (is_user_logged_in()) { ?>
                        <li>
                        <?php
                        $panel_link = \mihanpanel\app\options::get_panel_url();
                        $edit_profile_link = esc_url(add_query_arg(['tab' => 'edit-profile'], $panel_link));
                        ?>
                            <a class="mwtaba" href="<?php echo $panel_link; ?>">
                                <i class="fas fa-tachometer-alt"></i>
                                <p><?php _e("User Dashboard", "mihanpanel"); ?></p>
                            </a>
                        </li>
                        <li>
                            <a class="mwtaba" href="<?php echo $edit_profile_link; ?>">
                                <i class="far fa-user"></i>
                                <p><?php _e("Edit Profile", "mihanpanel"); ?></p>
                            </a>
                        </li>
                        <?php global $wpdb;
                        $tablename = $wpdb->prefix . 'mihanpaneltabs';
                        $menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
                        foreach ($menus as $menu):
                            $tab_link = $menu->link ? esc_url($menu->link) : esc_url(add_query_arg(['tab' => $menu->id]));
                        ?>
                            <li>
                                <a class="mwtaba" href="<?php echo $tab_link; ?>">
                                    <i class="<?php echo esc_attr($menu->icon); ?>"></i>
                                    <p><?php echo esc_html($menu->name); ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <a href="<?php echo wp_logout_url( home_url() ) ?>">
                                <i class="fas fa-sign-out-alt"></i>
                                <p><?php _e("Logout", "mihanpanel"); ?></p>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?php echo wp_login_url();?>?redirect_to='<?php echo \mihanpanel\app\tools::get_current_page_url(); ?>'">
                                <i class="fa fa-unlock-alt"></i>
                                <p><?php _e("Login", "mihanpanel"); ?></p>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo wp_login_url(); ?>?action=register">
                                <i class="fa fa-user-plus"></i>
                                <p><?php _e("Register", "mihanpanel"); ?></p>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }
}