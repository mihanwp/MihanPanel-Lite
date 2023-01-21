<?php
namespace mihanpanel\app\adapter;
class mihanticket
{
    static function get_user_tickets_count($user_id)
    {
        global $wpdb;
        $tbl_name = $wpdb->prefix . 'mihanticket_tickets';
        $query = "SELECT count(id) FROM {$tbl_name} WHERE user_id=%d and parent_ticket_id IS NULL";
        return $wpdb->get_var($wpdb->prepare($query, $user_id));
    }
}