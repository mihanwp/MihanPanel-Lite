<?php
namespace mwplite\app;
class mw_email
{
    static function filter_content_type()
    {
        return 'text/html';
    }
    static function get_email_style($new_style = false)
    {
        $style = '<style>';
        $style .= '.aligncenter{display: block; margin: 0 auto;}';
        $style .= '.alignleft{float: left}';
        $style .= '.alignright{float: right}';
        $style .= $new_style ? $new_style . '</style>' : '</style>';
        return $style;
    }
    public static function do_filter_new_user_notification($user)
    {
        $email_content = mw_options::get_email_notify_content();
        $search = ['[[user_login]]', '[[display_name]]'];
        $replace = [$user->user_login, $user->display_name];
        $email_content = str_replace($search, $replace, $email_content);
        $style = self::get_email_style();
        return $style . '<pre dir="auto">' . $email_content . '</pre>';
    }
    public static function filter_new_user_email_notify($user_email, $user)
    {
        $email_content = self::do_filter_new_user_notification($user);
        if($email_content)
        {
            $user_email['message'] = $email_content;
            $user_email['headers'] = 'Content-Type: text/html';
        }
        return $user_email;
    }

    public static function filter_reset_password_email_title($subject, $user_login, $user_data)
    {
        $title = mw_options::get_reset_password_email_subject();
        return $title ? $title : $subject;
    }
    public static function filter_reset_password_email_message($message, $key, $user_login, $user_data)
    {
        add_filter('wp_mail_content_type', [__CLASS__, 'filter_content_type']);
        // key , user_login , first_name , last_name , display_name

        $content = mw_options::get_reset_password_email_content();
        if (!$content)
            return $message;

        $login_slug = mw_options::get_login_slug();
        $link = network_site_url($login_slug . '?action=rp&key=' . $key . '&login=' . rawurlencode($user_login));
        $link = '<a style="color: inherit" href="'.esc_url($link).'">'.$link.'</a>';
        $search = ['[[link]]', '[[user_login]]', '[[first_name]]', '[[last_name]]', '[[display_name]]'];
        $replace = [$link, $user_login, $user_data->first_name, $user_data->last_name, $user_data->dispay_name];
        $content = str_replace($search, $replace, $content);
        $style = self::get_email_style();
        return $content ? $style . '<pre dir="auto">' . $content . '</pre>' : $message;
    }

    public static function send_activation_link($user_id)
    {
        $activation_link = mw_users::get_new_activation_link($user_id);
        // disable default new user notification
        add_filter('wp_new_user_notification_email', '__return_false', 1);

        $user = get_user_by('id', $user_id);
        $subject = __("Account activation");
        $activation_link_text = '<a href="'.esc_url($activation_link).'">'. $activation_link.'</a>';
        $message = '<p>' . __("Click on below link to activate account") . '<br />' . $activation_link_text . '</p>';
        $header = 'Content-Type: text/html';
        wp_mail($user->user_email, $subject, $message, $header);
    }

    public static function manual_mode_email_process($user_id)
    {
        // disable default new user notification
        add_filter('wp_new_user_notification_email', '__return_false', 1);

        $user = get_user_by('id', $user_id);
        $message = self::do_filter_new_user_notification($user);
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        if (!$message){
            $message = __("Registration complete.");
            $message = sprintf("%s<br>%s<br>%s<br>%s", __("Registration complete. Please wait for admin approval.", 'mihanpanel'), __("Thanks!", 'mihanpanel'), $blogname, network_site_url());
        }

        $new_user_notification = array(
            'to'      => $user->user_email,
            /* translators: Login details notification email subject. %s: Site title */
            'subject' => __( '[%s] Login Details' ),
            'message' => $message,
            'headers' => 'Content-Type: text/html',
        );
        wp_mail(
            $new_user_notification['to'],
            wp_specialchars_decode(sprintf($new_user_notification['subject'], $blogname)),
            $new_user_notification['message'],
            $new_user_notification['headers']
        );
    }

    public static function send_new_user_notification($user_id)
    {
        $user = get_user_by('id', $user_id);
        $message = self::do_filter_new_user_notification($user);
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        if (!$message){
            $message = sprintf('%s<br>%s', __("Registration complete.", 'mihanpanel'), __('Thanks!', 'mihanpanel'), $blogname, network_site_url());
        }

        $new_user_notification = array(
            'to'      => $user->user_email,
            /* translators: Login details notification email subject. %s: Site title */
            'subject' => __( '[%s] Login Details' ),
            'message' => $message,
            'headers' => 'Content-Type: text/html',
        );
        wp_mail(
            $new_user_notification['to'],
            wp_specialchars_decode(sprintf($new_user_notification['subject'], $blogname)),
            $new_user_notification['message'],
            $new_user_notification['headers']
        );
    }
    static function send_change_account_status_email($user_id, $change_to)
    {
        $message = \mwplite\app\mw_options::get_change_account_status_email_content();
        if(!$message)
        {
            return false;
        }
        $subject = \mwplite\app\mw_options::get_change_account_status_email_subject();
        if(!$subject)
        {
            $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
            $subject = sprintf(__('[%s] Change Account Status', 'mihanpanel'), $blogname);
        }
        $user = get_user_by('id', $user_id);
        $search = ['[[status]]', '[[user_login]]', '[[first_name]]', '[[last_name]]', '[[display_name]]'];
        $status = __('Unknown', 'mihanpanel');
        $status_class = '';
        switch($change_to)
        {
            case 'activate':
                $status = __('Active', 'mihanpanel');
                $status_class = 'active';
                break;
            case 'deactivate':
                $status = __('Inactive', 'mihanpanel');
                $status_class = 'deactive';
                break;
        }
        $status = '<span class="account_status '.esc_attr($status_class).'">' . $status . '</span>';
        $replace = [$status, $user->user_login, $user->first_name, $user->last_name, $user->dispay_name];
        $content = str_replace($search, $replace, $message);
        $style = '.account_status{padding: 5px 10px; border-radius: 5px; font-weight: bold}.account_status.active{background-color: #5ed85e; color: #165616}.account_status.deactive{background-color: #d86363; color: #710b0b}';
        $style = self::get_email_style($style);
        $content = $style . '<pre dir="auto">' . $content . '</pre>';
        $header = 'Content-Type: text/html';
        wp_mail($user->user_email, $subject, $content, $header);
    }
    static function change_wordpress_email_name($original_email_from)
    {
        return get_bloginfo('name');
    }
    static function change_wordpress_from_name($original_email_address)
    {
        return get_option('mp_send_emails_from');
    }
    static function disable_default_emails()
    {
        remove_action('register_new_user', 'wp_send_new_user_notifications');
        remove_action('edit_user_created_user', 'wp_send_new_user_notifications', 10, 2);
        add_action('register_new_user', [__CLASS__, 'mp_durne_send_notification']);
        add_action('edit_user_created_user', [__CLASS__, 'mp_durne_send_notification'], 10, 2);
    }
    static function mp_durne_send_notification($userId, $to = 'both')
    {
        if (empty($to) || $to == 'admin') {
            return;
        }
        wp_send_new_user_notifications($userId, 'user');
    }
}