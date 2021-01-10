<?php
namespace mihanpanel\app;
class gutenberg
{
    static function init()
    {
         // add gutenberg block
         add_action('init', ['\mihanpanel\app\assets', 'load_gutenberg_block_assets']);

         // add MihanPanel category
         add_filter('block_categories', [__CLASS__, 'filter_block_categories'], 10, 2);
    }
    static function filter_block_categories($categories, $post)
    {
        $mihanpanel = [
            'slug' => 'mihanpanel',
            'title' => esc_html__('MihanPanel', 'mihanpanel'),
        ];
        array_unshift($categories, $mihanpanel);
        return $categories;
    }
}