<?php
namespace App\Module\M;

use Min\App;

class ContentController extends \App\Module\M\BaseController
{	
	public function onConstruct($redirect = 2)
	{
		parent::onConstruct(2);
	}

	public function details_get()
	{
		$params = explode('/', App::getArgs(), 2); // id36/share_no
		
		if(empty($params[0])) {
			$this->error('参数错误', 1);
		}
 
		$id 		= \str2int($params[0]);
		
		$share_id 	= App::getArgs();

		$result = $this->request('\\App\\Service\\Article::detail', $id);
		$result['body']['id'] = $params[0];
			/*
		if (1 == $result['statusCode']) {
			$result['body']['meta'] = ['title' => $result['body']['content_title'], 'description' => $result['body']['content_description']];
		
			if (empty($params[1]) && session_get('USER_ID') > 0) {
				$result['body']['share_nos']		=  \share_encode($id);
				foreach ($result['body']['share_nos'] as $key => $value) {
					$result['body']['share_url'][$key] 	=   SCHEMA . SERVER_NAME . '/content/' . $params[0] . ($value?('/'.$value):'') . '.html';
				}
				
				$wx = $this->getWx();
				$result['body']['js'] = $wx->getJsSign(CURRENT_URL);
			}
			
		}
		*/ 
		
		$this->response($result, '/content/details');	
	}
}