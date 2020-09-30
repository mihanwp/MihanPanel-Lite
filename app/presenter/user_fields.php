<?php
namespace mwplite\app\presenter;

class user_fields
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
        $value_arg = $current_user ? 'value="'. $current_user->{$user_field_slug} .'"' : '';
        ?>
        <input class="<?php echo $classes; ?>" type="<?php echo $field->type; ?>"
            name="mw_fields[<?php echo $field->slug; ?>]"
            <?php echo $value_arg;?>/>
        <?php
    }
    public static function render_textarea_field($view, $field, $current_user=null, $args=[])
    {
        $value = $current_user ? $current_user->{$field->slug} : '';
        $classes = isset($args['classes']) ? $args['classes'] : '';
        ?>
        <textarea class="<?php echo $classes; ?>" name="mw_fields[<?php echo $field->slug; ?>]" id="<?php echo $field->slug; ?>" cols="30" rows="10"><?php echo $value; ?></textarea>
        <?php
    }
}