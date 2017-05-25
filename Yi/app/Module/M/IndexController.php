<?php
namespace App\Module\M;

use Min\App;

class IndexController extends \App\Module\M\WbaseController
{
	public function index_get()
	{
		$result['meta'] = ['menu_active' => 1, 'title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	 
}