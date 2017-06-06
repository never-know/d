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
	
	final public function login($redirect = false)
	{
		$openid 	= session_get('openid');
		$logged 	= session_get('logged');
		
		if (empty($logged)) {
			$result = $this->request('\\App\\Service\\Wuser::login', $openid);	// 登陆
			if (0 === $result['statusCode']) {
				session_set('logged', 1);
				$user = $result['body'];
				$this->initUser($user);
			}
		}
		
		$uid 		= session_get('UID');
		$wxid 		= session_get('wxid');

		//if (!empty($uid) && !empty($openid) && 3 == $user['subscribe']) {
		if ( $uid > 0 &&  $wxid > 0) {
			return true;
		} 
 
		if (!$redirect)  return false;
		
		if (3 != $user['subscribe']) {
			$url = HOME_PAGE. '/bind/qrcode.html';	// 未关注,跳转关注页
		} elseif (empty($user['uid'])) {
			$url = HOME_PAGE. '/bind.html';			// 未绑定手机,跳转绑定页
		} else {
			$url = HOME_PAGE;						// other redirect homepage
		}
		
		redirect($url);
		exit;
	}
 
	final protected function initUser($user)
	{ 
		session_regenerate_id();

		if (!empty($user['uid'])) {
			session_set('UID', $user['uid']);
		}
		
		if (!empty($user['wxid'])) {
			session_set('wxid', $user['wxid']);
		} 
		
		if (!empty($user['phone'])) {
			session_set('phone', $user['phone']);
		}
		//session_set('user', $user);	 
	}
}