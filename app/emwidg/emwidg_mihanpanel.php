<?php

namespace mihanpanel\app\emwidg;

use Elementor\Widget_Base;

class emwidg_mihanpanel extends Widget_Base
{

    /**
     * Get element name.
     *
     * Retrieve the element name.
     *
     * @return string The name.
     * @since 1.4.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'mihan_panel';
    }
    public function get_title()
    {
        return __('Mihan Panel', 'mihanpanel');
    }

    public function get_icon()
    {
        return 'fa fa-th-large';
    }
    public function get_categories()
    {
        return ['mpanel_elements'];
    }

    protected function render()
    {
        echo do_shortcode('[mihanpanel]');
    }

    protected function _content_template()
    {
        echo '<h5 style="text-align: center; background-color: #eaeaea;margin: 0; padding: 20px; ">Mihan Panel Shortcode</h5>';
    }
}