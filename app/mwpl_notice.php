<?php
namespace mwplite\app;
if(defined('ABSPATH') && !class_exists('mwpl_notice'))
{
    class mwpl_notice
    {
        static function show_admin_setting_panel_notices()
        {
            // settings_errors('mp_save_setting_msg');
            $panel_errors = get_settings_errors('mp_save_setting_msg', true);
            if(!$panel_errors)
            {
                return;
            }
            foreach($panel_errors as $error)
            {
                ?>
                <div class="<?php echo esc_attr($error['setting']);?>">
                    <p class="alert <?php echo esc_attr($error['type']);?>" id="<?php echo esc_attr($error['code']); ?>"><?php echo esc_html($error['message']); ?></p>
                </div>
                <?php
            }
        }
        static function has_notice()
        {
            $notice = mwpl_session::get('mw_notice');
            return $notice ? true : false;
        }
        static function add_notice($type, $notice_msg)
        {
            $notice = [
                'msg' => $notice_msg,
                'type' => $type
            ];
            mwpl_session::store('mw_notice', $notice);
        }
        static function add_multiple_notice($type, $notice_msg)
        {
            $notices = mwpl_session::get('mw_notice');
            $i = isset($notices['multiple']) ? intval(count($notices['multiple'])) : 0;
            $notices['multiple'][$i]['msg'] = $notice_msg;
            $notices['multiple'][$i]['type'] = $type;
            mwpl_session::store('mw_notice', $notices);
        }
        static function get_notice()
        {
            if(!self::has_notice())
            {
                return false;
            }
            $notices = mwpl_session::get('mw_notice');
            $notice['msg'] = isset($notices['msg']) ? $notices['msg'] : false;
            $notice['type'] = isset($notices['type']) ? $notices['type'] : false;
            return $notice;
        }
        static function get_multiple_notice()
        {
            if(!self::has_notice())
            {
                return false;
            }
            $notices = mwpl_session::get('mw_notice');
            $notice_data = isset($notices['multiple']) && $notices['multiple'] ? $notices['multiple'] : false;
            return $notice_data;
        }
        static function once_get_notice()
        {
            if(!self::has_notice())
            {
                return false;
            }
            $notice = self::get_notice();
            self::remove_notice();
            return $notice;
        }
        static function once_get_multiple_notice()
        {
            if(!self::has_notice())
            {
                return false;
            }
            $notices = self::get_multiple_notice();
            self::remove_notice();
            return $notices;
        }
        static function remove_notice()
        {
            if(self::has_notice())
            {
                mwpl_session::unset('mw_notice');
            }
        }
    }
}