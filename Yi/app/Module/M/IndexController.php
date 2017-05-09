<?php
namespace App\Module\M;

use Min\App;

class IndexController extends \Min\Controller
{
	public function index_get()
	{
		$this->layout([], 'layout_m');
	}
	
	public function test()
	{
		$this->response();
	}



}