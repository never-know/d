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
		$captcha 	= $_POST['captcha'];
		$this->check($phone, $captcha, 'reg1');	
		
		$exit_result = $this->request('\\App\\Service\\Account::checkAccount', $phone);

		if (0 === $exit_result['statusCode']) {
			$this->error('该手机号码已被注册', 30205);
		} 
		
		$this->request('\\App\\Service\\Sms::send', [ 0 => $phone, 'init' => 'reg'], $this::EXITALL);	
	}
	
	public function index_post()
	{
		$phone 		= $_POST['phone'];
		$captcha 	= $_POST['captcha'];
		$smscode 	= $_POST['smscode'];
		$pwd 		= $_POST['pwd'];
		$repwd 		= $_POST['repwd'];
		
		if ($pwd != $repwd) {
			$this->error('两次输入密码不相同', 30203);
		}
		$this->check($phone, $captcha, 'reg2');	
		
		$this->request('\\App\\Service\\Sms::check', ['phone' => $phone, 'smscode' => $smscode], 'reg');

		$regist_data = ['phone' => $phone, 'pwd' => $pwd, 'regtime' => $_SERVER['REQUEST_TIME'], 'regip'=> ip_address()];
		
		$this->request('\\App\\Service\\Account::addUserByPhone', $regist_data, $this::EXITALL);
	
	}
	
	private function check($phone, $code, $type)
	{	
		if (1 !== validate('phone', $phone)) {
			$this->error('手机号码格式错误', 30120);
		}
		$captcha = new \Min\Captcha;
		if (true !== $captcha->checkCode($code, $type)) {
			$this->error('图片验证码错误', 30102);
		}
	}
	
}