<?php

namespace mihanpanel\app;

class notice
{
    static function show_admin_setting_panel_notices()
    {
        // settings_errors('mp_save_setting_msg');
        $panel_errors = get_settings_errors('mp_save_setting_msg', true);
        if (!$panel_errors) {
            return;
        }
        foreach ($panel_errors as $error) {
?>
            <div class="<?php echo esc_attr($error['setting']); ?>">
                <p class="alert <?php echo esc_attr($error['type']); ?>" id="<?php echo esc_attr($error['code']); ?>"><?php echo esc_html($error['message']); ?></p>
            </div>
        <?php
        }
    }
    static function has_notice()
    {
        $notice = session::get('mw_notice');
        return $notice ? true : false;
    }
    static function add_notice($type, $notice_msg)
    {
        $notice = [
            'msg' => $notice_msg,
            'type' => $type
        ];
        session::store('mw_notice', $notice);
    }
    static function add_multiple_notice($type, $notice_msg)
    {
        $notices = session::get('mw_notice');
        $i = isset($notices['multiple']) ? intval(count($notices['multiple'])) : 0;
        $notices['multiple'][$i]['msg'] = $notice_msg;
        $notices['multiple'][$i]['type'] = $type;
        session::store('mw_notice', $notices);
    }
    static function get_notice()
    {
        if (!self::has_notice()) {
            return false;
        }
        $notices = session::get('mw_notice');
        $notice['msg'] = isset($notices['msg']) ? $notices['msg'] : false;
        $notice['type'] = isset($notices['type']) ? $notices['type'] : false;
        return $notice;
    }
    static function get_multiple_notice()
    {
        if (!self::has_notice()) {
            return false;
        }
        $notices = session::get('mw_notice');
        $notice_data = isset($notices['multiple']) && $notices['multiple'] ? $notices['multiple'] : false;
        return $notice_data;
    }
    static function once_get_notice()
    {
        if (!self::has_notice()) {
            return false;
        }
        $notice = self::get_notice();
        self::remove_notice();
        return $notice;
    }
    static function once_get_multiple_notice()
    {
        if (!self::has_notice()) {
            return false;
        }
        $notices = self::get_multiple_notice();
        self::remove_notice();
        return $notices;
    }
    static function remove_notice()
    {
        if (self::has_notice()) {
            session::unset('mw_notice');
        }
    }
    static function renderAdminNotice($noticeText, $args=[])
    {
        $wrapperStyles = [
            "position: relative",
            "margin: 20px 0",
            "width: 100%",
            "max-width:100%",
            "font-family:IRANSans",
            "font-size: 16px",
            "box-sizing: border-box",
            "border-radius: 10px",
            "font-weight: 100",
            'display: flex',
            'align-items: center',
            'gap: 25px',
            'padding: 30px 20px',
            'background: white',
            'border: 1px solid #e4e4e4',
            'color: black',
        ];
        $logoWrapperStyle = [
            'font-size: 25px',
            'font-weight: bold',
        ];
        $logoImgStyle = [
            'border-radius: 50%',
            'width: 65px',
        ];

        $noticeTextStyle = [
            'flex: auto',
        ];
        $noticeBoxLine = [
            'position: absolute',
            'width: 5px',
            'height: 85%',
            'background-color: #d70150',
            'border-radius: 20px',
        ];

        $btnStyles = [
            'box-shadow: none',
            'color:#d70150',
            'text-decoration:none',
            'border-radius:5px',
            'padding:10px 20px',
            'background-color: #f8eef1',
            'font-weight: bold',
            'text-wrap: nowrap',
        ];

        if (is_rtl()) {
            $noticeBoxLine[] = 'right: 0';
            $logoWrapperStyle[] = 'border-left: 2px solid #e4e3e3';
            $logoWrapperStyle[] = 'padding-left: 20px';
        } else {
            $noticeBoxLine[] = 'left: 0';
            $logoWrapperStyle[] = 'border-right: 2px solid #e4e3e3';
            $logoWrapperStyle[] = 'padding-right: 20px';
        }

        $wrapperStyles = implode(';', $wrapperStyles);
        $logoWrapperStyle = implode(';', $logoWrapperStyle);
        $logoImgStyle = implode(';', $logoImgStyle);
        $noticeTextStyle = implode(';', $noticeTextStyle);
        $noticeBoxLine = implode(';', $noticeBoxLine);
        $btnStyles = implode(';', $btnStyles);
        ?>

        <div class="wrap">
            <div class="mw-mihanpanel-admin-notice" style="<?php echo esc_attr($wrapperStyles) ?>">
                <span style="<?php echo esc_attr($noticeBoxLine) ?>"></span>
                <span style="<?php echo esc_attr($logoWrapperStyle) ?>"><img style="<?php echo esc_attr($logoImgStyle) ?>" src="<?php echo MW_MIHANPANEL_URL . 'img/mp-menu.png' ?>" alt="mihanpanel-logo"></span>
                <span style="<?php echo esc_attr($noticeTextStyle) ?>"><?php echo $noticeText; ?></span>
                <span class="mw-notice-buttons">
                    <?php if(isset($args['link']) && $args['link']): ?>
                        <a style="<?php echo esc_attr($btnStyles) ?>" href="<?php echo esc_url($args['link']) ?>"><?php echo isset($args['link_text']) && $args['link_text'] ? esc_html($args['link_text']) : __('Get Started', 'mihanpanel'); ?></a>
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <?php
    }
}
