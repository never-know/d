<?php
namespace App\Module\M;

use Min\App;

class UserController extends \App\Module\M\BaseController
{
	protected $cache_key = 'user';
	
	public function index_get()
	{
		$user_id 	= session_get('USER_ID');
		
		// 用户基本信息
		
		$cache 		= $this->cache();
		$key 		= $this->getCacheKey('userinfo', $user_id);
		$result 	= $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
			$open_id 	= session_get('open_id');
			$wx 		= $this->getWX();
			$result 	= $wx->getUserInfo($open_id);
			if (!empty($result['openid'])) {
				$cache->set($key, $result, 86400);
			} else {
				$result = [];
			}
		}
		
		if (empty($result['headimgurl'])) {
			$result['headimgurl'] = '/public/images/avater.jpg';
		}
		
		if (empty($result['nickname'])) {
			$result['nickname'] = '用户' .  substr(session_get('user')['phone'], -4);
		}
		
		$today 		= $this->request('\\App\\Service\\Balance::today', $user_id);
		$balance 	= $this->request('\\App\\Service\\Balance::account', $user_id);
		
		session_set('user_balance', $balance);
		
		$result['today_balace'] 	= $today['body']['total'];
		$result['account_balace'] 	= $balance['body']['balance'];
		$result['show_bottom'] = 1;
		$result['meta'] = ['title' =>'用户主页'];
		$this->success($result);
	}
	  
	public function profile_get()
	{
		$user_id 	= session_get('USER_ID');
		
		// 用户基本信息
		$cache 		= $this->cache();
		$key 		= $this->getCacheKey('userinfo', $user_id);
		$result 	= $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
			$open_id 	= session_get('open_id');
			$wx 		= $this->getWX();
			$result 	= $wx->getUserInfo($open_id);
			if (!empty($result['openid'])) {
				$cache->set($key, $result, 86400);
			} else {
				$result = [];
			}
		}
		
		if (empty($result['headimgurl'])) {
			$result['headimgurl'] = '/public/images/avater.jpg';
		}
		
		if (empty($result['nickname'])) {
			$result['nickname'] = '用户' .  substr(session_get('user')['phone'], -4);
		}
		
		$result['meta'] = ['title' =>'用户信息'];
		$this->success($result);
	}
	
	public function setting_get()
	{
		$result['meta'] = ['title' =>' '];
		$this->success($result);
	}
	
	public function account_get()
	{
	
	}
	
	public function account_post()
	{
	
	}
		
}