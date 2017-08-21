<?php
namespace App\Module\M;

use Min\App;

class IndexController extends \App\Module\M\BaseController
{
	public function index_get()
	{	
		if (!empty($_COOKIE['selected_region'])) {
			$param['region'] =  explode(',', $_COOKIE['selected_region'])[2];
		} else {
			$param['region'] = 0;
		}
		
		$param['sub_region'] 	= $_REQUEST['sub_region']??($_COOKIE['selected_subregion']??'');
		$result = $this->request('\\App\\Service\\Article::list', $param);
		$result['body']['meta'] = ['menu_active' => 1, 'title' =>'列表'];
		$result['body']['show_bottom'] = 1;
		$result['body']['no_back'] = 1;
		if (!empty($_COOKIE['subregion_title'])) {
			$result['body']['params']['sub_region_name'] = $_COOKIE['subregion_title']?:'';
			$result['body']['params']['region_name'] = $_COOKIE['region_title']?:'';
		}
		if (!empty($_COOKIE['selected_region'])) {
			$result['body']['params']['selected_region'] = $_COOKIE['selected_region'];
			$result['body']['params']['region'] = $param['region'];
		}
		$result['body']['params']['selected_subregion'] = $param['sub_region'] ;
		$this->response($result);
	}
}