<?php
namespace mihanpanel\app;
class views
{
    public static function get($file_name, $extension = 'php')
    {
        $file_name = 'views.' . $file_name;
        return self::get_from_main_app($file_name, $extension);
    }
    static function get_from_main_app($file_name, $extension = 'php')
    {
        $file_name = str_replace('.', DIRECTORY_SEPARATOR, $file_name);
        $file_name = MW_MIHANPANEL_DIR . $file_name . '.' . $extension;
        return file_exists($file_name) && is_readable($file_name) ? $file_name : false;
    }
    static function is_view_exists($file_name, $extension = 'php')
    {
        $file_name = str_replace('.', DIRECTORY_SEPARATOR, $file_name);
        $file_name = MW_MIHANPANEL_DIR . $file_name . '.' . $extension;
        return file_exists($file_name) && is_readable($file_name);
    }
}