<?php
namespace App\Module\M;

use Min\App;

class IndexController extends \App\Module\M\BaseController
{
	public function index_get()
	{
		$param['region'] 		= intval($_REQUEST['region']??0);
		$param['sub_region'] 	= $_REQUEST['sub_region']??'';
		$result = $this->request('\\App\\Service\\Article::list', $param);
		$result['body']['meta'] = ['menu_active' => 1, 'title' =>' '];
		$result['body']['show_bottom'] = 1;
 
		$this->response($result);
	}
	
	 
}