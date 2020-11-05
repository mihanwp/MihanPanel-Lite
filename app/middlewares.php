<?php
namespace mihanpanel\app;
class middlewares
{
    private static function handle($middlewares)
    {
        if(!$middlewares || !is_array($middlewares))
        {
            return true;
        }
        foreach($middlewares as $middleware)
        {
            if(!$middleware)
            {
                continue;
            }
            $class = '';
            $method = '';
            $reverse_mode = false;
            if(is_array($middleware))
            {
                $class = $middleware[0];
                $method = $middleware[1];

                // check for reverse mode
                if(isset($middleware[2]))
                {
                    $reverse_mode = true;
                }
            }else{
                list($class, $method) = explode('::', $middleware);
            }
            if(strpos($class, '\\') !== 0)
            {
                $class = '\\' . $class;
            }
            if(class_exists($class) && method_exists($class, $method))
            {
                $res = call_user_func([$class, $method]);
                if($reverse_mode && $res)
                {
                    return false;
                }
                if(!$res && !$reverse_mode)
                {
                    return false;
                }
            }
        }
        return true;
    }
    static function handle_middleware($name)
    {
        $hook_name = 'mwpl_middlewares/' . $name;
        $middlewares_method = apply_filters($hook_name, []);
        return self::handle($middlewares_method);
    }
    static function handle_tabs_new_record_middleware($methods)
    {
        $methods[] = [__CLASS__, 'handle_method_tabs_new_record_lite_limit'];
        return $methods;
    }
    static function handle_method_tabs_new_record_lite_limit()
    {
        $tabs_count = panel::get_tabs_count();
        if(!defined('MIHANPANEL_PRO_DIR_PATH') && $tabs_count > 3)
        {
            $pro_version = sprintf('<a target="_blank" href="%s">%s</a>',tools::get_pro_version_link(), __('Pro Version', 'mihanpanel'));
            echo '<p class="alert error"><span>'.sprintf(__('Max item count is 4 in lite version. Upgrade to %s for disable this restriction.', 'mihanpanel'), $pro_version) . '</span></p>';
            return false;
        }
        return true;
    }
}