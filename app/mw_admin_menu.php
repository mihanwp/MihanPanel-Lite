<?php
namespace mwplite\app;

use mwplite\app\form\mw_admin_user_fields;

class mw_admin_menu
{
    public static function init()
    {
        self::mw_settings();
        self::social_login();
        self::user_fields();
    }

    public static function mw_settings()
    {
        $title = __('MihanPanel', 'mihanpanel');
        $mw_settings_page = add_menu_page($title, $title, 'manage_options', 'mihanpanel', __CLASS__ . '::settings_c', MW_MIHANPANEL_LITE_URL . 'img/mp-menu.png');
        add_action("load-{$mw_settings_page}", ['mwplite\app\mw_assets', 'load_admin_panel_assets']);
    }

    public static function settings_c()
    {
        $menu_items = [
            'general' => __('Login & Register', 'mihanpanel'),
            'panel' => __('User Panel', 'mihanpanel'),
            'register' => __('Registeration Form', 'mihanpanel'),
            'notification' => __('Notifications', 'mihanpanel'),
            'login_with_sms' =>  __("Login with SMS", "mihanpanel"),
            'email' => __('Emails', 'mihanpanel'),
        ];
        $settings = mw_views::get('admin.settings');
        include $settings;
    }
    static function user_fields()
    {
        $user_fields_menu_title = __('User Fields', 'mihanpanel');
        add_submenu_page('mihanpanel', $user_fields_menu_title, $user_fields_menu_title, 'manage_options', 'mihanpanel_fields', [__CLASS__, 'user_fields_c']);
    }
    static function user_fields_c()
    {
        if(!empty($_POST))
        {
            \mwplite\app\form\mw_admin_user_fields::do($_POST);
        }
        ?>
        <div class="mihanpanel-admin">
            <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                <p>
                    <span><?php _e('Priorities was changed! Save changes?', 'mihanpanel')?></span>
                    <span><input class="mw_submit mw_update_priority" data-mw_type="user_field" type="submit" name="save_priority" value="<?php _e('Yes', 'mihanpanel')?>"></span>
                </p>
            </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <?php mw_admin_user_fields::render_user_fields(); ?>
            <h2><?php _e('Create new field', 'mihanpanel'); ?></h2>
            <form method="post">
                <?php mw_admin_user_fields::render_new_record_fields()?>
            </form>
        </div>
        <?php
    }
    static function social_login()
    {
        $social_login_menu_title = __('Social Login', 'mihanpanel');
        add_submenu_page( 'mihanpanel', $social_login_menu_title, $social_login_menu_title,'manage_options', 'mihanpanel_social', [__CLASS__, 'social_login_c'] );
    }
    static function social_login_c()
    {
        ?>
        <div class="mihanpanel-admin">
            <p><?php _e('For using Social login just use "Nextend Social Login" plugin with Mihan Panel plugin.', 'mihanpanel')?></p>
          <a href="../wp-admin/plugin-install.php?tab=plugin-information&plugin=nextend-facebook-connect&TB_iframe=true&width=772&height=642"><?php _e('Click for installing', 'mihanpanel')?></a>
        </div>
        <?php
    }
    static function tabs()
    {
        $mw_menus_management_title = __('Panel Menus', 'mihanpanel');
        $mw_menus_management = add_submenu_page('mihanpanel', $mw_menus_management_title, $mw_menus_management_title, 'manage_options', 'mihanpanel_menus', [__CLASS__, 'tabs_c']);
        add_action("load-{$mw_menus_management}", ['mwplite\app\mw_assets', 'load_menus_management_assets']);
    }
    static function tabs_c()
    {
        if (!empty($_POST)) {
            global $wpdb;
            $tablename = $wpdb->prefix . 'mihanpaneltabs';
            //if save
            if (isset($_POST['save'])) {
                $link = filter_var($_POST['link_or_content'], FILTER_VALIDATE_URL);
                $link = $link ? $link : "";
                $content = $link ? "" : sanitize_text_field($_POST['link_or_content']);
                $success = $wpdb->update(
                    $tablename,
                    [
                        'name' => $_POST['name'],
                        'link' => $link,
                        'content' => $content,
                        'icon' => $_POST['icon']
                    ],
                    array('ID' => $_POST['id'])
                );
                if ($success) {
                    echo '<p class="alert success">'.__("Successfully updated!", "mihanpanel").'</p>';
                } else {
                    echo '<p class="alert error">'.__("No any data saved!", "mihanpanel").'</p>';
                }
            }
            // if add
            if (isset($_POST['add'])) {
                $count = mw_panel::get_tabs_count();
                if($count > 4)
                {
                    $pro_version = sprintf('<a target="_blank" href="%s">%s</a>',mw_tools::get_pro_version_link(), __('Pro version', 'mihanpanel'));
                    echo '<p class="alert error"><span>'.sprintf(__('Max item count is 4 in lite version. Upgrade to %s for disable this restriction.', 'mihanpanel'), $pro_version) . '</span></p>';
                }elseif (isset($_POST['name'])) {
                    $menucontent = str_replace('"', "'", $_POST['content']);
                    $success = $wpdb->insert(
                        $tablename,
                        array(
                            'name' => $_POST['name'],
                            'link' => $_POST['link'],
                            'content' => $menucontent,
                            'icon' => $_POST['icon'],
                        )
                    );
                    if ($success) {
                        echo '<p class="alert success">'.__("Successfully added! Watch user panel").'</p>';
                    } else {
                        echo '<p class="alert error">'.__("An error occurred!", "mihanpanel").'</p>';
                    }
                }
            }
            // if delete
            if (isset($_POST['delete'])) {
                $success = $wpdb->delete(
                    $tablename,
                    array(
                        'id' => $_POST['id'],
                    )
                );
                if ($success) {
                    echo '<p class="alert success">'.__("Successfully deleted!", "mihanpanel").'</p>';
                } else {
                    echo '<p class="alert error">'.__("An error occurred!", "mihanpanel").'</p>';
                }
            }
        }
        ?>
        <div class="mihanpanel-admin">
            <div class="mw_update_sortable_notice notice inline notice-info notice-alt">
                <p>
                    <span><?php _e('Priorities was changed! Save changes?', 'mihanpanel')?></span>
                    <span><input class="mw_submit mw_update_priority" data-mw_type="tabs" type="submit" name="save_priority" value="<?php _e("yes", "mihanpanel")?>"></span>
                </p>
            </div>
            <div class="mw_notice_box notice inline notice-alt"></div>
            <table class="mihanmenustable">
                <thead>
                    <tr>
                    <th></th>
                    <th><?php _e("Menu title", "mihanpanel")?></th>
                    <th><?php _e("Link / Shortcode", "mihanpanel")?></th>
                    <th><span><?php _e("Icon", "mihanpanel"); ?> </span><a target="_blank" href="https://fontawesome.com/icons?d=gallery&m=free"><?php _e("List", "mihanpanel")?></a></th>
                    <th></th>
                </tr>
                </thead>
                <script>
                var whichInput = 0;
                jQuery(document).ready(function ($) {
                  <?php
                  global $wpdb;
                  $tablename = $wpdb->prefix . 'mihanpaneltabs';
                  $menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
                  foreach ($menus as $menu) { ?>
                  $('.fontawesomepicker-<?php echo $menu->id; ?>').iconpicker(".fontawesomepicker-<?php echo $menu->id; ?>");
                  <?php } ?>
                });
                </script>
                <tbody class="mw_sortable">
                <?php
                global $wpdb;
                $tablename = $wpdb->prefix . 'mihanpaneltabs';
                $menus = $wpdb->get_results("SELECT * FROM $tablename ORDER BY priority ASC");
                foreach ($menus as $menu) {
                    $link_or_content = $menu->link ? $menu->link : $menu->content;
                    ?>
                    <form method="post" id="mpnav-form-<?php echo $menu->id; ?>">
                        <tr class="mihanpanelmenulist">
                            <td><span class="mw_icon mw_sort_icon dashicons dashicons-menu"></span></td>
                            <td style="display:none"><input name="id" value="<?php echo $menu->id; ?>"/></td>
                            <td><input name="name" value="<?php echo $menu->name; ?>"/></td>
                            <td><input type="text" name="link_or_content" value="<?php echo $link_or_content; ?>"></td>
                            <td><input class="fontawesomepicker-<?php echo $menu->id; ?>" name="icon" value="<?php echo $menu->icon; ?>"/></td>
                            <th><input type="submit" name="save" value="<?php _e("Save", "mihanpanel")?>"/></th>
                            <th><input class="mihanpanelremove" type="submit" name="delete" value="x"/></th>
                        </tr>
                    </form>
                <?php } ?>
                </tbody>
            </table>
            <h2><?php _e("Create new item", "mihanpanel")?></h2>
            <table class="mihanmenustable">
                <form method="post">
                    <tr>
                        <th><?php _e("Menu Title", "mihanpanel")?></th>
                        <th><?php _e("Content", "mihanpanel")?></th>
                        <th><span><?php _e("Icon", "mihanpanel")?> </span><a target="_blank" href="https://fontawesome.com/icons?d=gallery&m=free"><?php _e("List", "mihanpanel")?></a></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><input name="name" placeholder="<?php _e("Menu Title", "mihanpanel")?>"/></td>
                        <td>
                            <select id="linkorcontent" onchange="yesnoCheck();">
                                <option><?php _e("Choose", "mihanpanel"); ?></option>
                                <option value="optionlink"><?php _e("Link to other page", "mihanpanel")?></option>
                                <option value="optioncontent"><?php _e("Shortcode", "mihanpanel"); ?></option>
                            </select>
                            <input class="hiddenoutput" id="islink" style="direction:ltr;text-align:left" name="link"
                                   placeholder="http://"/>
                            <select class="hiddenoutput" id="iscontent" name="content">
                            <?php
                            global $shortcode_tags;
                            $shortcodes = $shortcode_tags;
                            ksort( $shortcodes );
                            ?>
                              <?php foreach( $shortcodes as $code => $function ) { ?>
                                <option value="[<?php echo $code; ?>]"><?php echo $code; ?></option>
                            <?php } ?>
                            </select>
                            <script>
                                function yesnoCheck() {
                                    if (document.getElementById("linkorcontent").value == "optionlink") {
                                        document.getElementById("islink").style.display = "block";
                                    } else {
                                        document.getElementById("islink").style.display = "none";
                                    }
                                    if (document.getElementById("linkorcontent").value == "optioncontent") {
                                        document.getElementById("iscontent").style.display = "block";
                                    } else {
                                        document.getElementById("iscontent").style.display = "none";
                                    }
                                }
                            </script>
                        </td>
                        <td><input class="mw-new-tab-record-icon" name="icon" placeholder="<?php _e("Icon", "mihanpanel"); ?>"/></td>
                        <td><input type="submit" name="add" value="+"/></td>
                    </tr>
                </form>
            </table>
        </div>
        <?php
    }
}