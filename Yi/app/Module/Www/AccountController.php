<?php
namespace App\Module\Www;

use Min\App;

class AccountController extends \Min\Controller
{ 
	public function onConstruct() 
	{
		$this->checkLogin();
	}
	
	public function index_get()
	{
		if (PHP_SESSION_NONE === session_status()) {
			App::initSession(true);  
		} 
		$this->layout('type-login');
	}
	
	public function repwd_post()
	{		 
		$pwd 		= $_POST['pwd'];
		$newpwd 	= $_POST['newpwd'];
		$newpwd2 	= $_POST['newpwd2'];
		
		if (empty($newpwd) || empty($pwd) || empty($newpwd2) ) {	
			$this->error('密码不能为空', 30208);		
		}
		
		if ($newpwd != $newpwd2) {	
			$this->error('两次新密码不相同', 30208);		
		}

		//$result = $this->request('\\App\\Service\\Account::checkAccount', ['name' => $name], false, true);
		$account =  $this->request('\\App\\Service\\Account', null, null, false, true);
		$result  = 	$account->checkAccount(session_get('UID'), 'UID');

		if (0 === $result['statusCode']) {
			if (password_verify($pwd, $result['body']['pwd'])) {				
				 
				 
				$this->success(['message'=>'登陆成功']);
			} else {
				unset($result);
				$result['message'] = '账号密码错误';
			}	 
		} 
		
		$error_times = $this->loginErrorInc($name);
		$result['statusCode'] = ($error_times > 3) ? 30202 : 30201;
		$this->response($result);
	}

	private function loginErrorTimes($key)
	{	 
		$var1 = intval(session_get('loginerror'));
		$key = 'loginerror:'. $key;
		$cache = $this->cache('login');
		$var2 = $cache->get($key);
		if ($var2 == false) {
			$cache->set($key, 0, 7200);
		}
		if ( $var2 > $this->max_error_time ) {
			$this->error('账户已锁定，请2小时后再登录', 30207);
		}
		
		return max($var1, $var2);
	}
	
	private function loginErrorInc($key)
	{	
		$var1 =session_inrc('loginerror');
		$var2 = $this->cache('login')->incr('loginerror:'. $key);
		return max($var1, $var2);
	}
 
}