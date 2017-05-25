<?php
namespace App\Module\M;

use Min\App;

class WbaseController extends \Min\Controller
{
	public function onConstruct()
	{
		$openid = session_get('openid');
		
		if (!isset($openid)) {
			$this->getOpenid();
		}
		
		if (empty($openid)) {
			redirect();
			exit;
		}
		
		$this->login(true);
		
	}
	
	final public function getOpenid()
	{
		$wx = $this->getWX();
		
		if (empty($_GET['code'])) {
			$url = 'https://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$state = mt_rand(10000, 99999);
			sesseion_set('state', $state);
			redirect($wx->getOauthRedirect($url, $state, 'snsapi_base'));
			exit;
		} else {
			if (isset($_GET['state']) && $_GET['state'] == sesseion_set('state')) {
				$r = $wx->getOauthAccessToken();
				$openid = $r['openid']??0;
			} else {
				$openid = 0;
			} 
			session_set('openid', $openid);
		}
	}
 
	final public function getWX()
	{
		require VENDOR_PATH. '/wx/WeBase.php';
		return new \WeBase();
	}
	
	final public function login($redirect = false)
	{
		$user 		= session_get('user');
		$openid 	= session_get('openid');
		
		if (empty($user)) {
			$this->request('\\App\\Service\\Wuser::login', $openid);	// 登陆
		}
		
		$user 		= session_get('user');
		 
		if (!empty($user['uid']) && !empty($user['openid']) && 3 == $user['subscribe']) {
			return true;
		} 
		
		if ($redirect) {
			if (3 != $user['subscribe']) {
				$url = 'https://m.anyitime.com/qrcode.html';	// 未关注,跳转关注页
			} elseif (empty($user['uid'])) {
				$url = 'https://m.anyitime.com/bind.html';	// 未绑定手机,跳转绑定页
			} else {
				$url = 'https://m.anyitime.com';			// other redirect homepage
			}
			
			redirect($url);
			
			exit;
			
		} else {
			return false;
		}
	}
}