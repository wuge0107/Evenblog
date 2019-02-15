<?php

namespace Core;

//加载配置文件
class Conf
{
    
    public static $conf = array();
    /**
     * @param $name 
     * @param null $key
     * @return 如果有key则返回单个key的值 否则为整个数组
     */
    public static function get($name, $key = null)
    {
        if (!isset(self::$conf[$name])) {
            $path = APP_PATH . '/Config' . '/' . $name . '.php';
            if (!is_file($path)) {
                return null;
            }
            self::$conf[$name] = require $path;
        }
        $config = self::$conf[$name];
        if (is_null($key)) {
            return $config;
        }
        return isset($config[$key]) ? $config[$key] : null;
    }
}