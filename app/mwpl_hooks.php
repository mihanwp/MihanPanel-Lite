<?php
namespace mwplite\app;
if(defined('ABSPATH') && !class_exists('mwpl_hooks'))
{
    class mwpl_hooks
    {
        public static function init()
        {
            // register scheduled event to check sessions
            add_action('init', ['\mwplite\app\mwpl_config', 'init_session_scheduled_event']);
            add_action('mihanpanel_session', ['\mwplite\app\mwpl_session', 'clear_expired_session']);
            add_action('init', ['\mwplite\app\mwpl_config', 'start_depends']);
            add_action('init', ['\mwplite\app\mwpl_config', 'init_shortcode']);
            add_action('admin_menu', ['\mwplite\app\mwpl_admin_menu', 'init']);
            add_action('admin_init', ['\mwplite\app\mwpl_options', 'register_settings']);
            add_action('admin_init', ['\mwplite\app\mwpl_config', 'redirect_to_option_panel_handler']);
            add_action('admin_notices', ['\mwplite\app\mwpl_notice', 'show_admin_setting_panel_notices']);
            add_filter("plugin_action_links_" . MW_MIHANPANEL_BASE_NAME, ['\mwplite\app\mwpl_sundry', 'add_go_pro_link_in_plugins_list']);
            add_action('admin_enqueue_scripts', ['mwplite\app\mwpl_assets', 'load_media_uploader']);
            add_action('admin_enqueue_scripts', ['mwplite\app\mwpl_assets', 'load_admin_user_profile']);
    
            add_filter('wp_new_user_notification_email', ['\mwplite\app\mwpl_email', 'filter_new_user_email_notify'], 10, 2);
            add_filter('retrieve_password_title', ['\mwplite\app\mwpl_email', 'filter_reset_password_email_title'], 10, 3);
            add_filter('retrieve_password_message', ['\mwplite\app\mwpl_email', 'filter_reset_password_email_message'], 10, 4);
    
            add_action('wp_login', ['\mwplite\app\mwpl_config', 'logout_non_active_account'], 1, 2);
            add_action('wp_login', ['\mwplite\app\mwpl_config', 'redirect_non_admin_after_login'], 10, 2);
            add_action('admin_init', ['\mwplite\app\mwpl_config', 'redirect_non_admin_from_admin_panel'], 1);
            add_filter('wp_login_errors', ['\mwplite\app\mwpl_config', 'handle_after_register_message']);
    
            add_action('template_redirect', ['\mwplite\app\mwpl_config', 'redirect_not_logged_in_user_from_panel']);
    
            add_action('plugins_loaded', ['\mwplite\app\mwpl_config', 'load_text_domain']);
    
            add_action('elementor/elements/categories_registered', ['\mwplite\app\mwpl_hooks', 'add_elementor_category']);
    
            add_filter('register_url', ['\mwplite\app\mwpl_config', 'filter_register_url'], 1);
    
            add_action('user_register', ['\mwplite\app\mwpl_users', 'set_activation_process']);
            add_action('login_init', ['\mwplite\app\mwpl_config', 'user_account_activation_process']);
            add_filter('login_message', ['\mwplite\app\mwpl_config', 'account_activation_message_handler']);
    
            add_action('mp_new_user_notification', ['\mwplite\app\mwpl_email', 'send_new_user_notification']);
            add_action('mp_change_user_account_activation_status', ['\mwplite\app\mwpl_email', 'send_change_account_status_email'], 10, 2);
    
            // add users accout status to users list
            add_filter('manage_users_columns', ['\mwplite\app\mwpl_users', 'add_users_status_column']);
            add_filter('manage_users_custom_column', ['\mwplite\app\mwpl_users', 'users_status_column_data'], 10, 3);
            add_action('edit_user_profile', ['\mwplite\app\mwpl_users', 'activate_btn_in_user_profile']);
            add_action('edit_user_profile_update', ['\mwplite\app\mwpl_users', 'activate_btn_in_user_profile_handler']);
            
            add_action('mw_before_render_user_fields', ['\mwplite\app\mwpl_user_fields', 'load_fields_deps'], 10, 2);
            
            // change view order link url
            add_filter('woocommerce_get_view_order_url', ['\mwplite\app\mwpl_panel', 'change_view_order_url'], 10, 2);
    
            add_action( 'plugins_loaded', ['\mwplite\app\mwpl_config', 'handle_update_version_process']);
    
            add_action('widgets_init', ['\mwplite\app\mwpl_widget', 'init']);
    
            // Send WordPress Emails from Website name & Email
            add_filter('wp_mail_from_name', ['\mwplite\app\mwpl_email', 'change_wordpress_email_name']);
            
            if (get_option('mp_wp_mail_from_email') != null) {
                add_filter('wp_mail_from', ['\mwplite\app\mwpl_email', 'change_wordpress_from_name']);
            }
    
            //change WP-login title
            add_filter('login_title', ['\mwplite\app\mwpl_sundry', 'change_login_title'], 99);
    
            //change wordpress logo title
            add_filter('login_headertitle', ['\mwplite\app\mwpl_sundry', 'change_login_logo_title']);
    
            // add css to mihanpanel admin
            add_action('admin_enqueue_scripts', ['\mwplite\app\mwpl_assets', 'load_admin_assets']);
            
            //change login page logo
            //add CSS to login page
            add_action('login_enqueue_scripts', ['\mwplite\app\mwpl_assets', 'login_assets'], '9999999');
    
            //change login logo url to your website home
            add_filter('login_headerurl', ['\mwplite\app\mwpl_sundry', 'change_login_logo_url'], '9999999');
    
            //hide wp admin bar for users
            if (get_option('mp_disable_wordpress_bar') == 1) {
                add_action('after_setup_theme', ['\mwplite\app\mwpl_sundry', 'hide_admin_bar']);
            }
    
            // panel page template
            add_filter( 'page_template', ['\mwplite\app\mwpl_sundry', 'handle_panel_page_template']);
    
            // load style.css when we are using mihanpanel shortcode
            add_action('wp_enqueue_scripts', ['\mwplite\app\mwpl_assets', 'load_front_assets'], 1);
            
            //disable mp emails
            add_action('init', ['\mwplite\app\mwpl_email', 'disable_default_emails']);
            
            // fix link changing by woocommerce
            add_filter('lostpassword_url', ['\mwplite\app\mwpl_sundry', 'change_reset_pass_url'], 11, 0);
            
            // add password field to register form
            add_action('register_form', ['\mwplite\app\mwpl_sundry', 'add_pass_field_to_register_form']);
            add_filter('registration_errors', ['\mwplite\app\mwpl_sundry', 'handle_pass_field_error_in_register_form'], 10, 3);
            add_action('user_register', ['\mwplite\app\mwpl_sundry', 'save_pass_field_value_in_register_form'], 10, 99);
        
            add_action('show_user_profile', ['\mwplite\app\mwpl_sundry', 'add_extra_fields_to_profile']);
            add_action('edit_user_profile', ['\mwplite\app\mwpl_sundry', 'add_extra_fields_to_profile']);
            add_action('personal_options_update', ['\mwplite\app\mwpl_sundry', 'handle_update_profile_extra_fields']);
            add_action('edit_user_profile_update', ['\mwplite\app\mwpl_sundry', 'handle_update_profile_extra_fields']);
            add_action('admin_notices', ['\mwplite\app\mwpl_sundry', 'update_profile_extra_fields_notice']);
            add_action('register_form', ['\mwplite\app\mwpl_sundry', 'add_extra_fields_to_register_form']);
    
            add_filter('registration_errors', ['\mwplite\app\mwpl_sundry', 'handle_register_form_extra_fields_errors'], 10, 3);
            add_action('user_register', ['\mwplite\app\mwpl_sundry', 'handle_register_form_extra_fields_save']);
        }
        
        public static function activation_hook()
        {
            mwpl_options::activate_user_can_register();
            \mwplite\app\mwpl_config::set_redirect_to_option_panel();
    
            //create dashboard page when activating mihanpanel
            $args = array(
                'post_type' => 'page',
                'name' => 'panel'
            );
            $query = new \WP_Query($args);
            if ($query->post_count == 0) {
                $mp_dashboard_page = array(
                    'post_title' =>    __('User Panel','mihanpanel'),
                    'post_content' => '[mihanpanel]',
                    'post_status' => 'publish',
                    'post_name' => 'panel',
                    'post_type' => 'page'
                );
                wp_insert_post($mp_dashboard_page);
            }
        }
        static function deactivation_hook()
        {
            // clear scheduled hook
            wp_clear_scheduled_hook('mihanpanel_session');
        }
        public static function add_elementor_category($elements_manager)
        {
            $elements_manager->add_category('mihanpanel', [
                'title' => __('Mihan Panel', 'mihanpanel'),
                'icon'  =>  'fa fa-plug'
            ]);
        }
    }
}