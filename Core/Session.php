<?php

namespace Core;

/**
 * session 类
 */

class Session
{  
	public function __construct()
	{
		session_start();

	}
    /** 
     * 设置session 
     * @param String $name   session name 
     * @param Mixed  $data   session data 
     * @param Int    $expire 超时时间(秒) 
     */  
    public static function set($name, $data, $expire = 7200)
    {  

        $session_data = array();  
        $session_data['data'] = $data;  
        $session_data['expire'] = time()+$expire;  
        $_SESSION[$name] = $session_data;  
    }  
  
    /** 
     * 读取session 
     * @param  String $name  session name 
     * @return Mixed 
     */  
    public static function get($name)
    {  
        if(isset($_SESSION[$name])){  
            if($_SESSION[$name]['expire']>time()){  
                return $_SESSION[$name]['data'];  
            }else{  
                self::clear($name);  
            }  
        }  
        return false;  
    }  
  
    /** 
     * 清除session 
     * @param  String  $name  session name 
     */  
    public static function clear($name)
    {  
    	if(empty($name)){
    		session_destroy();
    	}else{
        	unset($_SESSION[$name]);  
    	}
    } 
}  

