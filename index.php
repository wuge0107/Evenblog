<?php

if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    die('require PHP > 5.5.0 !');
}

require_once 'autoload.php';

/**
 * 设置时区
 */
date_default_timezone_set('PRC'); 
//目录分隔符 为兼容liunx系统
define('DS', DIRECTORY_SEPARATOR); 
define('APP_PATH', dirname(__FILE__));
//错误信息是否显示 开发模式开启 线上环境关闭
define('APP_DEBUG', true);
//控制器存放目录
define('APP_CONTROLLER_PATH', APP_PATH . '/Controllers' . DS);
//命名空间类前缀
define('NEWCONTROLLER_PATH', '\\Controllers\\');

//运行
Core\App::run();
// 后面写的代码也会被忽略掉！！！
