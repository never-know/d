<?php
namespace App\Module\Www;

use Min\App;

class RegistController extends \Min\Controller
{
	public function index_get()
	{
		if (PHP_SESSION_NONE === session_status())) {
			App::initSession(true);  
		} 
		$this->layout('type-login');
	}
	
	public function send_post()
	{
		$phone 		= $_POST['phone'];
		$captcha 	= $_POST['code'];
		$this->check($phone, $captcha);	
		
		$this->response($this->request('\\Min\\Service\\Sms::send', ['phone' => $phone]));	
	}
	
	public function index_post()
	{
		$phone 		= $_POST['phone'];
		$captcha 	= $_POST['code'];
		$sms 		= $_POST['sms'];
		$pwd 		= $_POST['pwd'];
		$repwd 		= $_POST['repwd'];
		
		if ($pwd != $repwd) {
			$this->error(30203, '两次输入密码不相同');
		}
		$this->check($phone, $captcha);	
		
		$this->request('\\Min\\Service\\Sms::check', ['phone' => $phone, 'code' => $sms]);
		// 不要更改 regist_data 中 key 的顺序
		$regist_data = ['phone' => $phone, 'pwd' => $pwd, 'regtime' => $_SERVER['REQUEST_TIME'], 'regip'=> ip2long(ip_address())];
		
		$uid = $this->request('\\Min\\Service\\Account::addByPhone', $regist_data, false);
		if($uid > 1){
			$this->initUser($phone, $uid);
			$this->success('注册成功');
		}else{
			$this->error(301100, '注册失败');
		}
		
		
		 
					
	}
	
	private function check($phone, $code){

		if (true !== validate('phone', $phone)) {
			$this->error(100, '手机号码格式错误');
		}

		$code_result = $this->request('\\Min\\Captcha::checkCode', ['code'=>$code, 'type'=>'reg']);
		
		if (true !== $code_result ) {
			$this->error(30101, '验证码错误');
		}
		
		$exit_result = $this->request('\\Min\\Service\\Account::checkAccount', ['name'=>$phone, 'type'=>'phone']);
		 
		if (2 != $exit_result) {
			$this->error(100, '该手机号码已被注册');
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