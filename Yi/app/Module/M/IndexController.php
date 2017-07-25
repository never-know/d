<?php
namespace App\Module\M;

use Min\App;

class IndexController extends \App\Module\M\BaseController
{
	public function index_get()
	{
		$result['meta'] = ['menu_active' => 1, 'title' =>' '];
		$result['show_bottom'] = 1;
		$this->success($result);
	}
	
	 
}