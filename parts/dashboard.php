<div class="nocss main-panel mwtabb" id="dashboard">
    <a class="back-to-sitehome"
       href="<?php echo esc_url(get_bloginfo('url')); ?>"><span><?php esc_html_e("Back to Home Page", "mihanpanel") ?></span> <i
                class="fas fa-angle-double-left"></i></a>
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container-fluid">
            <div class="navbar-header">
                <?php $current_user = wp_get_current_user(); ?>
                <div class="navbar-brand"><?php printf(esc_html__('Howdy, %1$s!', 'mihanpanel'), $current_user->display_name); ?></div>
            </div>
        </div>
    </nav>
    <div class="mp-content">
        <div class="container-fluid">
            <div class="row">
                <?php
                \mihanpanel\app\handle_view::handle_panel_widgets();
                if ($off_code = get_option('mp_offer_code')): ?>
                    <div class="col-md-8">
                        <div class="mihanpanel-card mihanpanel-card-stats">
                            <div class="mihanpanel-card-header" data-background-color="purple">
                                <i class="fas fa-3x fa-gift"></i>
                            </div>
                            <div class="mihanpanel-card-content">
                                <?php $off_code_text = \mihanpanel\app\options::get_offer_code_text();?>
                                <p class="category"><?php echo $off_code_text; ?></p>
                                <h3 class="title"
                                    style="text-align:center;line-height:40px;"><?php printf(esc_html__('Off code: %1$s', 'mihanpanel'), $off_code) ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="clear"></div>
                <?php if (get_option('mp_dashboard_message') != null): ?>
                    <div class="alert alert-primary" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message2') != null): ?>
                    <div class="alert alert-primary2" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message2')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message3') != null): ?>
                    <div class="alert alert-primary3" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message3')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message4') != null): ?>
                    <div class="alert alert-primary4" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message4')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message5') != null): ?>
                    <div class="alert alert-primary5" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message5')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message6') != null): ?>
                    <div class="alert alert-primary6" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message6')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message7') != null): ?>
                    <div class="alert alert-primary7" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message7')); ?>
                    </div>
                <?php endif; ?>
                <?php if (get_option('mp_dashboard_message8') != null): ?>
                    <div class="alert alert-primary8" role="alert">
                        <?php echo esc_html(get_option('mp_dashboard_message8')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
