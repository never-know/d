<?php
namespace App\Module\M;

use Min\App;

class TestController extends \App\Module\M\TestBaseController
{

	public function homepage_get()
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



	public function img_get()
	{
	
		$a = '{"x":"0","y":"153.88957278829227","width":"360","height":"360","scaleX":"1","scaleY":"1","media_id":"dueS5sj_9-GUvl8knS0dVd-RwVertPSuIq-ZAtfFfJHcYtid9tgFiHn_028JIoZj","csrf_token":"U0Ltmv_E-55_CoOPGmykBq7S-4hdjGbCCZtaarqqCTE"}';
		$src = imagecreatefromstring(file_get_contents(PUBLIC_PATH . '/images/00.jpg'));
		$b = json_decode($a, true);	 
		$x = intval($b['x']);
		$y = intval($b['y']);
		//裁剪区域的宽和高
		$width = intval($b['width']);
		 
		//最终保存成图片的宽和高，和源要等比例，否则会变形
		$final_width = 100;
 
		//将裁剪区域复制到新图片上，并根据源和目标的宽高进行缩放或者拉升
		$new_image = imagecreatetruecolor($final_width, $final_width);
		
		
		imagecopyresampled($new_image, $src, 0, 0, $x, $y, $final_width, $final_width, $width, $width);
		//var_dump($new_image);
		
		imagepng($new_image, PUBLIC_PATH . '/images/12.png' );
	
	
	
	}

	public function shareid_get()
	{
		$j = 0;
		$error = [];

		$time= time();

		for($i=0;$i<1000;$i++) {

		$user_id  = mt_rand(1, 100000000);
		session_set('USER_ID', $user_id);
		$content_id = mt_rand(1, 100000000);
		$share_type = mt_rand(0,1);
		$_SERVER['REQUEST_TIME'] = mt_rand(1499929697,2099929697) ;

		$id = shareid_encode($content_id);
		 

		$result1 = shareid_decode($id['timeline']);
		$result2 = shareid_decode($id['friend']);

		 

		if ($result1['content_id'] != $content_id || $result1['user_id'] != $user_id || $result1['share_time'] != $_SERVER['REQUEST_TIME'] || $result2['content_id'] != $content_id || $result2['user_id'] != $user_id || $result2['share_time'] != $_SERVER['REQUEST_TIME']) {
			$j++;
			//$error[$j] = [$content_id, $share_type, $user_id, $_SERVER['REQUEST_TIME']];
		}


		}
		echo time()-$time,'<br>';

		var_dump($j);
		 exit;
				
	}
	
	
	public function index_get()
	{
		var_dump(http_get('https://m.anyitime.com/public/images/abc1.png'));
		exit;
		
	}
	public function popup_get()
	{
		$this->success([], null);
		
	}
	public function redis_get()
	{
		 $cache  =  $this->cache();
		 $result = $cache->set('name1', 'yangbiao2', 300);
		 var_dump($result);
		 exit;
		
	}
	
	public function getredis_get()
	{
		 $cache  =  $this->cache();
		 $result = $cache->get('name');
		 var_dump($result);
		 exit;
		
	}
	
	public function ondumpkeytest_get()
	{
			$user = $this->request('\\App\\Service\\Wuser::test');
			exit;
	
	}
	
	public function bulidt_get()
	{
	
		$user = $this->request('\\App\\Service\\Admin::bulid_table');
		exit;

	}
	
	public function phpinfo_get()
	{
		phpinfo();
		//$user = $this->request('\\App\\Service\\Admin::bulid_table');
		exit;

	}
	
	public function password_get()
	{
		echo password_hash('123456', PASSWORD_BCRYPT, ['cost' => 9]);
		exit;
	
	}
	public function bottom_get()
	{
		$id = $_GET['id'];
		$this->success('', '/test/'.$id);
	}	
	 

}