<?php
namespace App\Module\M;

use Min\App;

class IndexController extends \App\Module\M\BaseController
{
	public function index_get()
	{	
  
		if (!empty($_GET['region'])) {				// 使用GET 
			$param['region'] 		=  intval($_GET['region']);
			$param['sub_region']	= $_GET['sub_region'];
			
		
		} elseif (!empty($_COOKIE['region'])) {	// 使用COOKIE 
			$param['region'] 		=  intval($_COOKIE['region']);
			$param['sub_region']	= $_COOKIE['sub_region'];
			
		} elseif (!empty($_REQUEST['lat']) &&  !empty($_REQUEST['lng'])) {	// 使用经纬度  
			$param['region'] 		=   110101;
			$param['sub_region']	=  '';
		
		} else {   // 使用默认 
			 
			//$result = $this->cache('user_actions')->get('region_select_' . session_get('USER_ID'), true);

			$param['region'] 	 =  110101;
			$param['sub_region'] =  '';
		}		
 
		$result = $this->request('\\App\\Service\\Article::list', $param);
		$result['body']['meta'] = ['menu_active' => 1, 'title' =>'首页'];
		$result['body']['show_bottom'] = 1;
		$result['body']['no_back'] = 1;
		
		/*
		if (!empty($_COOKIE['subregion_title'])) {
			$result['body']['params']['sub_region_name'] = $_COOKIE['subregion_title']?:'';
			$result['body']['params']['region_name'] = $_COOKIE['region_title']?:'';
		}
		
		if (!empty($_COOKIE['selected_region'])) {
			$result['body']['params']['selected_region'] = $_COOKIE['selected_region'];
			$result['body']['params']['region'] = $param['region'];
		}
		
		$result['body']['params']['selected_subregion'] = $param['sub_region'] ;
		*/

		$this->response($result);
	}
	
	public function test_get()
	{	
	
		$_COOKIE['selected_region'] = '130000,130400,130404';
		$_COOKIE['selected_subregion'] = '130404002,130404003,130404004,130404005,130404006,130404007';
		$_COOKIE['subregion_title'] = '河北邯郸复兴河北邯郸复兴';
		$_COOKIE['region_title'] = '河北邯郸复兴';
		$_REQUEST['sub_region'] = '130404002,130404003,130404004,130404005,130404006,130404007';
		
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
		$result['body']['params']['selected_subregion'] = $param['sub_region'];
		$result['body']['template'] = '/m/index/index';
		$this->response($result);
	}
	
	
}