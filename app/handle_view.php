<?php
namespace mihanpanel\app;
class handle_view
{
    private static function handle_middlewares($middlewares)
    {
        if(!$middlewares || !is_array($middlewares))
        {
            return true;
        }
        foreach($middlewares as $middleware)
        {
            if(!$middleware)
            {
                continue;
            }
            $class = '';
            $method = '';
            $reverse_mode = false;
            if(is_array($middleware))
            {
                $class = $middleware[0];
                $method = $middleware[1];

                // check for reverse mode
                if(isset($middleware[2]))
                {
                    $reverse_mode = true;
                }
            }else{
                list($class, $method) = explode('::', $middleware);
            }
            if(strpos($class, '\\') !== 0)
            {
                $class = '\\' . $class;
            }
            if(class_exists($class) && method_exists($class, $method))
            {
                $res = call_user_func([$class, $method]);
                if($reverse_mode && $res)
                {
                    return false;
                }
                if(!$res && !$reverse_mode)
                {
                    return false;
                }
            }
        }
        return true;
    }
    static function handle_panel_content()
    {
        $middlewares = [
            // check account is active
            [__CLASS__, 'is_account_active'],
        ];
        $middlewares = apply_filters('mwpl_middlewares/panel_content', $middlewares);
        $middleware_res = self::handle_middlewares($middlewares);
        if($middleware_res)
        {
            panel::panel_content();
        }
    }
    static function is_account_active()
    {
        $status = users::is_active_account(get_current_user_id());
        if(!$status)
        {
            // load inactive account view
            $view = views::get('user.inactive_account');
            include $view;
            return false;
        }
        return true;
    }
    static function handle_option_panel_view($active_tab)
    {
        $middlewares = apply_filters('mwpl_middleware/option_panel', []);
        $middleware_res = self::handle_middlewares($middlewares);
        if($middleware_res)
        {
            $view = views::get('admin.panel-section.' . $active_tab);
            include $view;
        }
    }
    static function handle_panel_widgets()
    {
        $widgets = [
            'register_day',
            'comment',
            'edd',
            'woocommerce',
            'awesome_support'  
        ];
        foreach($widgets as $widget)
        {
            $method = 'handle_dashboard_widget_' . $widget;
            if(method_exists(__CLASS__, $method))
            {
                self::{$method}();
            }
        }
    }
    static function handle_dashboard_widget_register_day()
    {
        $middlewares = [];
        $middlewares = apply_filters('mwpl_panel_widgets_middlewares_register_day', $middlewares);
        $res = self::handle_middlewares($middlewares);
        if($res)
        {
            self::render_dashboard_widget_register_day();
        }
    }
    static function handle_dashboard_widget_comment()
    {
        $middlewares = [];
        $middlewares = apply_filters('mwpl_panel_widgets_middlewares_comment', $middlewares);
        $res = self::handle_middlewares($middlewares);
        if($res)
        {
            self::render_dashboard_widget_comment();
        }
    }

    static function handle_dashboard_widget_edd()
    {
        $middlewares = [
            ['\mihanpanel\app\tools', 'is_edd_active']
        ];
        $middlewares = apply_filters('mwpl_panel_widgets_middlewares_edd', $middlewares);
        $res = self::handle_middlewares($middlewares);
        if($res)
        {
            self::render_dashboard_widget_edd();
        }
    }
    static function handle_dashboard_widget_woocommerce()
    {
        $middlewares = [
            ['\mihanpanel\app\tools', 'is_woocommerce_active']
        ];
        $middlewares = apply_filters('mwpl_panel_widgets_middlewares_woocommerce', $middlewares);
        $res = self::handle_middlewares($middlewares);
        if($res)
        {
            self::render_dashboard_widget_woocommerce();
        }
    }
    static function handle_dashboard_widget_awesome_support()
    {
        $middlewares = [
            ['\mihanpanel\app\tools', 'is_awesome_support_active']
        ];
        $middlewares = apply_filters('mwpl_panel_widgets_middlewares_awesome_support', $middlewares);
        $res = self::handle_middlewares($middlewares);
        if($res)
        {
            self::render_dashboard_widget_awesome_support();
        }
    }
    
    static function render_dashboard_widget_register_day()
    {
        ?>
        <div class="col-md-4">
            <div class="mihanpanel-card mihanpanel-card-stats">
                <div class="mihanpanel-card-header" data-background-color="blue">
                    <i class="fas fa-3x fa-trophy"></i>
                </div>
                <div class="mihanpanel-card-content">
                    <p class="category"><?php esc_html_e("You are our user", "mihanpanel") ?></p>
                    <h3 class="title"><?php
                        $today_obj      = new \DateTime( date( 'Y-m-d', strtotime( 'today' ) ) );
                        $register_date  = get_the_author_meta( 'user_registered', get_current_user_id() );
                        $registered_obj = new \DateTime( date( 'Y-m-d', strtotime( $register_date ) ) );
                        $interval_obj   = $today_obj->diff( $registered_obj );
                        $day = '';
                        if( $interval_obj->days > 0 ) {
                            $day = $interval_obj->days;
                        } elseif( 0 == $interval_obj->days ) {
                            $day = 1;
                        }
                        printf(esc_html__('%d Day', 'mihanpanel'), $day);
                        ?></h3>
                </div>
            </div>
        </div>
        <?php
    }
    static function render_dashboard_widget_comment()
    {
        ?>
        <div class="col-md-4">
            <div class="mihanpanel-card mihanpanel-card-stats">
                <div class="mihanpanel-card-header" data-background-color="orange">
                    <i class="far fa-3x fa-comment"></i>
                </div>
                <div class="mihanpanel-card-content">
                    <p class="category"><?php esc_html_e("Your Comments", "mihanpanel") ?></p>
                    <h3 class="title"><?php
                        global $wpdb;
                        $userId = get_current_user_id();
                        $where = 'WHERE comment_approved = 1 AND user_id = ' . $userId;
                        $comment_count = $wpdb->get_var("SELECT COUNT( * ) AS total
                            FROM {$wpdb->comments}
                            {$where}");
                        echo $comment_count;
                        ?></h3>
                </div>
            </div>
        </div>
        <?php
    }
    static function render_dashboard_widget_edd()
    {
        ?>
            <div class="col-md-4">
                <div class="mihanpanel-card mihanpanel-card-stats">
                    <div class="mihanpanel-card-header" data-background-color="blue">
                        <i class="far fa-3x fa-file"></i>
                    </div>
                    <div class="mihanpanel-card-content">
                        <p class="category"><?php esc_html_e("Purchased files", "mihanpanel"); ?></p>
                        <h3 class="title">
                            <?php $user_id = get_current_user_id();
                            $mwpr_purchased = edd_get_users_purchases($user_id);
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
        <?php
    }
    static function render_dashboard_widget_woocommerce()
    {
        ?>
        <div class="col-md-4">
            <div class="mihanpanel-card mihanpanel-card-stats">
                <div class="mihanpanel-card-header" data-background-color="green">
                    <i class="fas fa-3x fa-shopping-cart"></i>
                </div>
                <div class="mihanpanel-card-content">
                    <p class="category"><?php esc_html_e("Your purchase count", "mihanpanel"); ?></p>
                    <h3 class="title"><?php $user_id = get_current_user_id();
                        echo wc_get_customer_order_count($user_id); ?></h3>
                </div>
            </div>
        </div>
        <?php
    }
    static function render_dashboard_widget_awesome_support()
    {
        ?>
        <div class="col-md-4">
            <div class="mihanpanel-card mihanpanel-card-stats">
                <div class="mihanpanel-card-header" data-background-color="red">
                    <i class="far fa-3x fa-life-ring"></i>
                </div>
                <div class="mihanpanel-card-content">
                    <p class="category"><?php esc_html_e("Your tickets", 'mihanpanel'); ?></p>
                    <h3 class="title">
                        <?php
                        $args = array(
                            'author' => get_current_user_id(),
                            'post_type' => 'ticket'
                        );
                        $posts = new \WP_Query($args);
                        echo $posts->found_posts;
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <?php
    }
    
    static function handle_option_panel_render_method($method, $alternative_method=false)
    {
        if(!is_array($method) || !$method)
        {
            return self::show_go_pro_link();
        }
        if(class_exists($method[0]) && method_exists($method[0], $method[1]))
        {
            return call_user_func([$method[0], $method[1]]);
        }        
        return $alternative_method && method_exists(__CLASS__, $alternative_method) ? call_user_func([__CLASS__, $alternative_method]) : self::show_go_pro_link();
    }
    static function show_go_pro_link()
    {
        $pro_version_link = tools::get_pro_version_link();
        ?>
        <span class="mpproversion"><a target="blank" href="<?php echo esc_url($pro_version_link); ?>"><?php _e("Pro Version", "mihanpanel");?></a></span>
        <?php
    }
    static function option_panel_field_login_form_theme()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/login_form_theme', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_redirect_normal_user()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/redirect_normal_user', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_use_mihan_avatar()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/use_mihan_avatar', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_panel_font()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/panel_font', []);
        self::handle_option_panel_render_method($render_method);
    }

    static function option_panel_field_sidebar_bg_color()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/sidebar_bg_color', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_sidebar_color()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/sidebar_color', []);
        self::handle_option_panel_render_method($render_method);
    }


    static function option_panel_field_hide_register_day_count_widget()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/hide_register_day_count_widget', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_hide_comment_widget()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/hide_comment_widget', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_hide_woocommerce_widget()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/hide_woocommerce_widget', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_hide_edd_widget()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/hide_edd_widget', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_hide_awesome_support_widget()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/hide_awesome_support_widget', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_ban_roles()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/ban_roles', []);
        self::handle_option_panel_render_method($render_method, 'option_panel_field_ban_roles_alternative');
    }
    static function option_panel_field_ban_roles_alternative()
    {
        ?>
        <div class="mp_option_single">
            <?php
                $all_roles = wp_roles()->get_names();
                foreach ($all_roles as $role_key => $role_name):
                    if ($role_key == 'administrator')
                        continue;
            ?>
                <p>
                    <label for="<?php echo $role_key ?>"><?php _e($role_name); ?></label>
                    <?php self::show_go_pro_link(); ?>
                </p>
            <?php endforeach; ?>
        </div>
        <?php
    }

    // register tab
    static function option_panel_field_register_text()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/register_text', []);
        self::handle_option_panel_render_method($render_method);
    
    }
    static function option_panel_field_roles_user_can_select()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/roles_user_can_select', []);
        self::handle_option_panel_render_method($render_method, 'option_panel_field_roles_user_can_select_alternative');
    }
    static function option_panel_field_roles_user_can_select_alternative()
    {
        ?>
        <div class="mp_option_single">
            <?php $roles_name = wp_roles()->get_names();
            foreach ($roles_name as $role_key => $role_name):
                if ($role_key == 'administrator')
                    continue;
                ?>
                <p>
                    <label><?php _e($role_name); ?></label>
                    <?php self::show_go_pro_link(); ?>
                </p>
            <?php endforeach; ?>
        </div>
        <?php
    }

    // login with sms tab

    static function option_panel_field_password_type()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/password_type', []);
        self::handle_option_panel_render_method($render_method, 'option_panel_field_password_type_alternative');
    }
    static function option_panel_field_password_type_alternative()
    {
        ?>
        <div class="mp_option_single">
            <?php
            $password_types = pro_version_detail::get_password_type_title();
            ?>
            <?php foreach($password_types as $name):?>
            <p>
                <label><?php echo $name; ?></label>
                <?php self::show_go_pro_link(); ?>
            </p>
            <?php endforeach; ?>
        </div>
        <?php
    }
    static function option_panel_user_phone_section()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/user_phone_section', []);
        self::handle_option_panel_render_method($render_method, 'option_panel_user_phone_section_alternative');
    }
    static function option_panel_user_phone_section_alternative()
    {
        ?>
        <div class="mp_option_single">
            <p>
                <label><?php _e('Auto confirm phone number after user register', 'mihanpanel'); ?></label>
                <?php self::show_go_pro_link(); ?>
            </p>
            <p>
                <label><?php _e('User can change phone number', 'mihanpanel'); ?></label>
                <?php self::show_go_pro_link(); ?>
            </p>
        </div>
        <?php
    }
    static function option_panel_field_sms_provider()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/sms_provider', []);
        self::handle_option_panel_render_method($render_method);
    }

    static function option_panel_field_phone_validation_sms_content()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/phone_validation_sms_content', []);
        self::handle_option_panel_render_method($render_method);
    }
    static function option_panel_field_dynamic_code_sms_content()
    {
        $render_method = apply_filters('mwpl_option_panel/render_method/dynamic_code_sms_content', []);
        self::handle_option_panel_render_method($render_method);
    }

    static function render_user_fields_type_selectbox($field_name, $selected=false)
    {
        $field_types = user_fields::get_types();
        ?>
        <select name="<?php echo esc_attr($field_name); ?>">
            <?php foreach($field_types as $type => $item): ?>
                <option <?php $selected ? selected($type, $selected) : null;?> <?php echo $item['disabled'] ? 'disabled' : false; ?> value="<?php echo $type?>"><?php echo $item['title']?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
}