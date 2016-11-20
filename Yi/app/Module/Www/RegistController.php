<?php
namespace App\Module\Www;

use Min\App;

class RegistController extends \App\Module\BaseController
{
	public function __construct($action) 
	{	
		if ($action == 'index') {
			$this->index();
		} elseif ($action=='send') {
			$this->send();
		}
		exit;
	}

	private function index()
	{
		if(!isset($_SESSION)){
			App::initSession(true);  
		} 
		layout('type-login');
	}
	
	private function send(){
	
		$phone 		= $_POST['phone'];
		$captcha 	= $_POST['code'];
		
		if (true === $this->check($phone, $captcha)) {
			
			$sms = new \Min\Sms('reg');	
			
			$sc = $sms->get($phone);
					
			if (false == $sc || 120 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
				$code = mt_rand(111111,999999);
				$result = $sms->send(['code'=>$code, 'phone'=>$phone ]);
				if (isset($result->code)) {
					if ($result->code == 15) {
						response(0, '发送失败，每个号码每小时最多发送7次');
					}
					response(0,'发送失败，请重试');
				} else {
					$regmsm = ['code' => $code, 'ctime' => $_SERVER['REQUEST_TIME']];
					$sms->set($phone, $regmsm);
					//迁移以前记录
					//if(isset($sc['code']))	$sms->move($phone,$sc);
					response(1, '发送成功'); 
				}
			} else {
				response(0, '请稍等，验证码已发送');
			}			
		} else {
			response(0, '请稍等，验证码已发送');
		}
	}
	
	private function check($phone, $code){

		if (true !== validate('phone', $phone)) {
			response(100, '手机号码格式错误');
		}

		$code_result = $this->request('\\Min\\Service\\Captcha::checkCode', ['code'=>$code, 'type'=>'reg']);
		
		if (true !== $code_result ) {
			response(0, '验证码错误');
		}
		
		$result = $this->request('\\Min\\Service\\Account::checkAccount', ['name'=>$phone, 'type'=>'phone']);
		 
		if (2 === $result) {
			return true;
		} elseif (1 === $result) {
			response(100, '该手机号码已被注册');
		}else{
			App::getService('logger')->log('Unknow Workflow', 'NOTICE', debug_backtrace(), 'Exception');
		}
		
		return false;	 
	}
   

}