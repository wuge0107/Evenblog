<?php

//公共控制器

namespace Controllers;
use Core\Json;
use Core\Input;

class Api_Controller
{
	public function __construct()
	{
		/**
		 * [$this->input]
		 * @var Input
		 */
		$this->input = new Input();
	}

	/**
	 * [response JSON返回]
	 * @Author   Even
	 * @DateTime 2018-12-24T16:37:22+0800
	 * @param    array                    $data [description]
	 * @return   [type]                         [description]
	 */
	public function response($data = [])
	{
		Json::jsonp_encode_ex($data);
		exit();
	}

}