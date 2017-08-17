<?php
namespace App\Module\M;

use Min\App;

class UserController extends \App\Module\M\BaseController
{
	protected $cache_key = 'user';
	
	public function index_get()
	{
		$user_id 	= session_get('USER_ID');
		
		// 用户基本信息
		
		$result = $this->userinfo();

		$today 		= $this->request('\\App\\Service\\Balance::today', $user_id, self::EXITNONE, true);
		$balance 	= $this->request('\\App\\Service\\Balance::account', $user_id);
		
		if (1 == $balance['statusCode']) {
			foreach ($balance['body'] as &$value) {
				$value = $value/100;
			}
			session_set('user_balance', $balance['body']);
		}
		$result['today_salary'] 	= $today['body']['total']/100;
		$result['account_balance'] 	= $balance['body']['balance'];
		$result['show_bottom'] = 1;
		$result['no_back'] = 1;
		$result['meta'] = ['title' =>'用户主页'];
		$this->success($result);
	}
	  
	public function profile_get()
	{
		 
		$result = $this->userinfo();
		$wx = $this->getWx();
		$result['js'] 		= $wx->getJsSign(CURRENT_URL);
		$result['meta'] 	= ['title' =>'用户信息'];
		$result['no_back'] 	= 1;
		$this->success($result);
	}
	
	public function setting_get()
	{
		$result['meta'] = ['title' =>' '];
		$this->success($result);
	}
	
	
	public function nickname_get()
	{
		$result['meta'] = ['title' =>'修改昵称'];
		$this->success();
	
	
	}
	public function nickname_post()
	{
		$params				= [];
		
		$params['nickname'] = trim($_POST['nickname']);
		
		if (!validate('nickname', $params['nickname'])) {
			$this->error('格式错误', 20000);
		}
 
		$user = session_get('user');
		
		if ($user['nickname'] != $params['nickname']) {
			$params['user_id']	= $user['user_id'];
			$params['wx_id']	= $user['wx_id'];
			$params['phone']	= $user['phone'];
			$params['open_id']	= $user['open_id'];
			
			$result = $this->request('\\App\\Service\\Account::nickname', $params);	 
			
			$user['nickname'] = $params['nickname'];
			session_set('user', $user);
		}
		
		$this->success();
	}
	
	public function avater_post()
	{	
		$wx = $this->getWx();
		$img = $wx->getMedia($_POST['media_id']);
		
		$wx_id = session_get('wx_id');
		
		if (!empty($img)) {
			$path 	= get_avater($wx_id);	// wx_id
			if (make_dir(PUBLIC_PATH . $path)) {
				file_put_contents(PUBLIC_PATH . $path, $img);
				$result['headimgurl'] = ASSETS_URL . $path;
				$this->success($result);
			}
		} 
			
		$this->error('操作失败', 20000);
	}
	
	public function binded_get()
	{
		$result						= [];
		$result['statusCode'] 		= 30200;
		
		$result['message'] 			= '帐号已绑定手机号码';
		$result['body']['no_back'] 	= 1;
		$result['body']['phone'] 	= session_get('user')['phone'];
		$this->response($result);
	
	} 
	
	public function userinfo()
	{	
		$result = [];
		
		$user = session_get('user');
		 
		$result['headimgurl'] = get_avater($user['wx_id'], ASSETS_URL);
		watchdog($result['headimgurl']);
		if (empty($user['nickname'])) {
			$result['nickname'] = 'An_' .  substr(session_get('user_phone'), -4);
		} else {
			$result['nickname'] = $user['nickname'];
		}
		
		return $result;
	}
		
}