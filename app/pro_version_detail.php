<?php
namespace mihanpanel\app;
class pro_version_detail
{
    static function get_password_type_title()
    {
        return [
            esc_html__('Wordpress Password Mode', 'mihanpanel'),
            esc_html__('Login with SMS', 'mihanpanel'),
            esc_html__('Both', 'mihanpanel'),
        ];
    }
}