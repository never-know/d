<?php
namespace App\Module\M;

use Min\App;

class UserController extends \App\Module\M\WbaseController
{
	public function index_get()
	{
		$result['show_bottom'] = 1;
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function profile_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function grid_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function line_get()
	{
		$result['show_bottom'] = 1;
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function line2_get()
	{
		$result['show_bottom'] = 1;
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}

}