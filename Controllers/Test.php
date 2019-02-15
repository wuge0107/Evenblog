<?php

/**
 * @Author: even
 * @Date:   2018-12-24 13:58:35
 * @Last Modified by:   even
 * @Last Modified time: 2018-12-25 14:02:38
 */
namespace Controllers;

use Model\TestModel;
use Core\RedisDriver;
use Core\Session;
use Helper\Byte;

class Test extends Api_Controller
{
	protected $testmodel;

	public function __construct()
	{
		parent::__construct();
		$this->testmodel = new TestModel();

		$this->redis_model = new RedisDriver();
		$this->session = new Session();
	}

	public function index()
	{

		dump([0x12,0x34,0xB6,0x78,0x90,0x12,0x34,0x56]);
	}
}