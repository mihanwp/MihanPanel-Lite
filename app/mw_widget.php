<?php
namespace mwplite\app;
class mw_widget
{
    static function init()
    {
        register_widget('\mwplite\app\widget\mw_profile');
    }
}