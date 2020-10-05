<?php
namespace mwplite\app;
if(defined("ABSPATH") && !class_exists("mwpl_users"))
{
    class mwpl_users
    {
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
            $url = $activation_code ? mwpl_options::get_login_url('?activation_code=' . $activation_code) : false;
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
            $activation_type = mwpl_options::get_account_activation_type();
            switch ($activation_type)
            {
                case mwpl_options::LINK_ACTIVATION_MODE:
                    mwpl_email::send_activation_link($user_id);
                    break;
                case mwpl_options::MANUAL_ACTIVATION_MODE:
                    mwpl_email::manual_mode_email_process($user_id);
                    mwpl_users::create_activation_link($user_id);
                    break;
            }
        }
    
        public static function add_users_status_column($columns){
            $columns['mw_user_status'] = __('Status', 'mihanpanel');
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
                $title = $is_active ? __('Active', 'mihanpanel') : __('Inactive', 'mihanpanel');
    
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
            $view = mwpl_views::get('admin.admin-profile.account-status');
            include $view;
        }
        public static function activate_btn_in_user_profile_handler($user_id)
        {
            if(!current_user_can('edit_user', $user_id))
            {
                return false;
            }
            $change_to = isset($_POST['mw_account_status']) ? sanitize_text_field($_POST['mw_account_status']) : false;
            if($change_to)
            {
                $is_active = self::is_active_account($user_id);
                switch($change_to)
                {
                    case 'activate':
                        if(!$is_active)
                        {
                            // activate account
                            $res = self::remove_activation_code($user_id);
                            if($res)
                            {
                                do_action('mp_change_user_account_activation_status', $user_id, $change_to);
                            }
                        }
                        break;
                    case'deactivate':
                        if($is_active)
                        {
                            // deactivate account
                            $res = self::create_activation_link($user_id);
                            if($res)
                            {
                                do_action('mp_change_user_account_activation_status', $user_id, $change_to);
                            }
                        }
                        break;
                }
                
            }
        }
    }
}