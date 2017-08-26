<?php
namespace App\Module\Www;

use Min\App;

class BaseController extends \Min\Controller
{
	public function onConstruct($redirect = 1)
	{ 
		$user_id = session_get('WWW_USER_ID');
		
		if (!isset($user_id)) {
			redirect(HOME_PAGE . '/login.html');
		} 
	}
	/*
	protected function initUser($user)
	{ 
		if($user['user_id'] > 0) {
			
			session_regenerate_id();// 每次登陆都需要更换session id ;
			//if (!empty($user['nick'])) setcookie('nick', $user['nick'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			//setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			session_set('wlogined', 1);
			session_set('WWW_USER_ID', $user['user_id']);
			session_set('www_user', $user);
		}
	}
	*/
	final public function initUser($user)
	{ 
		if (!empty($user['user_id'])) {
			session_regenerate_id();
			session_set('WWW_USER_ID', $user['user_id']);
			session_set('www_logged', 1);
		}
		
		if (!empty($user['wx_id'])) {
			session_set('www_wx_id', $user['wx_id']);
		}
		
		if (!empty($user['phone'])) {
			session_set('www_user_phone', $user['phone']);
		}
		
		$user['nickname'] = $user['nickname'] ?? ('An_' .  substr($user['phone'], -4));
		
		session_set('www_nickname', $user['nickname']);
		session_set('www_user', $user);	 
	}
}