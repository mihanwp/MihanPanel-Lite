<?php
namespace mihanpanel\app;

class user_fields
{
    public static function get_types()
    {
        $is_lite_version = !defined('MIHANPANEL_PRO_DIR_PATH');
        return [
            'text' => [
                'title' => esc_html__('Text input', 'mihanpanel'),
                'disabled' => false
            ],
            'textarea' => [
                'title' => esc_html__('Textarea', 'mihanpanel'),
                'disabled' => false
            ],
            'email' => [
                'title' => esc_html__('Email', 'mihanpanel'),
                'disabled' => false
            ],
            'number' => [
                'title' => esc_html__('Numeral', 'mihanpanel'),
                'disabled' => false
            ],
            'checkbox' => [
                'title' => esc_html__('Checkbox', 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'selectbox' => [
                'title' => esc_html__("Selectbox", 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'radio' => [
                'title' => esc_html__("Radio Button", 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'datepicker' => [
                'title' => esc_html__('Date Picker', 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'file_uploader' => [
                'title' => esc_html__('File Uploader', 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
        ];
    }
    public static function get_fields($cols=[])
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpanelfields';
        $cols = is_array($cols) && $cols ? implode(', ', $cols) : '*';
        return $wpdb->get_results("SELECT {$cols} FROM $table_name ORDER BY `priority`");
    }
    static function get_field_type_title($type)
    {
        $types = self::get_types();
        return $types[$type] ? $types[$type]['title'] : false;
    }
}