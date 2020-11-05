<?php
namespace mihanpanel\app;
class widget
{
    static function init()
    {
        register_widget('\mihanpanel\app\widget\profile');
    }
}