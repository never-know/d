<?php
namespace App\Module\M;

use Min\App;

class UserController extends \Min\Controller
{
	public function index_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function test()
	{
		$this->response();
	}



}