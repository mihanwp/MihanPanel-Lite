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
        // load assets
        add_action('wp_footer', function(){
            wp_enqueue_style('mw-profile-widget', MW_MIHANPANEL_URL . 'css/profile-widget.css', null, \mihanpanel\app\tools::get_plugin_version());
            \mihanpanel\app\assets::load_fontawesome_assets();
            \mihanpanel\app\assets::load_fonts_assets('profile');
            do_action('load_mihanpanel_profile_widget_assets');
        });
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
                        <?php
                        \mihanpanel\app\panel::render_default_tabs(false);
                        \mihanpanel\app\panel::render_tabs();
                        ?>
                        <li>
                            <a href="<?php echo wp_logout_url( home_url() ) ?>">
                                <i class="fas fa-sign-out-alt"></i>
                                <p><?php _e("Logout", "mihanpanel"); ?></p>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?php echo esc_url(wp_login_url(\mihanpanel\app\tools::get_current_page_url())) ?>">
                                <i class="fas fa-unlock-alt"></i>
                                <p><?php _e("Login", "mihanpanel"); ?></p>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(add_query_arg(['action' => 'register'], wp_login_url())); ?>">
                                <i class="fas fa-user-plus"></i>
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