<?php
namespace App\Module\Www;

use Min\App;

class LoginController extends \Min\Controller
{
	protected $max_error_time = 9;
	
	public function index_get()
	{
		if (PHP_SESSION_NONE === session_status()) {
			App::initSession(true);  
		} 
		$this->layout('type-login');
	}
	
	public function index_post()
	{	
		$name 	= $_POST['name'];
		$pwd 	= $_POST['pwd'];
		$code 	= $_POST['code'];
		if (empty($name) || empty($pwd)) {	
			$this->error('账号密码不能为空', 30208);		
		}

		if ($this->loginErrorTimes($name) > 2) {
		
			if (empty($code)) $this->error('请输入图片验证码', 30103);
			
			$captcha = new \Min\Captcha;
			if (true !== $captcha->checkCode($code, 'login1')) {
				$this->error('图片验证码错误', 30102);
			}
		}

		$result = $this->request('\\App\\Service\\Account::checkAccount', ['name' => $name], false, true);
		watchdog($result['body']['pwd']);
		if (0 === $result['code']) {
			if (password_verify($pwd, $result['body']['pwd'])) {				
				session_set('loginerror', null);
				$this->initUser($result['body']);	
				$this->success(['message'=>'登陆成功']);
			} else {
				unset($result);
				$result['message'] = '账号密码错误';
			}	 
		} 
		
		$error_times = $this->loginErrorInc($name);
		$result['code'] = ($error_times > 3) ? 30202 : 30201;
		$this->response($result);
	}
	
	private function initUser($user){
	
		if($user['id'] > 0) {
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			setcookie('nickname', $user['nickname'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			$_SESSION['logined'] = true;
			$_SESSION['UID'] = $user['uid'];
			$_SESSION['USER'] = $user;

		}
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