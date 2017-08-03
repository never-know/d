<?php
namespace App\Module\M;

use Min\App;

class ContentController extends \App\Module\M\BaseController
{	
	public function onConstruct($redirect = 2)
	{
		parent::onConstruct(2);
	}

	public function __call($method, $param)
	{
		$id36 		= substr($method, 0, -4);
		$id 		= \str2int($id36);
		$share_id 	= App::getArgs();

		if(empty($id)) {
			$this->error('参数错误', 1);
		}
		
		$result = $this->request('\\App\\Service\\Article::detail', $id);
		
		if (1 == $result['statusCode']) {
			$result['body']['meta'] = ['title' => $result['body']['content_title'], 'description' => $result['body']['content_description']];
			if (empty($share_id) && session_get('USER_ID') > 0) {
				$result['body']['share_nos']		=  \share_encode($id);
				foreach ($result['body']['share_nos'] as $key => $value) {
					$result['body']['share_url'][$key] 	=   SCHEMA . SERVER_NAME . '/content/' . $id36 . ($value?('/'.$value):'') . '.html';
				}
				
				$wx = $this->getWx();
				$result['body']['js'] = $wx->getJsSign(CURRENT_URL);
			}
			 
		}
		
		$this->response($result, '/content/details');
		
	}
 
	/*
		content_id
		share_time
		user_id
		type
	*/
	
	public function share_post()
	{
		$share_no = $_POST['key'];
		$params = share_decode($share_no);
		
		if ($params['user_id'] != session_get('USER_ID')) {
			$this->error('参数错误', 111111);
		}

		$params['share_no']		= $share_no;
		$params['share_time']	= $_SERVER['REQUEST_TIME'];
		
		$this->request('\\App\\Service\\Share::share', $params, self::EXITALL);
	}

}