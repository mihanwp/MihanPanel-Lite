<?php
namespace mihanpanel\app;
use PragmaRX\Google2FA\Google2FA;

class google_otp
{
    static function generateSecretKey()
    {
        $google2fa = new Google2FA();
        return $google2fa->generateSecretKey();
    }
    static function renderQrcode($userID=null)
    {
        $userID = $userID ? $userID : get_current_user_id();
        
        if(!$userID)
        {
            return false;
        }
        $secretKey = \mihanpanel\app\users::get2faSecretKey($userID);
        $companyName = 'MihanWP';
        $user = get_user_by('id', $userID);
        $userEmail = $user->user_email;
        
        $urlData = urlencode("otpauth://totp/{$companyName}:{$userEmail}?secret={$secretKey}&issuer={$companyName}");
        // Generate the QR code URL
        $qrCodeUrl = 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . $urlData;
        ?>

        <img src="<?php echo $qrCodeUrl; ?> "/>
        
        <?php
    }

    static function verifyOtpCode($code, $userID = null)
    {
        $userID = $userID ? $userID : get_current_user_id();
        if(!$userID)
        {
            return false;
        }
        $google2fa = new Google2FA();
        $userSecretKey = \mihanpanel\app\users::get2faSecretKey($userID);
        return $google2fa->verifyKey($userSecretKey, $code);
    }
    static function loadLoginAssets()
    {
        assets::enqueue_script('google_2fa', assets::get_js_url('google_2fa'));
    }

    static function loginAuthenticateValidation($user, $username, $password)
    {
        if(\mihanpanel\app\options::get_smart_login_2fa_status() && isset($_POST['mwpl_google_2fa_field']) && \mihanpanel\app\users::isActive2FA($user->ID))
        {
            $otpCode = $_POST['mwpl_google_2fa_field'] ? sanitize_text_field($_POST['mwpl_google_2fa_field']) : false;
            $verification = self::verifyOtpCode($otpCode, $user->ID);
            if(!$verification)
            {
                return new \WP_Error('mwpl_google_otp', __('Invalid OTP code', 'mihanpanel'));
            }
        }
        return $user;
    }
}