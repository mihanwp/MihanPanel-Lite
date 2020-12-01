<?php
namespace mihanpanel\app\presenter;
class tabs_menu
{
    static function render_tab_item_icon($icon)
    {
        if(!$icon)
        {
            return false;
        }
        $icon_element = sprintf('<i class="mw_icon %s"></i>', $icon);
        $icon_element = apply_filters('mwpl_tab_item_icon_element', $icon_element, $icon);
        echo $icon_element;
    }
}