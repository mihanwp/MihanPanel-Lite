<div class="nocss main-panel mwtabb" id="dashboard">
    <a class="back-to-sitehome"
       href="<?php echo get_bloginfo('url'); ?>"><span><?php _e("Back to Home Page", "mihanpanel") ?></span> <i
                class="fas fa-angle-double-left"></i></a>
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container-fluid">
            <div class="navbar-header">
                <?php $current_user = wp_get_current_user(); ?>
                <div class="navbar-brand"><?php printf(__('Howdy, %1$s!', 'mihanpanel'), $current_user->display_name); ?></div>
            </div>
        </div>
    </nav>
    <div class="mp-content">
        <div class="container-fluid">
            <div class="row">
                    <div class="col-md-4">
                        <div class="mihanpanel-card mihanpanel-card-stats">
                            <div class="mihanpanel-card-header" data-background-color="blue">
                                <i class="fas fa-3x fa-trophy"></i>
                            </div>
                            <div class="mihanpanel-card-content">
                                <p class="category"><?php _e("You are our user", "mihanpanel") ?></p>
                                <h3 class="title"><?php
                                    $today_obj      = new DateTime( date( 'Y-m-d', strtotime( 'today' ) ) );
                                    $register_date  = get_the_author_meta( 'user_registered', get_current_user_id() );
                                    $registered_obj = new DateTime( date( 'Y-m-d', strtotime( $register_date ) ) );
                                    $interval_obj   = $today_obj->diff( $registered_obj );
                                    $day = '';
                                    if( $interval_obj->days > 0 ) {
                                        $day = $interval_obj->days;
                                    } elseif( 0 == $interval_obj->days ) {
                                        $day = 1;
                                    }
                                    printf(__('%d Day', 'mihanpanel'), $day);
                                    ?></h3>
                            </div>
                        </div>
                    </div>
                   
                <div class="col-md-4">
                    <div class="mihanpanel-card mihanpanel-card-stats">
                        <div class="mihanpanel-card-header" data-background-color="orange">
                            <i class="far fa-3x fa-comment"></i>
                        </div>
                        <div class="mihanpanel-card-content">
                            <p class="category"><?php _e("Your Comments", "mihanpanel") ?></p>
                            <h3 class="title"><?php
                                global $wpdb, $post, $current_user;
                                wp_get_current_user();
                                $userId = $current_user->ID;
                                $where = 'WHERE comment_approved = 1 AND user_id = ' . $userId;
                                $comment_count = $wpdb->get_var("SELECT COUNT( * ) AS total
                                 FROM {$wpdb->comments}
                                 {$where}");
                                echo $comment_count;
                                ?></h3>
                        </div>
                    </div>
                </div>
                <?php if (class_exists('EDD_Customer')) : ?>
                    <div class="col-md-4">
                        <div class="mihanpanel-card mihanpanel-card-stats">
                            <div class="mihanpanel-card-header" data-background-color="blue">
                                <i class="far fa-3x fa-file"></i>
                            </div>
                            <div class="mihanpanel-card-content">
                                <p class="category"><?php _e("Purchased files", "mihanpanel"); ?></p>
                                <h3 class="title">
                                    <?php $mwpremail = get_current_user_id();
                                    $mwpr_purchased = edd_get_users_purchases($mwpremail);
                                    $counter = 0;
                                    if ($mwpr_purchased) {
                                        foreach ($mwpr_purchased as $val) {
                                            foreach ($val as $k => $v) {
                                                if ($k == 'ID') {
                                                    $mwpr_name = edd_get_payment_meta_cart_details($v);
                                                    foreach ($mwpr_name as $mwprt_name) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    echo $counter;
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (class_exists('WooCommerce')) : ?>
                    <div class="col-md-4">
                        <div class="mihanpanel-card mihanpanel-card-stats">
                            <div class="mihanpanel-card-header" data-background-color="green">
                                <i class="fas fa-3x fa-shopping-cart"></i>
                            </div>
                            <div class="mihanpanel-card-content">
                                <p class="category"><?php _e("Your purchase count", "mihanpanel"); ?></p>
                                <h3 class="title"><?php $user_id = get_current_user_id();
                                    echo wc_get_customer_order_count($user_id); ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (class_exists('Awesome_Support')): ?>
                    <div class="col-md-4">
                        <div class="mihanpanel-card mihanpanel-card-stats">
                            <div class="mihanpanel-card-header" data-background-color="red">
                                <i class="far fa-3x fa-life-ring"></i>
                            </div>
                            <div class="mihanpanel-card-content">
                                <p class="category"><?php _e("Your tickets", 'mihanpanel'); ?></p>
                                <h3 class="title">
                                    <?php
                                    $args = array(
                                        'author' => $current_user->ID,
                                        'post_type' => 'ticket'
                                    );
                                    $posts = new WP_Query($args);
                                    echo $posts->found_posts;
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_offer_code') != null): ?>
                    <div class="col-md-8">
                        <div class="mihanpanel-card mihanpanel-card-stats">
                            <div class="mihanpanel-card-header" data-background-color="purple">
                                <i class="fas fa-3x fa-gift"></i>
                            </div>
                            <div class="mihanpanel-card-content">
                                <?php $off_code_perc = get_option('mp_offer_perc'); ?>
                                <p class="category"><?php printf(__('%1$s off code for your next purchase', 'mihanpanel'), $off_code_perc) ?></p>
                                <?php $off_code = get_option('mp_offer_code'); ?>
                                <h3 class="title"
                                    style="text-align:center;line-height:40px;"><?php printf(__('Off code: %1$s', 'mihanpanel'), $off_code) ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="clear"></div>
                <?php if (get_option('mp_dashboard_message') != null): ?>
                    <div class="alert alert-primary" role="alert">
                        <?php echo get_option('mp_dashboard_message'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message2') != null): ?>
                    <div class="alert alert-primary2" role="alert">
                        <?php echo get_option('mp_dashboard_message2'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message3') != null): ?>
                    <div class="alert alert-primary3" role="alert">
                        <?php echo get_option('mp_dashboard_message3'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message4') != null): ?>
                    <div class="alert alert-primary4" role="alert">
                        <?php echo get_option('mp_dashboard_message4'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message5') != null): ?>
                    <div class="alert alert-primary5" role="alert">
                        <?php echo get_option('mp_dashboard_message5'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message6') != null): ?>
                    <div class="alert alert-primary6" role="alert">
                        <?php echo get_option('mp_dashboard_message6'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message7') != null): ?>
                    <div class="alert alert-primary7" role="alert">
                        <?php echo get_option('mp_dashboard_message7'); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message8') != null): ?>
                    <div class="alert alert-primary8" role="alert">
                        <?php echo get_option('mp_dashboard_message8'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
