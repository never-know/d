<?php
namespace App\Module\M;

use Min\App;

class NewsController extends \App\Module\M\WbaseController
{
	public function onConstruct()
	{
		$openid = session_get('openid');
		if (!isset($openid)) {
			$this->getOpenid();
		}
	}
	
	public function index_get()
	{
		$id = intval($_GET['id']);
		if($id < 1) {
			$this->error('商品不存在', 1000);
		}
		
		$openid = session_get('openid');
 
		if (!empty($openid)) {
			$user = $this->request('\\App\\Service\\Wuser::addUserByOpenid', ['openid' => $openid, 'subscribe' => 2]);
			$this->initUser($user);
		}

		$news = $this->request('\\App\\Service\\News::detail', $id);
		 
		$this->success();
	}
	
}