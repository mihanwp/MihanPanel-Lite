<?php
namespace mihanpanel\app\form;

use mihanpanel\app\adapter\woo;
use mihanpanel\app\notice;
use mihanpanel\app\session;
use mihanpanel\app\tools;

class profile
{
    private static $_uid;
    static function do()
    {
        // set current user id
        self::$_uid = get_current_user_id();
        self::check_nonce(sanitize_text_field($_POST['mwpl_nonce']));
        do_action('mwpl_panel/profile/after_submit_form', self::$_uid);
        if(isset($_POST['general']))
        {
            self::user_data_handler();
        }
        
        if(isset($_POST['mw_fields']))
        {
            self::user_fields_handler();
        }
        if(isset($_POST['wc']))
        {
            self::woocommerce_handler();
        }
        \mihanpanel\app\tools::do_redirect();
    }
    static function handleChangePasswordForm()
    {
        self::$_uid = get_current_user_id();
        self::check_nonce(sanitize_text_field($_POST['mwpl_nonce']), 'mwpl_change_password');
        $mwuser_data = [];
        $hasError = true;
        if(isset($_POST['general']['pass1']))
        {
            if ($_POST['general']['pass1'] == $_POST['general']['pass2']) {
                $mwuser_data['ID'] = self::$_uid;
                $mwuser_data['user_pass'] = sanitize_text_field($_POST['general']['pass1']);
            } else {
                $type = 'error';
                $msg = __("Passwords don't match!", "mihanpanel");
                \mihanpanel\app\notice::add_multiple_notice($type, $msg);
            }
        }
        if($mwuser_data)
        {
            $updaters = wp_update_user($mwuser_data);
            if (is_wp_error($updaters)) {
                $type = 'error';
                $msg = __("An error occurred!", "mihanpanel");
            } else {
                session::unset('required_change_password');
                $type = 'success';
                $msg = __("Password successfully changed!", "mihanpanel");
                $hasError = false;
            }
            \mihanpanel\app\notice::add_multiple_notice($type, $msg);
        }
        if($hasError)
        {
            \mihanpanel\app\tools::do_redirect();
        }else{
            $panelUrl = \mihanpanel\app\options::get_panel_url();
            $args = [
                'tab' => 'edit-profile',
            ];
            $url = esc_url(add_query_arg($args, $panelUrl));
            wp_safe_redirect($url);
            exit;
        }
    }
    static function check_nonce($nonce, $action = 'mwpl_update_user_profile_panel')
        {
            if(!wp_verify_nonce($nonce, $action))
            {
                notice::add_multiple_notice('error', __('The operation failed due to security issues.', 'mihanpanel'));
                tools::do_redirect();
            }
        }
    static function user_data_handler()
    {
        $mwuser_data = array(
            'ID' => wp_get_current_user()->ID,
            'description' => sanitize_text_field($_POST['general']['description']),
            'first_name' => sanitize_text_field($_POST['general']['first_name']),
            'last_name' => sanitize_text_field($_POST['general']['last_name'])
        );

        if ($_POST['general']['pass1']) {
            if ($_POST['general']['pass1'] == $_POST['general']['pass2']) {
                $mwuser_data['user_pass'] = sanitize_text_field($_POST['general']['pass1']);
            } else {
                $type = 'error';
                $msg = __("Passwords don't match!", "mihanpanel");
                \mihanpanel\app\notice::add_multiple_notice($type, $msg);
            }
        }

        $updaters = wp_update_user($mwuser_data);
        if (is_wp_error($updaters)) {
            $type = 'error';
            $msg = __("An error occurred!", "mihanpanel");
        } else {
            $type = 'success';
            $msg = __("Successfully updated!", "mihanpanel");
        }
        \mihanpanel\app\notice::add_multiple_notice($type, $msg);
    }
    
    static function user_fields_handler()
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpanelfields';
        
        $updatingfields = $wpdb->get_results("SELECT * FROM $tablename where type!='file_uploader'");
        foreach ($updatingfields as $updatingfield) {
            $last_value = get_user_meta(self::$_uid, $updatingfield->slug, true);
            $field_meta = isset($updatingfield->meta) ? unserialize($updatingfield->meta) : false;
            $prevent_edit_field = !\mihanpanel\app\users::is_admin_user() && isset($field_meta['data']['prevent_edit_field']);
            if(!isset($_POST['mw_fields'][$updatingfield->slug]) || empty($_POST['mw_fields'][$updatingfield->slug]))
            {
                if(($prevent_edit_field && $last_value) || !apply_filters('mwpl_user_fields_render_permission', true, $updatingfield, 'profile'))
                {
                    continue;
                }
                if($updatingfield->required == 'yes')
                {
                    $type = 'error';
                    $msg = sprintf(__('%1$s must not be empty.', 'mihanpanel'), $updatingfield->label);
                    \mihanpanel\app\notice::add_multiple_notice($type, $msg);
                }else{
                    if($updatingfield->type=='checkbox')
                    {
                        update_user_meta(self::$_uid, $updatingfield->slug, 'non');
                    }else{
                        delete_user_meta(self::$_uid, $updatingfield->slug);
                    }
                }
            }else {
                if(($prevent_edit_field && $last_value) || !apply_filters('mwpl_user_fields_render_permission', true, $updatingfield, 'profile'))
                {
                    continue;
                }
                $value = tools::sanitize_value($_POST['mw_fields'][$updatingfield->slug], $updatingfield->type);
                update_user_meta(self::$_uid, $updatingfield->slug, $value);
            }
        }
    }
    static function woocommerce_handler()
    {
        foreach($_POST['wc'] as $key => $value)
        {
            woo::set_field_value(sanitize_key($key), sanitize_text_field($value));
        }
    }
}