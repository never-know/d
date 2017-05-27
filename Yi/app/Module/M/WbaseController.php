<?php
namespace App\Module\M;

use Min\App;

class WbaseController extends \Min\Controller
{
	public function onConstruct()
	{
		$openid = session_get('openid');
		
		if (!isset($openid)) {
			$openid = $this->getOpenid();
		}
		
		if (empty($openid)) {
			exit('can not get openid');
		}
		
		$this->login(true);
		
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
			if (isset($_GET['state']) && $_GET['state'] == sesseion_set('state')) {
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
				$url = HOME_PAGE. '/qrcode.html';	// 未关注,跳转关注页
			} elseif (empty($user['uid'])) {
				$url = HOME_PAGE. '/bind.html';		// 未绑定手机,跳转绑定页
			} else {
				$url = HOME_PAGE;					// other redirect homepage
			}
			
			redirect($url);
			
			exit;
			
		} else {
			return false;
		}
	}
}