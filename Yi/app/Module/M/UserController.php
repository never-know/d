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
		
		$result = $this->userinfo();

		$today 		= $this->request('\\App\\Service\\Balance::today', $user_id, self::EXITNONE, true);
		$balance 	= $this->request('\\App\\Service\\Balance::account', $user_id);
		
		if (1 == $balance['statusCode']) {
			foreach ($balance['body'] as &$value) {
				$value = $value/100;
			}
			session_set('user_balance', $balance['body']);
		}
		$result['today_salary'] 	= $today['body']['total']/100;
		$result['account_balance'] 	= $balance['body']['balance'];
		$result['show_bottom'] = 1;
		$result['no_back'] = 1;
		$result['meta'] = ['title' =>'用户主页'];
		$this->success($result);
	}
	  
	public function profile_get()
	{
		 
		$result = $this->userinfo();
		
		$result['meta'] = ['title' =>'用户信息'];
		$this->success($result);
	}
	
	public function setting_get()
	{
		$result['meta'] = ['title' =>' '];
		$this->success($result);
	}
	
	public function nickname_post()
	{
		$params				= [];
		
		$params['nickname'] = trim($_POST['nickname']);
		if (!validate('nickname', $params['nickname'])) {
			$this->error('格式错误', 20000);
		}
		
		$params['user_id']	= session_get('USER_ID');
		
		$result = $this->request('\\App\\Service\\Account::nickname', $params);
		$result['redirect'] = '/user/profile.html';
		$this->success($result);
	}
	
	public function avater_post()
	{
		$params				= [];

		$params['user_id']	= session_get('USER_ID');
		
		$result = $this->request('\\App\\Service\\Account::avater', $params);
		$result['redirect'] = '/user/profile.html';
		$this->success($result);
	}
	
	public function binded_get()
	{
		$result						= [];
		$result['statusCode'] 		= 30200;
		
		$result['message'] 			= '帐号已绑定手机号码';
		$result['body']['no_back'] 	= 1;
		$result['body']['phone'] 	= session_get('user')['phone'];
		$this->response($result);
	
	} 
	
	public function userinfo()
	{	
		/*
		$wx_id		= session_get('wx_id');
		$cache 		= $this->cache('user');
		$key 		= $this->getCacheKey('userinfo', $wx_id);
		$result 	= $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
			$open_id 	= session_get('open_id');
			$wx 		= $this->getWx();
			$result 	= $wx->getUserInfo($open_id);
			
			if (!empty($result['openid'])) {
				 
				if (!empty($result['headimgurl'])) {
					$img = http_get(substr_replace($result['headimgurl'], '64', -1, 1));
					if (!empty($img)) {
						$path = '/avater/' . implode('/', str_split(base_convert($wx_id, 10, 36), 2)) . '.jpg';
						$result['img_path'] = ASSETS_URL . $path;
						file_put_contents(PUBLIC_PATH . $path, $img);
					}
				}
				 
				$cache->set($key, $result);

			} else {
				$result = [];
			}
		}
		*/
		
		$result = [];
		$user = session_get('user');
		if (!empty($user['avater'])) {
			$key = $user['user_id'] . 'anyitime6688';
			$result['headimgurl'] = (ASSETS_URL . '/avater' . hash_path(md5($key), 'fucome6688') . ['', '.png', '.jpg', '.jpeg'][$user['avater']]);
		} else {
			$result['headimgurl'] = '/public/images/avater.jpg';
		}
		if (empty($user['nickname'])) {
			$result['nickname'] = 'Anbaby' .  substr(session_get('user_phone'), -4);
		}
		return $result;
	}
		
}