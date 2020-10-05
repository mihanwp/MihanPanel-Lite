<?php
namespace mwplite\app;
if(defined("ABSPATH") && !class_exists("mwpl_session"))
{
    class mwpl_session
    {
        private static $table_name = 'mihanpanel_sessions';
        private const SESSION_KEY_NAME = 'session_key';
        private const SESSION_VALUE_NAME = 'session_value';
        private const SESSION_EXPIRATION_NAME = 'session_expiration';
        private const SESSION_ID_NAME = 'mw_sessid';
        
        static function create_session_table()
        {
            // create table
            $db = self::get_db($table_name);
            $charset_collate = $db->get_charset_collate();
            $command = "CREATE TABLE $table_name (
                `session_key` varchar(32) NOT NULL,
                `session_value` longtext NOT NULL,
                `session_expiration` datetime NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY  (session_key),
                KEY session_expiration (session_expiration)
              ) ENGINE=InnoDB $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($command);        
        }
        static function clear_expired_session()
        {
            $db = self::get_db($table_name);
            $sql = "DELETE FROM {$table_name} WHERE DATE(NOW()) >= DATE(session_expiration)";
            $db->get_results($sql);
        }
        private static function get_db(&$table_name=false)
        {
            global $wpdb;
            $table_name = $wpdb->prefix . self::$table_name;
            return $wpdb;
        }
        private static function replace($session_key, $serialized_value, $expiration_time=false)
        {
            if(!$session_key)
            {
                return false;
            }
            if(!$expiration_time)
            {
                $expiration_time = date('Y-m-d H:i:s', strtotime('+1 day'));
            }
            $db = self::get_db($table_name);
            $replaced_row_id = $db->replace(
                $table_name,
                [
                    self::SESSION_KEY_NAME => $session_key,
                    self::SESSION_VALUE_NAME => $serialized_value,
                    self::SESSION_EXPIRATION_NAME => $expiration_time
                ],
                '%s'
            );
            return $replaced_row_id;
        }
        static function remove($session_key)
        {
            $db = self::get_db($table_name);
            $removed_id = $db->delete(
                $table_name,
                [
                    self::SESSION_KEY_NAME => $session_key
                ],
                [
                    '%s'
                ]
            );
            return $removed_id;
        }
        private static function get_value($session_key)
        {
            $db = self::get_db($table_name);
            $sql = "Select session_key, session_value from {$table_name} where session_key=%s";
            $res = $db->get_row($db->prepare($sql, $session_key));
            return $res;
        }
        private static function serialize_value($value)
        {
            return $value ? serialize($value) : false;
        }
        private static function deserialize_value($value)
        {
            return $value ? unserialize($value) : false;
        }
        private static function update_value($value, $session_key=false)
        {
            /**
             * current: get current value
             * add this value to old value
             * update session
             * if ${current} no exist: insert value
             */
            if(!$session_key)
            {
                $session_key = self::get_user_session_key();
            }
            $current = self::get_value($session_key);
            if($current)
            {
                // update
                $db_value = self::deserialize_value($current->session_value);
                if($db_value)
                {
                    // append data
                    $values = array_values($value);
                    $db_value[key($value)] = array_shift($values);
                }else{
                    // add data
                    $db_value = $value;
                }
                $db_value = self::serialize_value($db_value);
            }else{
                $db_value = self::serialize_value($value);
            }
            $res = self::replace($session_key, $db_value);
            return $res;
        }
        private static function set($value, $session_key=false)
        {
            if(!$session_key)
            {
                $session_key = self::get_user_session_key();
            }
            // use replace
            $value = self::serialize_value($value);
            $replaced_row_id = self::replace($session_key, $value);
            return $replaced_row_id;
        }
        static function generate_session_key()
        {
            $key = md5(microtime() . $_SERVER['REMOTE_ADDR']);
            return $key;
        }
        static function get_user_session_key()
        {
            return isset($_COOKIE[self::SESSION_ID_NAME]) && $_COOKIE[self::SESSION_ID_NAME] ? $_COOKIE[self::SESSION_ID_NAME] : false;
        }
        static function get_session_key()
        {
            /**
             * check is session key exist in user cookie
             */
            $session_key = self::get_user_session_key();
            /**
             * generate new session key if not exist in cookie
             * and store session key in user cookie
             */
            if(!$session_key)
            {
                $session_key = self::generate_session_key();
                $exprie_time = strtotime('+1 day'); // time() + (24*60*60); // 24 hours
                $domain = parse_url(get_option('siteurl'), PHP_URL_HOST);
                setcookie(self::SESSION_ID_NAME, $session_key, $exprie_time, '/', $domain, false, true);
            }
            return $session_key;
        }
        static function store($key, $value)
        {
            $session_key = self::get_session_key(); // get current session or generate new session
            $new_value[$key] = $value;
            $res = self::update_value($new_value, $session_key);
            return $res;
        }
        static function get($key=false)
        {
            $session_key = self::get_session_key();
            $data = self::get_value($session_key);
            $data = isset($data->session_value) ? self::deserialize_value($data->session_value) : false;
            if($key)
            {
                return isset($data[$key]) ? $data[$key] : false;            
            }
            return $data;
        }
        static function unset($key)
        {
            $data = self::get(); // all sessions data
            if(!isset($data[$key]))
            {
                return false;
            }
            unset($data[$key]);
            return self::set($data);
        }
    }
}