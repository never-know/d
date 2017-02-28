<?php
namespace App\Module\Www;

use Min\App;

class IndexController extends \Min\Controller
{
	 

	public function index_get()
	{
		$result['meta'] = ['menu_active' => 'homepage', 'title' =>'首页'];
		$this->response($result);
	}
	
	public function test()
	{
		$this->response();
	}



}