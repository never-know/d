<?php
namespace App\Module\M;

use Min\App;

class UserController extends \App\Module\M\BaseController
{
	public function index_get()
	{
		$result['show_bottom'] = 1;
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->success($result);
	}
	  
	public function profile_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->success($result);
	}
	
	public function setting_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->success($result);
	}
		
}