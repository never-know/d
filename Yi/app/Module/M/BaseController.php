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
			watchdog('can not get opend id ', 'wx_openid_error', 'ERROR');			
			exit('openid error');
		}
		
		return $this->login($redirect);  
	}
	
	final public function getOpenid()
	{
		$wx = $this->getWx();
		
		if (empty($_GET['code'])) {
			$state = mt_rand(10000, 99999);
			session_set('state', $state);
			redirect($wx->getOauthRedirect(CURRENT_URL, $state, 'snsapi_base'));
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
 
	final public function getWx($type= 'anyitime')
	{
		return new \Vendor\Wx\WxBase($type);
	}
	
	/*
		params: 
		
		2 1 0
	
	*/
	
	final public function login($redirect = 1)
	{
		$open_id 	= session_get('open_id');
		$logged 	= session_get('logged');
		
		if (!empty($logged)) {
			$user 	= session_get('user');
		} else {
			
			$result = $this->request('\\App\\Service\\Wuser::login', $open_id);	// 登陆
			if (1 == $result['statusCode']) {
				$user = $result['body'];
				$this->initUser($user);
				
			} elseif (30206 == $result['statusCode'] && 2 === $redirect ) {
				$user						= [];
				$user['wx_ip']				= ip_address();
				$user['parent_id']			= 0;
				$user['open_id']			= $open_id;
				$user['subscribe_time'] 	= $_SERVER['REQUEST_TIME'];
				$user['subscribe_status']	= 2;

				$result = $this->request('\\App\\Service\\Wuser::addUserByOpenid', $user);
				if (!empty($result['body']['id'])) {
					$this->initUser(['wx_id' => $result['body']['id']]);
				}
			}	
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
			session_set('logged', 1);
		}
		
		if (!empty($user['wx_id'])) {
			session_set('wx_id', $user['wx_id']);
		}
		
		if (!empty($user['phone'])) {
			session_set('user_phone', $user['phone']);
		}
		 
		session_set('user', $user);	 
	}

}