<?php
namespace mwplite\app;

use mwplite\app\presenter\mwpl_user_fields;
if(defined('ABSPATH') && !class_exists('mwpl_sundry'))
{
    class mwpl_sundry
    {
        static function add_go_pro_link_in_plugins_list($links)
        {
            $pro_version_link = mwpl_tools::get_pro_version_link();
            $links['go_pro'] = sprintf('<a target="_blank" style="color: #673ab6;font-weight: bold; font-size: 15px;" href="%s">%s</a>', esc_url($pro_version_link), __("Pro Version", 'mihanpanel'));
            return $links;
        }
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
            $panel_slug = mwpl_options::get_panel_slug();
            if ( is_page( $panel_slug ) ) {
                $page_template = MW_MIHANPANEL_LITE_DIR . 'mihanpanel-template.php';
            }
            return $page_template;
        }
        static function change_reset_pass_url()
        {
            $reset_pass_url = mwpl_options::get_login_url('?action=lostpassword');
            return $reset_pass_url;
        }
        static function add_pass_field_to_register_form()
        {
            ?>
            <p>
                <label for="user_password"><?php _e("Password", "mihanpanel"); ?></label>
                <input required="required" type="password" name="user_password" value="" id="user_password" class="input"/>
            </p>
            <?php
        }
        static function handle_pass_field_error_in_register_form($errors, $sanitized_user_login, $user_email)
        {
            if (empty(sanitize_text_field($_POST['user_password']))) {
                $errors->add('pass_error', __("Password must not empty", "mihanpanel"));
            }
            return $errors;
        }
        static function save_pass_field_value_in_register_form($user_id)
        {
            $pass = isset($_POST['user_password']) ? sanitize_text_field($_POST['user_password']) : false;
            if ($pass)
                wp_set_password($pass, $user_id);
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
                        <?php mwpl_user_fields::render_field('wp-edit-profile', $field, $user, ['classes' => 'regular-text'])?>
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
                    $value = mwpl_tools::sanitize_value($form_data[$field->slug], $field->type);
                    update_user_meta($user_id, $field->slug, $value);
                }
            }
        }
        static function update_profile_extra_fields_notice(){
            $notices = mwpl_notice::once_get_multiple_notice();
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
                    <?php mwpl_user_fields::render_field('register-form', $field, null, ['classes' => 'input']); ?>
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
                if ($field->required == 'yes') {
                    if (empty($_POST['mw_fields'][$field->slug])) {
                        $errors->add($field->slug . '_error', $field->label . __(' Should not be empty!', 'mihanpanel'));
                    }
                }
            }
            return $errors;
        }
        static function handle_register_form_extra_fields_save($user_id)
        {
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpanelfields';
            $fields = $wpdb->get_results("SELECT * FROM $tablename");
            foreach ($fields as $field) {
                if (!empty($_POST['mw_fields'][$field->slug])) {
                    $value = mwpl_tools::sanitize_value($_POST['mw_fields'][$field->slug], $field->type);
                    update_user_meta($user_id, $field->slug, $value);
                }
            }
        }
    }
}