<?php
namespace mwplite\app;
if(defined("ABSPATH") && !class_exists("mwpl_widget"))
{
    class mwpl_widget
    {
        static function init()
        {
            register_widget('\mwplite\app\widget\mwpl_profile');
        }
    }
}