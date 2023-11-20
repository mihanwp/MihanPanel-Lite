<?php


function mwplLoginHeader()
{
    $title = 'Login';
    $login_title = get_bloginfo('name', 'display');

    /* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
    $login_title = sprintf(__('%1$s &lsaquo; %2$s &#8212; WordPress'), $title, $login_title);

    if (wp_is_recovery_mode()) {
        /* translators: %s: Login screen title. */
        $login_title = sprintf(__('Recovery Mode &#8212; %s'), $login_title);
    }

    /**
     * Filters the title tag content for login page.
     *
     * @since 4.9.0
     *
     * @param string $login_title The page title, with extra context added.
     * @param string $title       The original page title.
     */
    $login_title = apply_filters('login_title', $login_title, $title);

?>
    <!DOCTYPE html>
    <html <?php language_attributes() ?>>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <title><?php echo $login_title; ?></title>


        <?php
        wp_enqueue_style('login');

        /**
         * Enqueue scripts and styles for the login page.
         *
         * @since 3.1.0
         */
        do_action('login_enqueue_scripts');

        /**
         * Fires in the login page header after scripts are enqueued.
         *
         * @since 2.1.0
         */
        do_action('login_head');
        ?>
    </head>

    <?php
    $bodyClasses = apply_filters('mwpl_login_form_body_classes', []);
    $bodyClasses = implode(' ', $bodyClasses);
    ?>
    <body class="mwpl-login-body <?php echo is_rtl() ? 'mwpl-rtl' : '';?> <?php echo $bodyClasses; ?>">
    <?php
    /**
     * Fires in the login page header after the body tag is opened.
     *
     * @since 4.6.0
     */
    do_action('login_header');
}

function mwplLoginFooter()
{
    do_action('mp_login_footer');
    /**
     * Fires in the login page footer.
     *
     * @since 3.1.0
     */
    do_action('login_footer');
    ?>

    </body>
    </html>
<?php
}

function mwplLoginContent()
{
?>
    <div class="mwpl-login-wrapper">
        <?php \mihanpanel\app\login::renderLoginForm();?>
        <div class="mwpl-image-cover-wrapper">
            <?php
            if (\mihanpanel\app\tools::isProVersion()){
                do_action('mp_login_cover_wrapper');
            } else {
                echo '<img src="'. \mihanpanel\app\options::get_login_bg() .'" alt="login-bg">';
            }
            ?>
        </div>
    </div>
<?php
}

/**
 * Fires when the login form is initialized.
 *
 * @since 3.2.0
 */
do_action('login_init');


mwplLoginHeader();

mwplLoginContent();

mwplLoginFooter();
