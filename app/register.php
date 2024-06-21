<?php

namespace mihanpanel\app;

class register
{
    public static function set_filter_for_register_data_validation($filter_key, $validation_value){
        return apply_filters("mp_user_register_field_{$filter_key}_validation", $validation_value, $filter_key);
    }

    static function getExtraFields()
    {
        global $wpdb;
        $tblName = $wpdb->prefix . 'mihanpanelfields';
        return $wpdb->get_results("SELECT * From {$tblName} order by priority");
    }
    static function addExtraFields()
    {
        $fields = self::getExtraFields();
        foreach ($fields as $field) :
            if (!apply_filters('mwpl_user_fields_render_permission', true, $field, 'register-form')) {
                continue;
            } ?>
            <div class="mwpl-field-type-<?php echo $field->type; ?> mwpl-input-item">
                <?php \mihanpanel\app\presenter\user_fields::render_field('register-form', $field, null) ?>
            </div>
        <?php
        endforeach;
    }

    public static function get_register_form_fields(){
        $fields = [
                'username' => [
                    'label' => __('Username', 'mihanpanel'),
                    'icon' => \mihanpanel\app\assets::get_image_url('email-icon', 'svg'),
                    'type' => 'text'
                ],
                'email' => [
                    'label' => __('Email', 'mihanpanel'),
                    'icon' => \mihanpanel\app\assets::get_image_url('email-icon', 'svg'),
                    'type' => 'email'
                ],
                'password' => [
                    'label' => __('Password', 'mihanpanel'),
                    'icon' => \mihanpanel\app\assets::get_image_url('password-icon', 'svg'),
                    'type' => 'password'
                ],
        ];
        return apply_filters('mp_register_form_fields', $fields);
    }

    static function renderRegisterFormFields()
    {
        $fields = self::get_register_form_fields();
        ?>
        <?php if ($fields): ?>
            <?php foreach ($fields as $field_name => $field_data): ?>
                <?php
                $type = isset($field_data['type']) && !empty($field_data['type']) ? $field_data['type'] : 'text';
                $placeholder = isset($field_data['placeholder']) && !empty($field_data['placeholder']) ? $field_data['placeholder'] : '';
                $field_id = isset($field_data['id']) && !empty($field_data['id']) ? $field_data['id'] : $field_name;
                if ($type === 'password'):
                ?>
                <div class="mwpl-input-item mwpl-password-field-wrapper">
                    <label for="<?php echo esc_attr($field_id) ?>">
                        <span class="mwpl-icon">
                            <img src="<?php echo esc_attr($field_data['icon']) ?>" alt="<?php echo esc_attr($field_id) ?>-icon">
                        </span>
                        <span class="mwpl-field">
                            <input type="password" name="<?php echo esc_attr($field_name) ?>" id="<?php echo esc_attr($field_id) ?>" placeholder="<?php echo esc_attr($placeholder) ?>">
                            <span class="mwpl-label-value"><?php _e('Password', 'mihanpanel') ?></span>
                        </span>
                        <span class="mwpl-action-icon" mwpl-action="show">
                            <img mwpl-action="show-pass" src="<?php echo \mihanpanel\app\assets::get_image_url('show-pass-icon', 'svg') ?>" alt="show-pass-icon">
                            <img mwpl-action="hide-pass" src="<?php echo \mihanpanel\app\assets::get_image_url('hide-pass-icon', 'svg') ?>" alt="hide-pass-icon">
                        </span>
                    </label>
                    <span mwpl-weak-mode-text="<?php _e('Weak', 'mihanpanel')?>" mwpl-moderate-mode-text="<?php _e('Moderate', 'mihanpanel')?>" mwpl-strong-mode-text="<?php _e('Strong', 'mihanpanel')?>" class="mwpl-password-strength"></span>
                </div>
                <?php else: ?>
                    <div class="mwpl-input-item mwpl-<?php echo esc_attr($field_name) ?>-field-wrapper">
                        <label for="<?php echo esc_attr($field_id) ?>">
                        <span class="mwpl-icon">
                            <img src="<?php echo esc_attr($field_data['icon']) ?>" alt="<?php echo esc_attr($field_id) ?>-icon">
                        </span>
                            <span class="mwpl-field">
                            <input type="<?php echo esc_attr($type) ?>" name="<?php echo esc_attr($field_name) ?>" id="<?php echo esc_attr($field_id) ?>" placeholder="<?php echo esc_attr($placeholder) ?>">
                            <span class="mwpl-label-value"><?php echo esc_attr($field_data['label']) ?></span>
                        </span>
                        </label>
                    </div>
                <?php endif; ?>
                <?php do_action('mp_register_form_fields_loop', $field_name, $field_data); ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php
        do_action('mwpl_register_form_after_default_fields');
    }

    /**
     * return WP_Error on failure
     */
    static function validateExtraFieldsData()
    {
        $extraFields = self::getExtraFields();
        $handler_methods = apply_filters('mwpl_register_form_extra_field_validation_handler_method', []);

        foreach ($extraFields as $field) {
            if (isset($field->type) && is_array($handler_methods) && isset($handler_methods[$field->type])) {
                $target = $handler_methods[$field->type];
                if (is_array($target) && class_exists($target[0]) && method_exists($target[0], $target[1])) {
                    $res = call_user_func([$target[0], $target[1]], $field);
                    if (is_wp_error($res)) {
                        return $res;
                    }
                }
            } else {
                $metaData = isset($field->meta) && $field->meta ? unserialize($field->meta) : null;
                if (
                    isset($field->required)
                    && $field->required == 'yes'
                    && (!isset($_POST['mw_fields'][$field->slug]) || !$_POST['mw_fields'][$field->slug])
                    && !isset($metaData['data']['just_in_profile'])
                    )
                    
                {
                    return new \WP_Error('mwpl_register_form_fields_validation', __('Missing required fields data', 'mihanpanel'));
                }
            }
        }
        return true;
    }

    static function saveExtraFieldsData($userID)
    {
        $extraFields = self::getExtraFields();
        $handler_methods = apply_filters('mwpl_register_form_extra_field_store_data_handler_method', []);
        foreach($extraFields as $field)
        {
            if(isset($field->type) && is_array($handler_methods) && isset($handler_methods[$field->type]) && is_array($handler_methods[$field->type]))
            {
                // call handler method
                list($handlerClass, $handlerMethod) = $handler_methods[$field->type];
                if(class_exists($handlerClass) && method_exists($handlerClass, $handlerMethod))
                {
                    $result = call_user_func([$handlerClass, $handlerMethod], $field, $userID);
                    if(is_wp_error($result))
                    {
                        return $result;
                    }
                }
            }else{
                // handle normal fields
                if(isset($_POST['mw_fields'][$field->slug]) && !empty($_POST['mw_fields'][$field->slug]))
                {
                    $value = \mihanpanel\app\tools::sanitize_value($_POST['mw_fields'][$field->slug], $field->type);
                    update_user_meta($userID, $field->slug, $value);
                }
            }
        }
    }
    static function addExtraFieldsTypeValidationMethod($methods)
    {
        $methods['email'] = [__CLASS__, 'handleEmailFieldValidation'];
        return $methods;
    }
    static function handleEmailFieldValidation($field)
    {
        $fieldValue = sanitize_email($_POST['mw_fields'][$field->slug]);
        $metaData = isset($field->meta) && $field->meta ? unserialize($field->meta) : null;
        if(!$fieldValue && (!isset($field->required) || $field->required != 'yes' || isset($metaData['data']['just_in_profile'])))
        {
            return true;
        }
        return !filter_var($fieldValue, FILTER_VALIDATE_EMAIL) ? new \WP_Error('mwpl_register_form_fields_validation', __('Invalid email address', 'mihanpanel')) : true;
    }
}
