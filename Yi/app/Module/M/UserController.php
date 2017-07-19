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
 
		$today 		= $this->request('\\App\\Service\\Balance::today', $user_id);
		$balance 	= $this->request('\\App\\Service\\Balance::account', $user_id);
		
		session_set('user_balance', $balance);
		
		$result['today_balace'] 	= $today['body']['total'];
		$result['account_balace'] 	= $balance['body']['balance'];
		$result['show_bottom'] = 1;
		$result['meta'] = ['title' =>'用户主页'];
		$this->success($result);
	}
	  
	public function profile_get()
	{
		 
		$result = $this->userinfo();
		
		$result['meta'] = ['title' =>'用户信息'];
		$this->success($result);
	}
	
	public function setting_get()
	{
		$result['meta'] = ['title' =>' '];
		$this->success($result);
	}
	
	public function account_get()
	{
	
	}
	
	public function account_post()
	{
	
	}
		
}