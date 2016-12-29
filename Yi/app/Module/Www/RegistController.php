<?php
namespace App\Module\Www;

use Min\App;

class RegistController extends \Min\Controller
{
	public function index_get()
	{
		if (PHP_SESSION_NONE === session_status()) {
			App::initSession(true);  
		} 
		$this->layout('type-login');
	}
	
	public function send_post()
	{
		$phone 		= $_POST['phone'];
		$captcha 	= $_POST['code'];
		$this->check($phone, $captcha, '1');	
		
		$this->response($this->request('\\App\\Service\\Sms::send', ['init' => 'reg', 'phone' => $phone]));	
	}
	
	public function index_post()
	{
		$phone 		= $_POST['phone'];
		$captcha 	= $_POST['code'];
		$sms 		= $_POST['sms'];
		$pwd 		= $_POST['pwd'];
		$repwd 		= $_POST['repwd'];
		
		if ($pwd != $repwd) {
			$this->error('两次输入密码不相同', 30203);
		}
		$this->check($phone, $captcha, '2');	
		
		$this->request('\\App\\Service\\Sms::check', ['phone' => $phone, 'code' => $sms]);

		$regist_data = ['phone' => $phone, 'pwd' => $pwd, 'regtime' => $_SERVER['REQUEST_TIME'], 'regip'=> ip_address()];
		
		$regist_result = $this->request('\\App\\Service\\Account::addUserByPhone', $regist_data, false);
		if ($regist_result['uid'] > 1) {
			$this->initUser($phone, $regist_result['uid']);
			$this->success('注册成功');
		} else {
			$this->error('注册失败', 30204);
		}
			
	}
	
	private function check($phone, $code, $type){
		
		
		if (1 !== validate('phone', $phone)) {
			$this->error('手机号码格式错误', 30120);
		}
		$captcha = new \Min\Captcha;
		if (true !== $captcha->checkCode($code, $type)) {
			$this->error('图片验证码错误', 30102);
		}
	
		$exit_result = $this->request('\\App\\Service\\Account::checkAccount', ['name'=>$phone, 'type'=>'phone']);

		if (0 === $exit_result['code']) {
			$this->error('该手机号码已被注册', 30205);
		} 
	}
	
	private function initUser($name, $uid){
	
		if($uid > 0) {
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			setcookie('nickname', $name, 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 10, '/', COOKIE_DOMAIN);
			$_SESSION['logined'] = true;
			$_SESSION['UID'] = $uid;
		}
	}

   

}