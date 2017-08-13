<?php
namespace App\Module\M;

use Min\App;

class BindController extends \App\Module\M\BaseController
{
	public function onConstruct($redirect = 0)
	{
		$binded = parent::onConstruct(0);
	
		if ($binded) {
			$result['statusCode'] 		= 30200;
			$result['message'] 			= '帐号已绑定手机号码';
			$result['redirect'] 		= '/user/binded.html';
			$result['body']['phone'] 	= session_get('user_phone');
			$this->response($result);
		}	
	}
	
	public function index_get()
	{
		$result['no_back'] = 1;
		$result['meta'] = ['title' =>'绑定手机号码'];
		$this->success($result);
	}
	
	public function index_post()
	{
		$params 			= [];
		$params['init'] 	= 'bind';
		$params['phone'] 	= trim($_POST['phone']);
		$params['code'] 	= trim($_POST['code']);
		
		$this->check($params['phone']);
		
		$this->request('\\App\\Service\\Sms::check', $params, self::EXITALL);
		
		$user = session_get('user');
		
		$regist_data	= [
			'phone' 		=> $params['phone'], 
			'register_time' => $_SERVER['REQUEST_TIME'], 
			'register_ip'	=> ip_address(), 
			'wx_id' 		=> $user['wx_id'], 
			'open_id' 		=> $user['open_id'], 
			'balance_index' => $user['balance_index']
		];
		
		$user = $this->request('\\App\\Service\\Account::addUserByWx', $regist_data);
		
		if (1 == $user['statusCode']) {
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

		if (1 == $exit_result['statusCode']) {
			if (!empty($exit_result['body']['wx_id']) || 2 == $exit_result['body']['user_type']) {
				$this->error('该手机号码已被注册', 30205);
			}  
		}
		
		$this->request('\\App\\Service\\Sms::send', [ 0 => $phone, 'init' => 'bind'], self::EXITALL);	
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