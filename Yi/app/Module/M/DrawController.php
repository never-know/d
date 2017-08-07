<?php
namespace App\Module\M;

use Min\App;

class DrawController extends \App\Module\M\BaseController
{
	 
	public function withdraw_get()
	{
		$user_id = session_get('user_id');
		
		$result 	= $this->request('\\App\\Service\\Draw::account', $user_id);
 
		$result['meta'] = ['title' =>'提现申请'];
		
		$this->success($result);
	}
	
	public function withdraw_post()
	{
		$params = [];
		$params['user_id'] 		= session_get('USER_ID');
		$params['draw_account'] = intval($_POST['account']);
		$params['draw_money'] 	= intval($_POST['money']);
		$params['draw_time'] 	= $_SERVER['REQUEST_TIME'];	 
		
		$result = $this->request('\\App\\Service\\Draw::reocrd', $params);
		$this->success($result);
	}
	
	public function wdlog_get()
	{
		$user_id = session_get('USER_ID');
		
		$result 	= $this->request('\\App\\Service\\Draw::allList', $user_id);
		$result['body']['meta'] = ['title' =>'提现记录'];
		$this->response($result);
	}
	
	public function detail_get()
	{
		$user_id = session_get('USER_ID');
		$result 	= $this->request('\\App\\Service\\Balance::allList', $user_id);
		$result['body']['meta'] = ['title' =>'资金明细'];
		$this->response($result);
	}

}