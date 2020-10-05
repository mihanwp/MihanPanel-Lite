<?php
namespace mwplite\app\presenter;

if(defined('ABSPATH') && !class_exists('mwpl_user_fields'))
{
    class mwpl_user_fields
    {
        public static function render_field($view, $field, $current_user=null, $args=[])
        {
            $type = $field->type;
            $method = 'render_' . $type . '_field';
            do_action('mw_before_render_user_fields', $type, $field);
            if (method_exists(__CLASS__, $method)) {
                return call_user_func([__CLASS__, $method], $view, $field, $current_user, $args);
            }
            self::render_normal_field($view, $field, $current_user, $args);
        }
        public static function render_normal_field($view, $field, $current_user=null, $args=[])
        {
            $user_field_slug = $field->slug;
            $classes = isset($args['classes']) ? $args['classes'] : '';
            $value_arg = $current_user ? 'value="'. esc_attr($current_user->{$user_field_slug}) .'"' : '';
            ?>
            <input class="<?php echo esc_attr($classes); ?>" type="<?php echo esc_attr($field->type); ?>"
                name="mw_fields[<?php echo esc_attr($field->slug); ?>]"
                <?php echo $value_arg;?>/>
            <?php
        }
        public static function render_textarea_field($view, $field, $current_user=null, $args=[])
        {
            $value = $current_user ? $current_user->{$field->slug} : '';
            $classes = isset($args['classes']) ? $args['classes'] : '';
            ?>
            <textarea class="<?php echo esc_attr($classes); ?>" name="mw_fields[<?php echo esc_attr($field->slug); ?>]" id="<?php echo esc_attr($field->slug); ?>" cols="30" rows="10"><?php echo esc_textarea($value); ?></textarea>
            <?php
        }
    }
}