<?php
namespace App\Module\M;

use Min\App;

class BindController extends \App\Module\M\WbaseController
{
	public function onConstruct($redirect = true)
	{
		$binded = parent::onConstruct(false);
		
		if ($binded) {
			$result['statusCode'] 		= 30200;
			$result['message'] 			= '帐号已绑定手机号码';
			$result['body']['template_path'] 	= '/bind/binded';
			$result['body']['phone'] 			= session_get('user')['phone'];
			$this->response($result);
		}	
	}
	
	public function index_get()
	{
		$result['meta'] = ['title' =>'绑定手机号码'];
		$this->success($result);
	}
	
	public function index_post()
	{
		$phone 		= trim($_POST['phone']);
		$smscode 	= trim($_POST['smscode']);
		
		$this->check($phone);
		
		$this->request('\\App\\Service\\Sms::check', ['phone' => $phone, 'smscode' => $smscode], 'bind');
		
		$register_data	= ['phone' => $phone, 'register_time' => $_SERVER['REQUEST_TIME'], 'register_ip'=> ip_address(), 'wx_id' => session_get('wx_id'), 'open_id' => session_get('open_id')];
		
		$user = $this->request('\\App\\Service\\Account::addUserByWx', $regist_data);
		
		if (0 === $user['statusCode']) {
			$this->initUser($user['body']);
			$this->success('绑定成功');
		} else {
			$this->error($user['message'], $user['statusCode']);
		}
	}
 
	public function send_post()
	{	
		$phone 		= trim($_POST['phone']);
		
		$this->check($phone);
		 
		$exit_result = $this->request('\\App\\Service\\Account::checkAccount', $phone);

		if (0 === $exit_result['statusCode']) {
			if (!empty($exit_result['body']['wx_id']) || 2 == $exit_result['body']['user_type']) {
				$this->error('该手机号码已被注册', 30205);
			}  
		}
		
		$this->request('\\App\\Service\\Sms::send', [ 0 => $phone, 'init' => 'bind'], $this::EXITALL);	
	}
	
	public function binded_get()
	{
		$this->error('微信号已绑定手机', 309);
	
	}
	
	
	private function check($phone)
	{	
		if (!validate('phone', $phone)) {
			$this->error('手机号码格式错误', 30120);
		}
	}

	/*************test ******************/
	
	
	public function fastclick_get()
	{
		$result['meta'] = ['title' =>'fastclick'];
		$this->success($result, null);
	}
	 
}