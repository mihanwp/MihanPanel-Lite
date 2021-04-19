<?php
namespace mihanpanel\app\form;

use mihanpanel\app\adapter\woo;
use mihanpanel\app\notice;
use mihanpanel\app\tools;

class profile
{
    private static $_uid;
    static function do($form_data)
    {
        // set current user id
        self::$_uid = get_current_user_id();
        self::check_nonce($form_data['posts']['mwpl_nonce']);
        do_action('mwpl_panel/profile/after_submit_form', self::$_uid, $form_data);
        if(isset($form_data['posts']['general']))
        {
            self::user_data_handler($form_data['posts']['general']);
        }
        
        if(isset($form_data['posts']['mw_fields']))
        {
            self::user_fields_handler($form_data['posts']['mw_fields']);
        }
        if(isset($form_data['posts']['wc']))
        {
            self::woocommerce_handler($form_data['posts']['wc']);
        }
        \mihanpanel\app\tools::do_redirect();
    }
    static function check_nonce($nonce)
        {
            if(!wp_verify_nonce($nonce, 'mwpl_update_user_profile_panel'))
            {
                notice::add_multiple_notice('error', __('The operation failed due to security issues.', 'mihanpanel'));
                tools::do_redirect();
            }
        }
    static function user_data_handler($fields)
    {
        $mwuser_data = array(
            'ID' => wp_get_current_user()->ID,
            'description' => sanitize_text_field($fields['description']),
            'first_name' => sanitize_text_field($fields['first_name']),
            'last_name' => sanitize_text_field($fields['last_name'])
        );

        if ($fields['pass1']) {
            if ($fields['pass1'] == $fields['pass2']) {
                $mwuser_data['user_pass'] = sanitize_text_field($fields['pass1']);
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
    
    static function user_fields_handler($fields_data)
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpanelfields';
        
        $updatingfields = $wpdb->get_results("SELECT * FROM $tablename where type!='file_uploader'");
        foreach ($updatingfields as $updatingfield) {
            $last_value = get_user_meta(self::$_uid, $updatingfield->slug, true);
            $field_meta = isset($updatingfield->meta) ? unserialize($updatingfield->meta) : false;
            $prevent_edit_field = !\mihanpanel\app\users::is_admin_user() && isset($field_meta['data']['prevent_edit_field']);
            if(!isset($fields_data[$updatingfield->slug]) || empty($fields_data[$updatingfield->slug]))
            {
                if($prevent_edit_field && $last_value)
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
                if($prevent_edit_field && $last_value)
                {
                    continue;
                }
                $value = $fields_data[$updatingfield->slug];
                $value = tools::sanitize_value($value, $updatingfield->type);
                update_user_meta(self::$_uid, $updatingfield->slug, $value);
            }
        }
    }
    static function woocommerce_handler($wc_data)
    {
        foreach($wc_data as $field => $field_value)
        {
            woo::set_field_value($field, $field_value);
        }
    }
}