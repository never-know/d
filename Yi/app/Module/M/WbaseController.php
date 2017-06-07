<?php
namespace App\Module\M;

use Min\App;

class WbaseController extends \Min\Controller
{
	public function onConstruct($redirect = true)
	{
		$openid = session_get('openid');
		
		if (!isset($openid)) {
			$openid = $this->getOpenid();
		}
		
		if (empty($openid)) {
			exit('can not get openid');
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
			redirect($wx->getOauthRedirect($url, $state, 'snsapi_userinfo'));
			exit;
		} else {
			if (isset($_GET['state']) && $_GET['state'] == session_get('state')) {
				$r = $wx->getOauthAccessToken();
				$openid = $r['openid']??0;
			} else {
				$openid = 0;
			} 
			
			if (!empty($openid)) session_set('openid', $openid);
			return $openid;
		}
	}
 
	final public function getWX()
	{
		require VENDOR_PATH. '/Wx/WxBase.php';
		return new \WeBase();
	}
	
	final public function login($redirect = true)
	{
		$openid 	= session_get('openid');
		$logged 	= session_get('logged');
		
		if (!empty($logged)) {
			$user 	= session_get('user');
		} else {
			$result = $this->request('\\App\\Service\\Wuser::login', $openid);	// 登陆
			if (0 === $result['statusCode']) {
				session_set('logged', 1);
				$user = $result['body'];
				$this->initUser($user);
			}  
		}
		
		if (empty($user) || 3 != $user['subscribe']) {
			$url = HOME_PAGE. '/bind/qrcode.html';	 
		} elseif ($user['uid'] > 0) {
			return true;
		} elseif (!$redirect) {
			return false;
		} else {
			$url = HOME_PAGE. '/bind.html';	
		}
		
		$this->response(['statusCode' => '307' , 'redirect' => $url]);
	}
 
	final public function initUser($user)
	{ 
		if (!empty($user['uid'])) {
			session_set('UID', $user['uid']);
		}
		
		if (!empty($user['wxid'])) {
			session_set('wxid', $user['wxid']);
		} 
		session_set('user', $user);	 
	}
}