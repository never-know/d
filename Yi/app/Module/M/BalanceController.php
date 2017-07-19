<?php
namespace App\Module\M;

use Min\App;

class BalanceController extends \App\Module\M\BaseController
{
	public function index_get()
	{
		$result = $this->request('\\App\\Service\\Balance::account', session_get('USER_ID'));
		
		$result['body']['meta'] = ['title' =>'我的钱包'];
 
		$this->response($result);

	}
	
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
		$user_id = session_get('user_id');
		
		$result 	= $this->request('\\App\\Service\\Draw::allList', $user_id);
		$result['body']['meta'] = ['title' =>'提现记录'];
		$this->response($result);
	}
	
	public function records_get()
	{
		$user_id = session_get('user_id');
		$result 	= $this->request('\\App\\Service\\Balance::allList', $user_id);
		$result['body']['meta'] = ['title' =>'资金明细'];
		$this->response($result);
	}
	
	public function income_get()
	{
		$user = session_get('user');
		$params['user_id']			= $user['user_id'];
		$params['register_time']	= $user['register_time'];
		
		$result 	= $this->request('\\App\\Service\\Balance::incomeList', $params);
		 
		if ($result['body']['page']['current_page'] < 2) {		
			$balance 	= $this->request('\\App\\Service\\Balance::account', $params['user_id']);
			$result['body']['balance'] = $balance['body'];
		}
		
		$result['body']['meta'] = ['title' =>'收益历史'];
		
		$this->response($result);
	}
	
	public function daily_get()
	{
		$params['date'] = APP::getArgs();
		
		if ('today' == $params['date']) {
			$params['date'] = date('ymd');
			$date = '今日';
		} else {
			$params['date'] = intval($params['date']);
			$date = date('m月d日', strtotime('20'.$params['date']));
		}
		
		$params['user_id']	= session_get('USER_ID');
		
		$result 	= $this->request('\\App\\Service\\Balance::dailyList', $params);

		$result['body']['date'] = $date;
 
		$result['body']['meta'] = ['title' => $date.'收益纪录'];
		
		$this->response($result);
	}
}