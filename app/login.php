<?php

namespace mihanpanel\app;

class login
{
    static function init()
    {
        add_action('init', [__CLASS__, 'initRoute']);
        add_action('mwpl_before_update_db_version', [__CLASS__, 'handleFlushRewriteRulesAfterPluginUpdated']);
        add_action('mwpl_login_form_after_start_form', [__CLASS__, 'handleQueryStringStateMessages']);
        if (\mihanpanel\app\options::getLoginGuardStatus()) {
            \mihanpanel\app\login_guard::init();
        }
        if (options::get_mwpl_redirect_wp_login_tp_mwpl_login_status()) {
            add_action('init', [__CLASS__, 'redirectWpLoginToMihanPanelLoginUrl']);
            add_filter('login_url', [__CLASS__, 'filterLoginUrlValue'], 10, 3);
            
            add_filter('wp_redirect', [__CLASS__, 'redirectAdminRouteToLoginRoute'], 10, 2);
            remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
        }

        add_action('login_enqueue_scripts', [__CLASS__, 'enqueueLoginPageAssets']);
        add_action('mwpl_login_enqueue_assets_login_type_normal_login', [__CLASS__, 'enqueueNormalLoginFormAssets']);
        add_action('mwpl_login_enqueue_assets_login_type_register', [__CLASS__, 'enqueueRegisterFormAssets']);

        // check is active user register option
        if (\mihanpanel\app\options::get_user_can_register()) {
            add_action('mwpl_login_after_login_form_end', [__CLASS__, 'addRegisterFormContentToLoginForm']);
        }
        

        add_action('update_option_rwl_page', [__CLASS__, 'setTransientForFlushRewriteRulesAfterUpdateLoginSlugOption'], 10, 2);
        add_action('init', [__CLASS__, 'handleFlushRewriteRulesAfterUpdateLoginSlugOption']);

        add_action('before_render_login_page_content', [__CLASS__, 'redirectIfUserIsLoggedIn']);
    }
    static function getLoginSlug()
    {
        return \mihanpanel\app\options::get_login_slug();
    }
    static function redirectAdminRouteToLoginRoute($location, $status)
    {
        if (strpos($location, 'wp-login.php') !== false) {
            // redirect to new login route
            $location = str_replace('wp-login.php', self::getLoginSlug(), $location);
        }
        return $location;
    }
    static function redirectWpLoginToMihanPanelLoginUrl()
    {
        global $pagenow;
        if (strpos($pagenow, 'wp-login.php') !== false) {
            $newLocation = options::get_login_url();
            if (isset($_GET) && $_GET) {
                if (isset($_GET['action']) && $_GET['action'] == 'logout') {
                    return;
                }
                $newLocation = esc_url(add_query_arg($_GET, $newLocation));
            }
            wp_safe_redirect($newLocation);
            exit;
        }
    }
    static function filterLoginUrlValue($login_url, $redirect, $force_reauth)
    {
        $loginSlug = self::getLoginSlug();
        return $redirect ? esc_url(add_query_arg(['redirect_to' => $redirect], site_url($loginSlug))) : site_url($loginSlug);
    }
    static function initRoute()
    {
        add_rewrite_rule(
            '^' . self::getLoginSlug() . '$',
            'index.php?mwpl_login=1',
            'top'
        );

        add_filter('query_vars', [__CLASS__, 'filterQueryVars']);
        add_action('parse_request', [__CLASS__, 'parseRequest']);
    }

    static function handleFlushRewriteRulesAfterPluginUpdated($oldDbVersion)
    {
        if (version_compare($oldDbVersion, MW_MIHANPANEL_DB_VERSION, '<')) {
            set_transient('mwpl_must_flush_rewrite_rules', 10);
        }
    }
    static function setTransientForFlushRewriteRulesAfterUpdateLoginSlugOption($oldValue, $newValue)
    {
        if ($oldValue !== $newValue) {
            set_transient('mwpl_must_flush_rewrite_rules', 10);
        }
    }
    static function handleFlushRewriteRulesAfterUpdateLoginSlugOption()
    {
        if (get_transient('mwpl_must_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_transient('mwpl_must_flush_rewrite_rules');
        }
    }

    static function filterQueryVars($vars)
    {
        $vars[] = 'mwpl_login';
        return $vars;
    }

    static function parseRequest($query)
    {
        if (!isset($query->query_vars['mwpl_login'])) {
            return false;
        }
        do_action('before_render_login_page_content');
        $view = views::get('user.login');
        $view ? include_once $view : null;
        exit;
    }
    static function doLogin($userID, $remember = false, $redirectTo = false)
    {
        $user = get_user_by('id', $userID);
        if (!$user) {
            return false;
        }

        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, $remember);
        do_action('wp_login', $user->user_login, $user);
        do_action('mwpl_after_do_login', $user->user_login, $user);
        if ($redirectTo) {
            wp_safe_redirect($redirectTo);
            exit();
        }
    }
    static function getLoginType()
    {
        return apply_filters('mwpl_login_type_form', 'normal_login');
    }
    static function enqueueLoginPageAssets()
    {
        $loginCss = assets::get_css_url('user/login');
        $loginJs = assets::get_js_url('user/login');
        assets::load_fonts_assets('login');

        $version = tools::get_plugin_version();

        assets::enqueue_fontawesome();

        wp_enqueue_script('mwpl_assets', assets::get_js_url('assets'), ['jquery'], $version);

        wp_enqueue_style('mwpl_login', $loginCss, null, $version);
        wp_enqueue_script('mwpl_login', $loginJs, ['jquery'], $version, true);

        do_action('mwpl_login_enqueue_assets');
        do_action('mwpl_login_enqueue_assets_login_type_' . self::getLoginType());

        // handle options style
        $additionalStyle = '';
        if ($loginButtonBgColor = \mihanpanel\app\options::get_login_button_bg_color()) {
            $additionalStyle .= '.mwpl-login-body .mwpl-form-fields .mwpl-input-item input[type="submit"]{ background-color: ' . $loginButtonBgColor . '; border-color: ' . $loginButtonBgColor . '}';
        }

        if ($additionalStyle) {
            wp_add_inline_style('mwpl_login', $additionalStyle);
        }
    }
    static function enqueueNormalLoginFormAssets()
    {

        $version = tools::get_plugin_version();
        $normalLoginJs = assets::get_js_url('user/normal_login');
        wp_enqueue_script('mwpl_normal_login', $normalLoginJs, ['jquery', 'mwpl_login'], $version, true);

        $data = [
            'au' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mwpl_login_nonce'),
            'msg' => [
                'missing_username_field_value' => esc_html__('Please fill username field', 'mihanpanel'),
                'missing_password_field_value' => esc_html__('Please fill password field', 'mihanpanel'),
                'has_error_in_request' => esc_html__('Has error in request', 'mihanpanel'),
            ],
        ];
        wp_localize_script('mwpl_normal_login', 'mwpl_data', $data);
    }

    static function enqueueRegisterFormAssets()
    {
        $version = tools::get_plugin_version();

        $registerFormJs = assets::get_js_url('user/register');
        wp_enqueue_script('mwpl_register', $registerFormJs, ['jquery', 'mwpl_login'], $version, true);

        $data = [
            'au' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mwpl_login_nonce'),
            'msg' => [
                'missing_username_field' => esc_html__('Please fill username field', 'mihanpanel'),
                'missing_password_field' => esc_html__('Please fill password field', 'mihanpanel'),
                'has_error_in_request' => esc_html__('Has error in request', 'mihanpanel'),
            ],
        ];
        wp_localize_script('mwpl_register', 'mwpl_data_register', $data);
    }

    static function addRegisterFormContentToLoginForm()
    {
        self::enqueueRegisterFormAssets();
        $registerView = views::get('user.register_form');
        $registerView ? include_once $registerView : null;
    }
    static function renderLoginForm()
    {
        $loginType = self::getLoginType();
        $formView = views::get('user.' . $loginType . '_form');
        $formView = apply_filters('mwpl_login/login_form_view', $formView, $loginType);
        $formView ? include_once $formView : null;
    }
    static function addLogoBeforeTitle()
    {
        $loginLogo = \mihanpanel\app\options::getLoginPageLogo();
        if (!$loginLogo) {
            return false;
        }
?>
        <a href="<?php echo get_site_url() ?>" class="mwpl-login-logo">
            <img src="<?php echo $loginLogo ?>" alt="login-logo">
        </a>
    <?php
    }
    static function addTwoFactorAutenticationFieldToLogin()
    {
    ?>
        <div class="mwpl-input-item mwpl-2fa-field-wrapper mwpl-hide">
            <label for="otp_field">
                <span class="mwpl-icon">
                    <img src="<?php echo \mihanpanel\app\assets::get_image_url('password-icon', 'svg') ?>" alt="password-icon">
                </span>
                <span class="mwpl-field">
                    <input type="text" name="otp_field" id="otp_field">
                    <span class="mwpl-label-value"><?php _e('OTP Code', 'mihanpanel') ?></span>
                </span>
            </label>
        </div>
<?php
    }

    static function redirectIfUserIsLoggedIn()
    {
        if (is_user_logged_in()) {
            wp_safe_redirect(user_admin_url());
            exit;
        }
    }

    static function generateDynamicCode()
    {
        return random_int(100000, 999999);
    }

    static function getCodeTimeLimit()
    {
        return 2 * 60;
    }

    static function getNewDynamicCodeData()
    {
        $code = self::generateDynamicCode();
        $currentTime = strtotime('now');
        $limit = self::getCodeTimeLimit();
        $expirationTime = $currentTime + $limit;

        // store code
        $codeData['code'] = $code;
        $codeData['expiration'] = $expirationTime;
        return $codeData;
    }

    static function setUserDynamicCode($userID)
    {
        $codeData = self::getNewDynamicCodeData();
        if (!$codeData) {
            return false;
        }
        $codeData['user_id'] = $userID;
        $storeResult = session::store('mwpl_login_form_dynamic_code_data', $codeData);
        return $storeResult ? $codeData : false;
    }

    static function validateDynamicCode($code, $userID)
    {
        $codeData = session::get('mwpl_login_form_dynamic_code_data');
        if (!isset($codeData['code']) || (!isset($codeData['user_id']) || $codeData['user_id'] != $userID) || !isset($codeData['expiration'])) {
            return false;
        }

        // validate code
        if ($code != intval($codeData['code'])) {
            return false;
        }

        // validate expiration time
        $currentTime = strtotime('now');
        return $currentTime <= $codeData['expiration'];
    }
    static function handleQueryStringStateMessages()
    {
        $messages = [];
        // handle account activation status messages
        $activationStatus = isset($_GET['activation_status']) && $_GET['activation_status'] ? sanitize_text_field($_GET['activation_status']) : false;
        if($activationStatus)
        {
            if($activationStatus == 'completed')
            {
                $messages[] = [
                    'type' => 'success',
                    'message' => __('Your Account successfully activated! You can login with your account', 'mihanpanel'),
                ];
            }
        }

        // handle is account active or inactive
        $nonActiveAccountState = isset($_GET['action']) && $_GET['action'] == 'inactive_account';
        if($nonActiveAccountState)
        {
            $activation_account_mode = options::get_account_activation_type();
            if ($activation_account_mode === options::MANUAL_ACTIVATION_MODE)
            {
                $messages[] = [
                    'type' => 'error',
                    'message' => __('Your account is disable! Please wait for admin approval.', 'mihanpanel'),
                ];
            }else{
                $messages[] = [
                    'type' => 'error',
                    'message' => __('Your account is disable!', 'mihanpanel'),
                ];
            }
        }

        // handle register mode messages
        $registerMode = isset($_GET['mwpl_register_status']) && $_GET['mwpl_register_status'] ? sanitize_text_field($_GET['mwpl_register_status']) : false;
        if($registerMode)
        {
            switch($registerMode)
            {
                case options::AUTO_ACTIVATION_MODE:
                    $messages[] = [
                        'message' => __("Registration completed, You can login with your account.", 'mihanpanel'),
                        'type' => 'success',
                    ];
                break;
                case options::MANUAL_ACTIVATION_MODE:
                    $messages[] = [
                        'message' => __("Registration completed, Please wait for admin approval.", 'mihanpanel'),
                        'type' => 'success',
                    ];
                break;
                case options::LINK_ACTIVATION_MODE:
                    $messages[] = [
                        'message' => __("Please check your email for activation link.", 'mihanpanel'),
                        'type' => 'success',
                    ];
                break;
            }
        }

        if($messages && is_array($messages))
        {
            foreach($messages as $item)
            {
                ?>
                <span class="mwpl-notice-box mwpl-login-notice-<?php echo $item['type']?>"><?php echo $item['message']; ?></span>
                <?php
            }
        }
    }
}
