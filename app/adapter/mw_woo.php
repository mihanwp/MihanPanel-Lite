<?php
namespace mwplite\app\adapter;
class mw_woo
{
    public static function get_email($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $email = get_user_meta($uid, 'billing_email', true);
        return $email;
    }

    public static function set_email($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = update_user_meta($uid, 'billing_email', $value);
        return $res;
    }

    public static function get_company_name($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $company_name = get_user_meta($uid, 'billing_company', true);
        return $company_name;
    }

    public static function set_company_name($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = update_user_meta($uid, 'billing_company', $value);
        return $res;
    }
    public static function get_address_1($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $address = get_user_meta($uid, 'billing_address_1', true);
        return $address;
    }

    public static function set_address_1($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = update_user_meta($uid, 'billing_address_1', $value);
        return $res;}
    public static function get_address_2($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = get_user_meta($uid, 'billing_address_2', true);
        return $res;
    }

    public static function set_address_2($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = update_user_meta($uid, 'billing_address_2', $value);
        return $res;
    }


    public static function get_city($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $city = get_user_meta($uid, 'billing_city', true);
        return $city;
    }

    public static function set_city($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();   
        $res = update_user_meta($uid, 'billing_city', $value);
        return $res;
    }

    public static function get_zip_code($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = get_user_meta($uid, 'billing_postcode', true);
        return $res;
    }

    public static function set_zip_code($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = update_user_meta($uid, 'billing_postcode', $value);
        return $res;
    }

    public static function get_phone($uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = get_user_meta($uid, 'billing_phone', true);
        return $res;
    }

    public static function set_phone($value, $uid=false)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        $res = update_user_meta($uid, 'billing_phone', $value);
        return $res;
    }

}