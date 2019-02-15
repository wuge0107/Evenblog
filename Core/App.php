<?php

namespace Core;

use Core\Route as route;

class App
{
	
	public static function registerDebug()
	{
		/**
		 * [$whoops 注册错误信息]
		 * @var [type]
		 */
		include APP_PATH . "/vendor/autoload.php";
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
		$whoops->register();
	}

	public static function run()
	{
		if(APP_DEBUG === true){
			ini_set('display_errors', 'On');
			self::registerDebug();
		}else{
			ini_set('display_errors', 'Off');
			error_reporting(E_ALL | E_STRICT);
		}
		//路由初始化
		$ctrlClass = route::init();
		$controllerName = $ctrlClass->controller;
		$actionName = $ctrlClass->action;

		//加载文件实例方法
		$ctrlfile =  APP_CONTROLLER_PATH . '/' . ucfirst($controllerName) . '.php';
		//类名
		$ctrlClass = NEWCONTROLLER_PATH . ucfirst($controllerName);
		//文件是否存在
		if (is_file($ctrlfile)) {
		    require_once $ctrlfile;
		    //判断类是否存在
		    if (class_exists($ctrlClass)) {
		        $ctrl = new $ctrlClass();
		    } else {
		        echo Json::jsonp_encode_ex(['code'=>404,'msg'=>'文件不存在']);
		        exit();
		    }
		    //类中的方法是否存在
		    if (method_exists($ctrl, $actionName)) {
		        $ctrl->$actionName();
		        exit();
		    } else {
		        echo Json::jsonp_encode_ex(['code'=>404,'msg'=>'方法不存在']);
		        exit();
		    }
		} else {
		    echo Json::jsonp_encode_ex(['code'=>404,'msg'=>'文件不存在']);
		    die;
		}
	}
}