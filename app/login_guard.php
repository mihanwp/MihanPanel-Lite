<?php

namespace mihanpanel\app;

class login_guard
{
    private const BLOCKED_IP_TABLE_NAME = 'mihanpanel_guard_blocked_ip';

    static function init()
    {
        // add inactive login mode content
        add_action('mwpl_login_form_after_start_form', [__CLASS__, 'addInactiveFormContent']);

        // filter login form classes
        add_filter('mwpl_login_form/form_classes', [__CLASS__, 'filterLoginFormClasses']);
        
        // check wrong password in normal login
        add_action('mwpl_login/normal_login/wrong_username', [__CLASS__, 'handleFailedAttemptsLoginProcessInLoginForm']);
        add_action('mwpl_login/normal_login/wrong_password', [__CLASS__, 'handleFailedAttemptsLoginProcessInLoginForm']);
        add_action('mwpl_login_start_process_response', [__CLASS__, 'checkFailedAttemptsCountInLoginProcess']);

        // delete failed attempts data after login
        add_action('mwpl_after_do_login', [__CLASS__, 'deleteFailedAttemptsDataAfterLogin']);
    }
    static function beforeUpdateDbVersion($oldVersion)
    {
        if($oldVersion < 12)
        {
            self::createDatabaseTables();
        }
    }
    static function createDatabaseTables()
    {
        // create login guard ip block list
        global $wpdb;
        $tableName = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();
        $command = "CREATE TABLE IF NOT EXISTS {$tableName} (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `ip_address` varchar(15) NOT NULL,
            `failed_attempts_count` int(11) NOT NULL,
            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
            `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY  (id),
            KEY `ip_address` (`ip_address`)
          ) ENGINE=InnoDB {$charset_collate}";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($command);
    }
    static function getBlockedIps()
    {
        global $wpdb;
        $tbl = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        $sql = "SELECT * FROM {$tbl}";
        return $wpdb->get_results($sql);
    }
    static function deleteIpItem($itemID)
    {
        global $wpdb;
        $tblName = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        return $wpdb->delete(
            $tblName,
            [
                'id' => $itemID,
            ]
        );
    }

    static function truncateIpListTable()
    {
        global $wpdb;
        $tblName = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        return $wpdb->query("TRUNCATE TABLE {$tblName}");
    }
    private static function getCurrentIpAddress()
    {
        // if user from the share internet   
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        }   
        //if user is from the proxy   
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }   
        //if user is from the remote address   
        else{   
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        } 
        return $ipAddress ? $ipAddress : false;
    }
    private static function getCurrentIpAddressData()
    {
        $ipAddress = self::getCurrentIpAddress();
        global $wpdb;
        $tbl = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        $sql = "SELECT * FROM {$tbl}
                WHERE ip_address=%s";

        $result = $wpdb->get_row($wpdb->prepare($sql, $ipAddress));
        return $result;
    }
    static function deleteCurrentIpAddressFailedAttemptsData()
    {
        $ipAddress = self::getCurrentIpAddress();
        global $wpdb;
        $tbl = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        return $wpdb->delete(
            $tbl,
            [
                'ip_address' => $ipAddress,
            ]
        );
    }
    private static function addNewFailedAttempt()
    {
        // get ip address
        $ipAddress = self::getCurrentIpAddress();
        
        // update redcord if exists and create if not exists
        global $wpdb;
        $guardTablName = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        $currentIpAddressData = self::getCurrentIpAddressData();
        if($currentIpAddressData)
        {
            // must update record
            $newFailedCountValue = $currentIpAddressData->failed_attempts_count + 1;
            return $wpdb->update(
                $guardTablName,
                [
                    'failed_attempts_count' => $newFailedCountValue,
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'ip_address' => $ipAddress,
                ]
            );
        }else{
            // must create new record
            return $wpdb->insert(
                $guardTablName,
                [
                    'ip_address' => $ipAddress,
                    'failed_attempts_count' => 1,
                ],
            );
        }
    }
    static function restartCurrentIpAddressFailedAttemptsData()
    {
        $ipAddress = self::getCurrentIpAddress();
        global $wpdb;
        $tbl = $wpdb->prefix . self::BLOCKED_IP_TABLE_NAME;
        return $wpdb->update(
            $tbl,
            [
                'failed_attempts_count' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ip_address' => $ipAddress,
            ]
        );
    }

    static function deleteFailedAttemptsDataAfterLogin()
    {
        self::deleteCurrentIpAddressFailedAttemptsData();
    }

    static function hasPermissionForLogin()
    {
        $failedAttemptsData = self::getCurrentIpAddressData();
        if(!$failedAttemptsData)
        {
            return true;
        }
        $failedAttemptsCount = isset($failedAttemptsData->failed_attempts_count) ? $failedAttemptsData->failed_attempts_count : 0;
        $availableFailedAttemptsCount = \mihanpanel\app\options::getLoginGuardFailedAttemptCount();
        $permission = $failedAttemptsCount < $availableFailedAttemptsCount;

        if(!$permission)
        {
            // cehck first failed attempts
            $lastTimeFailedAttempts = isset($failedAttemptsData->updated_at) ? strtotime($failedAttemptsData->updated_at) : false;
            $unblockTimerMinutes = options::getLoginGuardUnblockTimerMinutesValue();
            
            if($lastTimeFailedAttempts && $lastTimeFailedAttempts + ($unblockTimerMinutes * 60) < strtotime('now') )
            {
                // restart failed attempts data
                self::restartCurrentIpAddressFailedAttemptsData();
                $permission = true;
            }
        }

        return $permission;
    }

    static function addInactiveFormContent()
    {
        if(!self::hasPermissionForLogin())
        {
            $styles = [
                'z-index: 100',
                'background-color: #ffffffd4',
                'width: 100%',
                'left: 0',
                'height: 100%',
                'display: flex',
                'justify-content: center',
                'align-items: center',
                'font-size: 16px',
                'color: #b74e4e',
            ];
            $styles = implode('; ', $styles);
            ?>
            <div class="mwpl_login_inactive_mode_content" style="<?php echo $styles?>">
                <span><?php _e('You are unable to log in due to too many incorrect attempts', 'mihanpanel')?></span>
            </div>
            <?php
        }
    }

    static function filterLoginFormClasses($classes)
    {
        if(!self::hasPermissionForLogin())
        {
            $classes[] = 'mwpl-inactive-mode';
        }
        return $classes;
    }
    
    static function handleFailedAttemptsLoginProcessInLoginForm()
    {
        self::addNewFailedAttempt();
    }

    static function checkFailedAttemptsCountInLoginProcess($response)
    {
        if(!self::hasPermissionForLogin())
        {
            $response['msg'] = __('You are unable to log in due to too many incorrect attempts', 'mihanpanel');
            $response['code'] = 409;
            tools::send_json_response($response);
        }
    }
}
