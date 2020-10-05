<?php
namespace mwplite\app;
class mw_ajax
{

    public static function init()
    {
        add_action('wp_ajax_update_user_field_priority', __CLASS__ . '::update_user_field_priority');
        add_action('wp_ajax_update_tabs_priority', __CLASS__ . '::update_tabs_priority');
    }

    public static function update_user_field_priority()
    {
        $fields_data = isset($_POST['fields_data']) && $_POST['fields_data'] ? $_POST['fields_data'] : false;
        $table_name = 'mihanpanelfields';
        $res = self::update_priority($fields_data, $table_name);
        die(json_encode($res));
    }

    public static function update_tabs_priority()
    {
        $fields_data = isset($_POST['fields_data']) && $_POST['fields_data'] ? $_POST['fields_data'] : false;
        $table_name = 'mihanpaneltabs';
        $res = self::update_priority($fields_data, $table_name);
        die(json_encode($res));
    }
    private static function update_priority($fields_data, $table_name)
    {
        if (!$fields_data)
            die(json_encode(['stats' => 400]));


        $priority = 0;
        $success = '';
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        foreach ($fields_data as $item) {
            $update_res = $wpdb->update($table_name, ['priority' => $priority], ['id' => sanitize_text_field($item)]);
            if ($update_res && !$success)
                $success = $update_res;

            $priority++;
        }
        $res = [];
        $res['status'] = $success ? 200 : 500;
        return $res;
    }
}