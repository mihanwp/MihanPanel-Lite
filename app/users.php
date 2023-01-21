<?php
namespace mihanpanel\app;
class users
{
	public static function get_user_account_statuses(){
		$statuses = [
			'activate' => __('Active', 'mihanpanel'), 
			'deactivate' => __('Inactive', 'mihanpanel')
		];
		$statuses = apply_filters('ahura_get_user_account_statuses', $statuses);
		return $statuses;
	}
    static function is_admin_user($uid = false)
    {
        if(!$uid)
        {
            $uid = get_current_user_id();
        }
        return is_super_admin($uid) || user_can($uid, 'manage_options');
    }
    public static function get_activation_code_option_name()
    {
        return 'mp_activation_code';
    }
    public static function set_activation_code($user_id, $activation_code)
    {
        $option_name = self::get_activation_code_option_name();
        return update_user_meta($user_id, $option_name, $activation_code);
    }

    public static function get_activation_code($user_id)
    {
        $option_name = self::get_activation_code_option_name();
        return get_user_meta($user_id, $option_name, true);
    }

    public static function remove_activation_code($user_id)
    {
        $option_name = self::get_activation_code_option_name();
        return delete_user_meta($user_id, $option_name);
    }

    public static function create_activation_link($user_id)
    {
        $code = md5($user_id . time());
        $res = self::set_activation_code($user_id, $code);
        return $res ? $code : false;
    }

    public static function get_new_activation_link($user_id)
    {
        $activation_code = self::create_activation_link($user_id);
        $url = $activation_code ? options::get_login_url(['activation_code' => $activation_code]) : false;
        return $url;
    }

    public static function validate_activation_code($code)
    {
        $user = get_users([
            'meta_key'  =>  self::get_activation_code_option_name(),
            'meta_value'    =>  $code,
            'number'    =>  1,
            'fields'    =>  ['id']
        ]);
        $user = array_shift($user);
        $activation_code = self::get_activation_code($user->id);
        if ($activation_code !== $code)
            return false;

        do_action('mp_new_user_notification', $user->id);
        self::remove_activation_code($user->id);
        return true;
    }
    public static function is_active_account($user_id)
    {
        $activation_code = self::get_activation_code($user_id);
        return $activation_code ? false : true;
    }

    public static function set_activation_process($user_id)
    {
        $activation_type = options::get_account_activation_type();
        switch ($activation_type)
        {
            case options::LINK_ACTIVATION_MODE:
                email::send_activation_link($user_id);
                break;
            case options::MANUAL_ACTIVATION_MODE:
                email::manual_mode_email_process($user_id);
                users::create_activation_link($user_id);
                break;
        }
    }

    public static function add_users_status_column($columns){
        $columns['mw_user_status'] = esc_html__('Status', 'mihanpanel');
        return $columns;
    }

    public static function users_status_column_data($val, $column, $user_id){
        if($column == 'mw_user_status')
        {
            $is_active = self::is_active_account($user_id);
            if($is_active)
            {
                $bg_color= '#5ed85e';
                $color= '#165616';
            }else{
                $bg_color= '#d86363';
                $color= '#710b0b';
            }
            $title = $is_active ? esc_html__('Active', 'mihanpanel') : esc_html__('Inactive', 'mihanpanel');

            $style = 'background-color: '.$bg_color.'; color: '.$color.'; padding: 1px 10px; border-radius: 3px; font-weight: bold;';
            $res = '<span style="'.$style.'">'.$title.'</span>';
            return $res;
        }
    }
    public static function activate_btn_in_user_profile($user){
        $is_active = self::is_active_account($user->ID);
            if($is_active)
            {
                $bg_color= '#5ed85e';
                $color= '#165616';
            }else{
                $bg_color= '#d86363';
                $color= '#710b0b';
            }
            $title = $is_active ? __('Active', 'mihanpanel') : __('Inactive', 'mihanpanel');

            $style = 'background-color: '.$bg_color.'; color: '.$color.'; padding: 1px 10px; border-radius: 3px; font-weight: bold;';
            $status_btn = '<span style="'.$style.'">'.$title.'</span>';
        $view = views::get('admin.admin-profile.account-status');
        include $view;
    }
    static function get_all_roles()
    {
        return wp_roles();
    }
    static function get_all_roles_name()
    {
        return self::get_all_roles()->get_names();
    }
    static function get_user($user_id=null)
    {
        if(!$user_id)
        {
            $user_id = get_current_user_id();
        }
        return get_user_by('id', $user_id);
    }
    static function get_user_role($user_id=null)
    {
        $user = self::get_user($user_id);
        return $user->roles[0];
    }
    static function permision_by_role($roles_white_list, $user_role=null, $except_role=[])
    {
        if(!$user_role)
        {
            $user_role = self::get_user_role();
        }
        $except_role[] = 'administrator';
        return in_array($user_role, $roles_white_list) || in_array($user_role, $except_role);
    }
}