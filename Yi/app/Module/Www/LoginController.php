<?php
namespace App\Module\Www;

use Min\App;

class LoginController extends \Min\Controller
{
	public function index_get()
	{
		if (PHP_SESSION_NONE === session_status()) {
			App::initSession(true);  
		} 
		$this->layout('type-login');
	}
	
	public function index_post()
	{
		if (empty($_POST['name']) || empty($_POST['pwd'])) {	
			$this->error('账号密码不能为空', 30208);		
		}

		if( $this->loginErrorTimes($_POST['name']) > 3){
			if (empty($_POST['captcha'])) $this->error('请输入图片验证码', 30103);
			
			$code_result = $this->request('\\Min\\Captcha::checkCode', ['code'=>$_POST['captcha'], 'type'=>'login']);
			if (true !== $code_result ) {
				$this->error('图片验证码错误', 30102);
			}	
		}

		$result = $this->request('\\Min\\Service\\Account::checkAccount', ['name' => $_POST['name'] ]);	
		
		if (0 === $result['code']) {	
			if (password_verify($_POST['pwd'], $result['pwd'])) {				
					if (isset($_SESSION['loginerror'])) unset($_SESSION['loginerror']);
					$this->initUser($result);	
					$this->success(['message'=>'登陆成功']);
			} else {	
				$result['message'] = '账号密码错误';
				unset($result['body']);
				$error_times = $this->loginErrorInc($arr['name']);
				$result['code'] = ($error_times > 3) ? 30202 : 30201;
			}
		}
		
		$this->response($result);
	}
	
	private function initUser($user){
	
		if($user['id'] > 0) {
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			setcookie('nickname', $user['nickname'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 10, '/', COOKIE_DOMAIN);
			$_SESSION['logined'] = true;
			$_SESSION['UID'] = $user['id'];
			$_SESSION['USER'] = $user;

		}
	}
	
	private function loginErrorTimes($key)
	{	 
		$var = intval(CM('loginerror')->get(md5($key)));		 
		if( $var > 9 ){
			$this->error('账户已锁定，请2小时后再登录', 30207);
		}
		return $var;
	}
	
	private function loginErrorInc($key)
	{
		CM('loginerror')->incr(md5($key));
	}
 
}