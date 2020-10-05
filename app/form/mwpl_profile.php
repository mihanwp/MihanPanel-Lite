<?php
namespace mwplite\app\form;

use mwplite\app\mwpl_notice;
use mwplite\app\mwpl_tools;

if(defined('ABSPATH') && !class_exists('mwpl_profile'))
{
    class mwpl_profile
    {
        private static $_uid;
        static function do($form_data)
        {
            // set current user id
            self::$_uid = get_current_user_id();
            // check nonce
            self::check_nonce($form_data['posts']['mwpl_nonce']);

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
            mwpl_tools::do_redirect();
        }
        static function check_nonce($nonce)
        {
            if(!wp_verify_nonce($nonce, 'mwpl_update_user_profile_panel'))
            {
                mwpl_notice::add_multiple_notice('error', __('The operation failed due to security issues.', 'mihanpanel'));
                mwpl_tools::do_redirect();
            }
        }
        static function user_data_handler($fields)
        {
            $mwuser_data = array(
                'ID' => wp_get_current_user()->ID,
                'description' => sanitize_textarea_field($fields['description']),
                'first_name' => sanitize_text_field($fields['first_name']),
                'last_name' => sanitize_text_field($fields['last_name'])
            );
    
            if ($fields['pass1']) {
                if ($fields['pass1'] == $fields['pass2']) {
                    $mwuser_data['user_pass'] = sanitize_text_field($fields['pass1']);
                } else {
                    $type = 'error';
                    $msg = __("Passwords don't match!", "mihanpanel");
                    mwpl_notice::add_multiple_notice($type, $msg);
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
            mwpl_notice::add_multiple_notice($type, $msg);
        }
        static function user_fields_handler($fields_data)
        {
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpanelfields';
            
            $updatingfields = $wpdb->get_results("SELECT * FROM $tablename");
            foreach ($updatingfields as $updatingfield) {
                if(!isset($fields_data[$updatingfield->slug]) || empty($fields_data[$updatingfield->slug]))
                {
                    if($updatingfield->required == 'yes')
                    {
                        $type = 'error';
                        $msg = sprintf(__('%1$s must not be empty.', 'mihanpanel'), $updatingfield->label);
                        mwpl_notice::add_multiple_notice($type, $msg);
                    }
                }else {
                    $value = $fields_data[$updatingfield->slug];
                    $value = mwpl_tools::sanitize_value($value, $updatingfield->type);
                    update_user_meta(self::$_uid, $updatingfield->slug, $fields_data[$updatingfield->slug]);
                }
            }
        }
        static function woocommerce_handler($wc_data)
        {
            $email = isset($wc_data['email']) && $wc_data['email'] ? sanitize_email($wc_data['email']) : false;
            $company_name = isset($wc_data['company_name']) && $wc_data['company_name'] ? sanitize_text_field($wc_data['company_name']) : false;
            $address_1 = isset($wc_data['address_1']) && $wc_data['address_1'] ? sanitize_textarea_field($wc_data['address_1']) : false;
            $address_2 = isset($wc_data['address_2']) && $wc_data['address_2'] ? sanitize_textarea_field($wc_data['address_2']) : false;
            $city = isset($wc_data['city']) && $wc_data['city'] ? sanitize_text_field($wc_data['city']) : false;
            $zip_code = isset($wc_data['zip_code']) && $wc_data['zip_code'] ? sanitize_text_field($wc_data['zip_code']) : false;
            $phone = isset($wc_data['phone']) && $wc_data['phone'] ? wc_sanitize_phone_number($wc_data['phone']) : false;
    
            \mwplite\app\adapter\mwpl_woo::set_email($email);
            \mwplite\app\adapter\mwpl_woo::set_company_name($company_name);
            \mwplite\app\adapter\mwpl_woo::set_address_1($address_1);
            \mwplite\app\adapter\mwpl_woo::set_address_2($address_2);
            \mwplite\app\adapter\mwpl_woo::set_city($city);
            \mwplite\app\adapter\mwpl_woo::set_zip_code($zip_code);
            \mwplite\app\adapter\mwpl_woo::set_phone($phone);
        }
    }
}