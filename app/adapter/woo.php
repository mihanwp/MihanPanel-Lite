<?php
namespace mihanpanel\app\adapter;
class woo
{
    static function get_billing_fields()
    {
        $fields = WC()->countries->get_address_fields();
        unset($fields['billing_first_name']);
        unset($fields['billing_last_name']);
        unset($fields['billing_country']);
        unset($fields['billing_state']);
        $fields['billing_address_2']['label'] = __("Address 2", 'mihanpanel');
        return $fields;
    }
    static function get_active_fields()
    {
        $fields = self::get_billing_fields();
        $active_fields = array_keys($fields);
        $active_fields = apply_filters('mwpl_profile/woocommerce_active_fields', $active_fields);
        return $active_fields;
    }
    static function set_field_value($field, $value, $uid=null)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        return update_user_meta($uid, $field, $value);
    }
    static function get_field_value($field, $uid=null)
    {
        $uid = intval($uid) ? $uid : get_current_user_id();
        return get_user_meta($uid, $field, true);
    }
}