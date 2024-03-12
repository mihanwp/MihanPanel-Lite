<?php
namespace mihanpanel\app;

class user_fields
{
    public static function get_types()
    {
        $is_lite_version = !defined('MIHANPANEL_PRO_DIR_PATH');
        return [
            'text' => [
                'title' => esc_html__('Text input', 'mihanpanel'),
                'disabled' => false
            ],
            'textarea' => [
                'title' => esc_html__('Textarea', 'mihanpanel'),
                'disabled' => false
            ],
            'email' => [
                'title' => esc_html__('Email', 'mihanpanel'),
                'disabled' => false
            ],
            'number' => [
                'title' => esc_html__('Numeral', 'mihanpanel'),
                'disabled' => false
            ],
            'checkbox' => [
                'title' => esc_html__('Checkbox', 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'selectbox' => [
                'title' => esc_html__("Selectbox", 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'radio' => [
                'title' => esc_html__("Radio Button", 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'datepicker' => [
                'title' => esc_html__('Date Picker', 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
            'file_uploader' => [
                'title' => esc_html__('File Uploader', 'mihanpanel'),
                'disabled' => $is_lite_version
            ],
        ];
    }
    public static function get_fields($cols=[])
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mihanpanelfields';
        $cols = is_array($cols) && $cols ? implode(', ', $cols) : '*';
        return $wpdb->get_results("SELECT {$cols} FROM $table_name ORDER BY `priority`");
    }
    static function get_field_type_title($type)
    {
        $types = self::get_types();
        return $types[$type] ? $types[$type]['title'] : false;
    }
    static function renderFirstNameField($user)
    {
        if(!apply_filters('mihanpanel/edit_profile/show_first_name_field_permission', true))
        {
            return false;
        }
        ?>
        <div class="col-md-6">
            <div class="form-group label-floating">
                <label><?php esc_html_e("First Name", "mihanpanel"); ?></label>
                <input name="general[first_name]" type="text" id="first_name"
                        value="<?php echo esc_attr($user->first_name); ?>"
                        class="form-control">
            </div>
        </div>
        <?php
    }
    static function renderLastNameField($user)
    {
        if(!apply_filters('mihanpanel/edit_profile/show_last_name_field_permission', true))
        {
            return false;
        }
        ?>
        <div class="col-md-6">
            <div class="form-group label-floating">
                <label><?php esc_html_e("Last Name", "mihanpanel")?></label>
                <input name="general[last_name]" type="text" id="last_name"
                        value="<?php echo esc_attr($user->last_name); ?>" class="form-control">
            </div>
        </div>
        <?php
    }
    static function renderPasswordField()
    {
        if(!apply_filters('mihanpanel/edit_profile/show_password_field_permission', true))
        {
            return false;
        }
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php esc_html_e("New password", "mihanpanel"); ?></label>
                    <input name="general[pass1]" type="password" id="pass1" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group label-floating">
                    <label><?php esc_html_e("Password repeat", "mihanpanel"); ?></label>
                    <input name="general[pass2]" type="password" id="pass2" class="form-control">
                </div>
            </div>
        </div>
        <?php
    }
    static function renderBioField($user)
    {
        if(!apply_filters('mihanpanel/edit_profile/show_bio_field_permission', true))
        {
            return false;
        }
        ?>
        <div class="row" id="bio-field-row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php esc_html_e("Bio", "mihanpanel"); ?></label>
                    <div class="form-group label-floating">
                        <textarea class="form-control" name="general[description]" id="description"
                                  rows="4"
                                  cols="50"><?php echo esc_textarea($user->description); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}