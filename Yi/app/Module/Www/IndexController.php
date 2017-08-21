<?php
namespace App\Module\Www;

use Min\App;

class IndexController extends \App\Module\Www\BaseController
{
	public function index_get()
	{
		$result['meta'] = ['menu_active' => 'homepage', 'title' =>'首页'];
		$this->success($result);
	}
	
	public function test()
	{
		$this->response();
	}



}