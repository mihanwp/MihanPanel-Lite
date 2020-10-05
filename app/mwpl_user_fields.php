<?php
namespace mwplite\app;
if(defined('ABSPATH') && !class_exists('mwpl_user_fields'))
{
    class mwpl_user_fields
    {
        public static function get_types()
        {
            return [
                'text' => __('Text input', 'mihanpanel'),
                'textarea' => __('Textarea', 'mihanpanel'),
                'email' => __('Email', 'mihanpanel'),
                'number' => __('Numeral', 'mihanpanel'),
                'checkbox' => __('Checkbox', 'mihanpanel'),
                'selectbox' => __("Selectbox", 'mihanpanel'),
                'radio' => __("Radio Button", 'mihanpanel'),
                'datepicker' => __('Date Picker', 'mihanpanel'),
                'file_uploader' => __('File Uploader', 'mihanpanel')
            ];
        }
        static function get_pro_items()
        {
            return [
                'checkbox',
                'selectbox',
                'radio',
                'datepicker',
                'file_uploader',
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
            return $types[$type] ? $types[$type] : false;
        }
        static function load_fields_deps($field_type, $field)
        {
            $method = 'load_' . $field_type . '_assets';
            if (method_exists(__CLASS__, $method)) {
                call_user_func([__CLASS__, $method], $field_type, $field);
            }
        }
    }
}