<?php
namespace App\Module\M;

use Min\App;

class ContentController extends \App\Module\M\BaseController
{	
	public function onConstruct($redirect = 2)
	{
		parent::onConstruct(2);
		
		require CONF_PATH .'/keypairs.php';
	}

	public function __call($method, $param)
	{
		$id = \str2int(substr($method, 0, -4));
		
		if(!$id) {
			$this->error('参数错误', 1);
		}
		
		$result = $this->request('\\App\\Service\\Article::detail', $id);
		
		if (1 == $result['statusCode']) {
			$result['body']['meta'] = ['menu_active' => 'article_list', 'title' => $result['body']['title'], 'description' => $result['body']['desc']];
			$result['body']['share'] =  \shareid($id);
		}
		
		$this->response($result);
		
	}
 
	/*
		content_id
		share_time
		user_id
		type
	
	*/
	
	public function share_get()
	{
		$share_id = $_POST['share_id'];
		$params = shareid_decode($share_id);
		
		if ($params['user_id'] != session_get('USER_ID')) {
			$this->error('参数错误', 111111);
		}

		$params['share_id']		= $share_id;
		$params['share_time']	= $_SERVER['REQUEST_TIME'];
		
		$this->request('\\App\\Service\\Share::share', $params, self::EXITALL);
	}

}