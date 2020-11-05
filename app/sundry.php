<?php
namespace mihanpanel\app;

use mihanpanel\app\presenter\user_fields;

class sundry
{
    static function change_login_title($origtitle)
    {
        return get_bloginfo('name') . ' - ' . __("Login", "mihanpanel");
    }
    static function change_login_logo_title()
    {
        return get_bloginfo('name');
    }
    static function change_login_logo_url()
    {
        return home_url();
    }
    static function hide_admin_bar()
    {
        if (!current_user_can('edit_posts') && !is_admin()) {
            show_admin_bar(false);
        }
    }
    static function handle_panel_page_template( $page_template ){
        $panel_slug = options::get_panel_slug();
        if ( is_page( $panel_slug ) ) {
            $page_template = MW_MIHANPANEL_DIR . 'mihanpanel-template.php';
        }
        return $page_template;
    }
    static function change_reset_pass_url()
    {
        $reset_pass_url = \mihanpanel\app\options::get_login_url('?action=lostpassword');
        return $reset_pass_url;
    }
    static function add_pass_field_to_register_form()
    {
        ?>
        <p>
            <label for="user_password"><?php esc_html_e("Password", "mihanpanel"); ?></label>
            <input required="required" type="password" name="user_password" id="user_password" class="input"/>
        </p>
        <?php
    }
    static function handle_pass_field_error_in_register_form($errors, $sanitized_user_login, $user_email)
    {
        if (empty($_POST['user_password'])) {
            $errors->add('pass_error', __("Password must not empty", "mihanpanel"));
        }
        return $errors;
    }
    static function save_pass_field_value_in_register_form($user_id)
    {
        $password = isset($_POST['user_password']) && $_POST['user_password'] ? sanitize_text_field($_POST['user_password']) : false;
        if ($password)
            wp_set_password($password, $user_id);
    }
    static function add_extra_fields_to_profile($user)
    {
        ?>
        <table class="form-table">
            <?php
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpanelfields';
            $fields = $wpdb->get_results("SELECT * FROM $tablename");
            foreach ($fields as $field):?>
                <tr>
                    <th><label for="<?php echo esc_attr($field->slug); ?>"><?php echo esc_html($field->label); ?></label></th>
                    <td>
                        <?php user_fields::render_field('wp-edit-profile', $field, $user, ['classes' => 'regular-text'])?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }
    static function handle_update_profile_extra_fields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpanelfields';
        $fields = $wpdb->get_results("SELECT * FROM $tablename");
        $form_data = $_POST['mw_fields'];
        
        foreach ($fields as $field) {
            if (!empty($form_data[$field->slug])) {
                $value = tools::sanitize_value($form_data[$field->slug], $form_data->type);
                update_user_meta($user_id, $field->slug, $value);
            }
        }
        do_action('mwpl_after_save_profile_extra_fields', $user_id);
    }
    static function update_profile_extra_fields_notice(){
        $notices = notice::once_get_multiple_notice();
        if(!$notices)
        {
            return false;
        }
        foreach($notices as $notice)
        {
            echo '<div class="notice notice-'.esc_attr($notice['type']).'"><p>'. esc_html($notice['msg']) .'</p></div>';
        }
    }
    static function add_extra_fields_to_register_form()
    { 
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpanelfields';
        $fields = $wpdb->get_results("SELECT * From $tablename order by priority");
        foreach ($fields as $field):?>
            <p>
                <label for="<?php echo esc_attr($field->slug)?>"><?php echo esc_html($field->label); ?></label>
                <?php user_fields::render_field('register-form', $field, null, ['classes' => 'input']); ?>
            </p>
    
        <?php
        endforeach;
    }
    static function handle_register_form_extra_fields_errors($errors, $sanitized_user_login, $user_email)
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpanelfields';
        $fields = $wpdb->get_results("SELECT * FROM $tablename");
        foreach ($fields as $field) {
            self::handle_register_form_extra_field_validation($field, $errors);
        }
        return $errors;
    }
    private static function handle_register_form_extra_field_validation($field, $errors)
    {
        $validation_methods = [];
        $validation_methods = apply_filters('mwpl_register_form_extra_fields_validation_methods', $validation_methods);
        if(isset($field->type) && is_array($validation_methods) && isset($validation_methods[$field->type]))
        {
            $target = $validation_methods[$field->type];
            if(is_array($target) && class_exists($target[0]) && method_exists($target[0], $target[1]))
            {
                return call_user_func([$target[0], $target[1]], $field, $errors);
            }
        }
        return self::validate_register_form_extra_fields_normal_type($field, $errors);
    }
    static function validate_register_form_extra_fields_normal_type($field, $errors)
    {
        if($field->required == 'yes' && empty($_POST['mw_fields'][$field->slug]))
        {
            $errors->add($field->slug . '_error', $field->label . __(' Should not be empty!', 'mihanpanel'));
            return false;
        }
        return true;
    }
    static function handle_register_form_extra_fields_save($user_id)
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'mihanpanelfields';
        $fields = $wpdb->get_results("SELECT * FROM $tablename");
        foreach ($fields as $field) {
            self::handle_register_form_extra_field_save($user_id, $field);
        }
    }
    private static function handle_register_form_extra_field_save($user_id, $field)
    {
        $handler_methods = [];
        $handler_methods = apply_filters('mwpl_register_form_extra_field_save_handler_methods', $handler_methods);
        if(isset($field->type) && is_array($handler_methods) && isset($handler_methods[$field->type]))
        {
            $target = $handler_methods[$field->type];
            if(is_array($target) && class_exists($target[0]) && method_exists($target[0], $target[1]))
            {
                return call_user_func([$target[0], $target[1]], $user_id, $field);
            }
        }
        return self::handle_register_form_extra_fields_normal_type($user_id, $field);
    }
    private static function handle_register_form_extra_fields_normal_type($user_id, $field)
    {
        if (!empty($_POST['mw_fields'][$field->slug])) {
            $value = tools::sanitize_value($_POST['mw_fields'][$field->slug], $field->type);
            update_user_meta($user_id, $field->slug, $value);
        }       
    }
}