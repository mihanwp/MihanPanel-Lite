<?php
namespace mihanpanel\app\presenter;

use mihanpanel\app\adapter\woo;
use mihanpanel\app\tools;

class user_fields
{
    static function render_woocommerce_fields($view)
    {
        if(!tools::is_woocommerce_active())
        {
            return false;
        }
        $billing_fields = woo::get_billing_fields();
        $active_fields = woo::get_active_fields();
        ?>
        <div class="row">
            <?php foreach($active_fields as $field):
            $method = 'render_woo_field_' . $field;
            if(method_exists(__CLASS__, $method))
            {
                self::{$method}($field, $billing_fields[$field]);
            }else{
                self::render_woo_field_normal($field, $billing_fields[$field]);
            }
            endforeach; ?>
        </div>
        <?php
    }
    static function render_woo_field_normal($field_id, $field_data)
    {
        ?>
        <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field_data['label']); ?></label>
                    <div class="form-group label-floating">
                        <input name="wc[<?php echo esc_attr($field_id); ?>]" type="text" id="<?php echo esc_attr($field_id); ?>"
                               value="<?php echo esc_attr(woo::get_field_value($field_id)); ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
        <?php
    }
    static function render_woo_field_billing_address_1($field_id, $field_data)
    {
        self::render_woo_textarea_field($field_id, $field_data);
    }
    static function render_woo_field_billing_address_2($field_id, $field_data)
    {
        self::render_woo_textarea_field($field_id, $field_data);
    }
    static function render_woo_textarea_field($field_id, $field_data)
    {
        ?>
        <div class="col-md-12">
            <div class="form-group">
                <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field_data['label']) ?></label>
                <div class="form-group label-floating">
                    <textarea placeholder="<?php echo esc_attr($field_data['placeholder']); ?>" name="wc[<?php echo esc_attr($field_id); ?>]" type="text" id="<?php echo esc_attr($field_id); ?>"
                            class="form-control"><?php echo esc_textarea(woo::get_field_value($field_id)); ?></textarea>
                </div>
            </div>
        </div>
        <?php
    }
    private static function get_render_fields_method($field_type)
    {
        $fields_render_method = [
            'textarea' => [__CLASS__, 'render_textarea_field'],
        ];
        $fields_render_method = apply_filters('mwpl_panel/profile/render_fields_method', $fields_render_method, $field_type);
        if(
            isset($fields_render_method[$field_type]) &&
            is_array($fields_render_method[$field_type]) &&
            class_exists($fields_render_method[$field_type][0]) &&
            method_exists($fields_render_method[$field_type][0], $fields_render_method[$field_type][1])
            )
        {
            return $fields_render_method[$field_type];            
        }
    }
    public static function render_field($view, $field, $current_user=null, $args=[])
    {
        $method_data = self::get_render_fields_method($field->type);
        do_action('mw_before_render_user_fields', $field->type, $field);
        if($method_data)
        {
            return call_user_func([$method_data[0], $method_data[1]], $view, $field, $current_user, $args);
        }
        return self::render_normal_field($view, $field, $current_user, $args);
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