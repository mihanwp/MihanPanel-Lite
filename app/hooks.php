<?php
namespace mihanpanel\app;


class hooks
{
    public static function init()
    {
        // register scheduled event to check sessions
        add_action('admin_init', [__CLASS__, 'activation_hook']);
        add_action('init', ['\mihanpanel\app\config', 'init_session_scheduled_event']);
        add_action('mihanpanel_session', ['\mihanpanel\app\session', 'clear_expired_session']);
        add_action('init', ['\mihanpanel\app\config', 'start_depends']);
        add_action('init', ['\mihanpanel\app\config', 'init_shortcode']);
        add_action('admin_menu', ['\mihanpanel\app\admin_menu', 'add_main_menu_page'], 1);
        add_action('admin_menu', ['\mihanpanel\app\admin_menu', 'init']);
        add_action('admin_init', ['\mihanpanel\app\options', 'register_settings']);

        add_filter('mwpl_middleware/option_panel', ['\mihanpanel\app\handle_view', 'optionPanelContentMiddlewares']);
        add_action('pre_update_option_rwl_page', ['\mihanpanel\app\config', 'handleBeforeUpdateLoginUrlOptionProcess'], 10, 3);
        add_action('pre_update_option_mp_panelslug', ['\mihanpanel\app\config', 'handleBeforeUpdatePanelSlugOptionProcess'], 10, 3);

        add_action('admin_notices', ['\mihanpanel\app\notice', 'show_admin_setting_panel_notices']);
        add_action('admin_notices', ['\mihanpanel\app\sundry', 'showMihanPanelHealthNotices']);
        add_action('admin_bar_menu', ['\mihanpanel\app\config', 'addMihanPanelMenusToAdminBarMenu'], 100);

        add_action('admin_enqueue_scripts', ['mihanpanel\app\assets', 'load_admin_user_profile']);

        add_filter('wp_new_user_notification_email', ['\mihanpanel\app\email', 'filter_new_user_email_notify'], 10, 2);
        add_filter('retrieve_password_title', ['\mihanpanel\app\email', 'filter_reset_password_email_title'], 10, 3);
        add_filter('retrieve_password_message', ['\mihanpanel\app\email', 'filter_reset_password_email_message'], 10, 4);

        add_filter('login_redirect', ['\mihanpanel\app\config', 'redirect_non_admin_after_login'], 10, 3);


        add_action('template_redirect', ['\mihanpanel\app\config', 'redirect_not_logged_in_user_from_panel']);

        add_action('plugins_loaded', ['\mihanpanel\app\config', 'load_text_domain']);

        add_action('elementor/elements/categories_registered', ['\mihanpanel\app\hooks', 'add_elementor_category']);

        add_filter('register_url', ['\mihanpanel\app\config', 'filter_register_url'], 1);

        add_action('user_register', ['\mihanpanel\app\users', 'set_activation_process']);
        add_action('login_init', ['\mihanpanel\app\config', 'user_account_activation_process']);

        add_action('mp_new_user_notification', ['\mihanpanel\app\email', 'send_new_user_notification']);
        add_action('mp_change_user_account_activation_status', ['\mihanpanel\app\email', 'send_change_account_status_email'], 10, 2);

        // add users accout status to users list
        add_filter('manage_users_columns', ['\mihanpanel\app\users', 'add_users_status_column']);
        add_filter('manage_users_custom_column', ['\mihanpanel\app\users', 'users_status_column_data'], 10, 3);
        add_action('edit_user_profile', ['\mihanpanel\app\users', 'activate_btn_in_user_profile']);

        // change view order link url
        add_filter('woocommerce_get_view_order_url', ['\mihanpanel\app\panel', 'change_view_order_url'], 10, 2);

        add_action( 'plugins_loaded', ['\mihanpanel\app\config', 'handle_update_version_process']);

        add_action('widgets_init', ['\mihanpanel\app\widget', 'init']);
        
        // Send WordPress Emails from Website name & Email
        add_filter('wp_mail_from_name', ['\mihanpanel\app\email', 'change_wordpress_email_name']);
        add_filter('wp_mail_from', ['\mihanpanel\app\email', 'change_wordpress_from_name']);
        
        //change WP-login title
        add_filter('login_title', ['\mihanpanel\app\sundry', 'change_login_title'], 99);
        
        //change wordpress logo title
        add_filter('login_headertitle', ['\mihanpanel\app\sundry', 'change_login_logo_title']);

        // add css to mihanpanel admin
        add_action('admin_enqueue_scripts', ['\mihanpanel\app\assets', 'load_admin_assets']);
        
        //change login logo url to your website home
        add_filter('login_headerurl', ['\mihanpanel\app\sundry', 'change_login_logo_url'], '9999999');

        //hide wp admin bar for users
        if (get_option('mp_disable_wordpress_bar') == 1) {
            add_action('after_setup_theme', ['\mihanpanel\app\sundry', 'hide_admin_bar']);
        }
        // panel page template
        add_filter( 'page_template', ['\mihanpanel\app\sundry', 'handle_panel_page_template'] );

        // load style.css when we are using mihanpanel shortcode
        add_action('wp_enqueue_scripts', ['\mihanpanel\app\assets', 'load_front_assets'], 1);

        //disable mp emails
        add_action('init', ['\mihanpanel\app\email', 'disable_default_emails']);
        
        // fix link changing by woocommerce
        add_filter('lostpassword_url', ['\mihanpanel\app\sundry', 'change_reset_pass_url'], 11, 0);

        // add password field to register form
        add_action('register_form', ['\mihanpanel\app\sundry', 'add_pass_field_to_register_form']);
        
        add_filter('registration_errors', ['\mihanpanel\app\sundry', 'handle_pass_field_error_in_register_form'], 10, 3);
        
        add_action('user_register', ['\mihanpanel\app\sundry', 'save_pass_field_value_in_register_form'], 10, 99);
    
        add_action('show_user_profile', ['\mihanpanel\app\sundry', 'add_extra_fields_to_profile']);
        add_action('edit_user_profile', ['\mihanpanel\app\sundry', 'add_extra_fields_to_profile']);
        add_action('personal_options_update', ['\mihanpanel\app\sundry', 'handle_update_profile_extra_fields']);
        add_action('edit_user_profile_update', ['\mihanpanel\app\sundry', 'handle_update_profile_extra_fields']);
        add_action('admin_notices', ['\mihanpanel\app\sundry', 'update_profile_extra_fields_notice']);
        add_action('register_form', ['\mihanpanel\app\sundry', 'add_extra_fields_to_register_form']);
        add_filter('registration_errors', ['\mihanpanel\app\sundry', 'handle_register_form_extra_fields_errors'], 10, 3);
        add_action('user_register', ['\mihanpanel\app\sundry', 'handle_register_form_extra_fields_save']);
        
        add_filter('mwpl_middlewares/tabs_field/new_record', ['\mihanpanel\app\middlewares', 'handle_tabs_new_record_middleware']);
        add_action('wp_dashboard_setup', ['\mihanpanel\app\widget', 'dashboard_widget']);
        add_action('wp_network_dashboard_setup', ['\mihanpanel\app\widget', 'dashboard_widget']);

        add_filter( 'login_display_language_dropdown', '__return_false' );
		
		if(\mihanpanel\app\tools::is_dokan_active()){
			add_action('init', function(){
				remove_action('login_init', 'dokan_redirect_to_register');
			});
		}

        // integrate some options string with wpml
        if(\mihanpanel\app\tools::is_wpml_active())
        {
            add_action('admin_init', ['\mihanpanel\app\config', 'WpmlRegisterSingleStringText']);
            add_filter('mihanpanel/edit_profile/fields_label_text', ['\mihanpanel\app\config', 'WpmlTranslateUserFieldsLabel'], 10, 2);
            add_filter('mihanpanel/panel/tabs_menu_item_label_text', ['\mihanpanel\app\config', 'WpmlTranslatePanelMenuTabLabel']);
            add_filter('mihanpanel/panel_url', ['\mihanpanel\app\config', 'WpmlHandlePanelPageUrl']);
            add_action('mwpl_login_form_after_start_form', ['\mihanpanel\app\login', 'renderWpmlSwitcherButton'], 1);
            add_action('mwpl_register_form_after_start_form', ['\mihanpanel\app\login', 'renderWpmlSwitcherButton'], 1);
        }

        // custom login & register form
        add_action('mwpl_register_form_after_default_fields', ['\mihanpanel\app\register', 'addExtraFields']);
        add_filter('mwpl_register_form_fields_validation_error', ['\mihanpanel\app\register', 'validateExtraFieldsData']);
        add_action('mwpl_register_form_after_create_new_user', ['\mihanpanel\app\register', 'saveExtraFieldsData']);
        add_filter('mwpl_register_form_extra_field_validation_handler_method', ['\mihanpanel\app\register', 'addExtraFieldsTypeValidationMethod']);

        // handle login logo
        add_action('mwpl_login_form_after_start_form', ['\mihanpanel\app\login', 'addLogoBeforeTitle']);
        add_action('mwpl_register_form_after_start_form', ['\mihanpanel\app\login', 'addLogoBeforeTitle']);

        add_action('plugins_loaded', ['\mihanpanel\app\reset_password', 'init']);

        // handle 2fa
        if(\mihanpanel\app\options::get_smart_login_2fa_status())
        {
            add_action('mihanpanel/panel/menu_default_tabs', ['\mihanpanel\app\panel', 'filter_default_tabs_render_items']);
            add_action('mihanpanel/panel/tab_file', ['\mihanpanel\app\panel', 'handle_2fa_menu_content'], 10, 2);
            
            add_action('mwpl_login_form_before_submit_button', ['\mihanpanel\app\login', 'addTwoFactorAutenticationFieldToLogin']);

            add_action('mihanpanel/admin/user_profile/before_start_extra_fields', ['\mihanpanel\app\sundry', 'addUserGoogleOtpStatusInAdminProfile']);
            add_action('mihanpanel/admin/user_profile/before_save_extra_fields', ['\mihanpanel\app\sundry', 'handleUpdateUserGoogleOtpStatusInAdminProfile']);
        }
    }
    public static function activation_hook()
    {
        if(!get_option('mwpl_handle_activation_hook'))
        {
            return false;
        }
        delete_option('mwpl_handle_activation_hook');
        options::activate_user_can_register();

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
        do_action('mwpl_befrore_activation_redirect');
        \mihanpanel\app\config::redirect_to_option_panel_handler();
    }
    static function deactivation_hook()
    {
        // clear scheduled hook
        wp_clear_scheduled_hook('mihanpanel_session');
        do_action('mwpl_deactiation_hook');
    }

    public static function add_elementor_category($elements_manager)
    {
        $elements_manager->add_category('mihanpanel', [
            'title' => __('Mihan Panel', 'mihanpanel'),
            'icon'  =>  'fa fa-plug'
        ]);
    }
}