<?php

namespace Core;

//接收参数类

class Input
{
	/**
	 * [get 接收get参数]
	 * @return   [type] [description]
	 */
	public function get($getParameterName = null)
	{
		return self::export('GET',$getParameterName);
	}

	/**
	 * [post 接收post参数]
	 * @return   [type] [description]
	 */
	public function post($postParameterName = null)
	{
		return self::export('POST',$postParameterName);
	}

	//处理参数
	public function export($type = "GET",$paramName = null)
	{
		$data = [];

		if($type == 'GET'){
			$data = $_GET;
			$i = 0;
			foreach ($data as $key => $value) {
				if($i == 0 && substr($key,0,1) == '/'){
					unset($data[$key]);
				}
				$i++;
			}
		}
		if($type == 'POST')
		{
			$data = $_POST;
		}
		if (empty($paramName) || is_null($paramName)) {
	        $result_arr = [];
	        foreach ($data as $key => $value) {
	            $value = htmlspecialchars(addslashes($value));
	            //是否纯数字
	            if(ctype_digit($value)){
	            	$result_arr[$key] = (int) $value;
	            	continue;
	            }
	            if(is_float($value)){
	            	$result_arr[$key] = (float) $value;
	            	continue;
	            }
	            $result_arr[$key] = $value;
	        }
	        if(empty($result_arr)){
		    	return null;
		    }
		    return $result_arr;
	    }
	    $result = isset($data[$paramName]) ? $data[$paramName] : null;
	   	if(is_null($result)) return null;
	  	if(is_numeric($result)) return (int) $result;
	  	if(is_float($result)) return (float) $result;
	  	if(is_string($result)) return $result;
	}
}