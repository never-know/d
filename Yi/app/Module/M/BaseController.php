<?php
namespace App\Module\M;

use Min\App;

class BaseController extends \Min\Controller
{
	public function onConstruct($redirect = 1)
	{ 
		$open_id = session_get('open_id');
		
		if (!isset($open_id)) {
			$open_id = $this->getOpenid();
		}
		
		if (empty($open_id)) {
			wacthdog('can not get opend id ', 'wx_openid_error', 'ERROR');			
			exit('');
		}
		
		return $this->login($redirect);  
	}
	
	final public function getOpenid()
	{
		$wx = $this->getWX();
		
		if (empty($_GET['code'])) {
			$url = 'https://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$state = mt_rand(10000, 99999);
			session_set('state', $state);
			redirect($wx->getOauthRedirect($url, $state, 'snsapi_base'));
			exit;
		} else {
			if (isset($_GET['state']) && $_GET['state'] == session_get('state')) {
				$r = $wx->getOauthAccessToken();
				$open_id = $r['openid']??0;
			} else {
				$open_id = 0;
			} 
			
			if (!empty($open_id)) session_set('open_id', $open_id);
			return $open_id;
		}
	}
 
	final public function getWX($type= 'anyitime')
	{
		require VENDOR_PATH. '/Wx/WxBase.php';
		return new \WeBase($type);
	}
	
	/*
		params: 
		
		2 1 0
	
	*/
	
	final public function login($redirect = 1)
	{
		$open_id 	= session_get('open_id');
		$logged 	= session_get('logged');
		
		if (empty($logged)) {
			
			$result = $this->request('\\App\\Service\\Wuser::login', $open_id);	// 登陆
			if (1 == $result['statusCode']) {
				session_set('logged', 1);
				$user = $result['body'];
				$this->initUser($user);
				
			} elseif (30206 == $result['statusCode'] && 2 === $redirect ) {
				$user						= [];
				$user['wx_ip']				= ip_address();
				$user['parent_id']			= 0;
				$user['open_id']			= $open_id;
				$user['subscribe_time'] 	= 1;
				$user['subscribe_status']	= 2;

				$result = $this->request('\\App\\Service\\Wuser::addUserByOpenid', $user);
				if (empty($result['body']['id'])) {
					unset($user);
				} else {
					$this->initUser(['wx_id' => $result['body']['id']]);
				}
			}
			
		} else {
			$user 	= session_get('user');
		}
		
		if (2 === $redirect) {
			return true;
		}
		
		if (empty($user) || 3 != $user['subscribe_status']) {
			$url = HOME_PAGE. '/subscribe.html';	 
		} elseif (!empty($user['user_id'])) {
			return true;
		} elseif (empty($redirect)) {
			return false;
		} else {
			$url = HOME_PAGE. '/bind.html';	
		}

		$this->response(['statusCode' => '307' , 'redirect' => $url]);
	}
 
	final public function initUser($user)
	{ 
		if (!empty($user['user_id'])) {
			session_set('USER_ID', $user['user_id']);
		}
		
		if (!empty($user['wx_id'])) {
			session_set('wx_id', $user['wx_id']);
		}
		 
		session_set('user', $user);	 
	}
}