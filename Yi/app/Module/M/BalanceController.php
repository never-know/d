<?php
namespace App\Module\M;

use Min\App;

class BalanceController extends \App\Module\M\BaseController
{
	public function index_get()
	{ 
		$result['meta'] = ['title' =>'我的钱包'];
		$result['balance'] = session_get('user_balance')['balance'];
 
		$this->success($result);

	}
 
	public function records_get()
	{
		$user_id = session_get('USER_ID');
		$result 	= $this->request('\\App\\Service\\Balance::allList', $user_id);
		
		foreach ($result['body']['list'] as &$value) {
			$value['balance_type'] 	= balance_type($value['balance_type']);
			$value['user_money'] 	= $value['user_money']/100;
			$value['user_current_balance'] = $value['user_current_balance']/100;
		}
		
		$result['body']['meta'] = ['title' =>'资金明细'];
		$this->response($result);
	}
	
	public function income_get()
	{
		$user = session_get('user');
		$params['user_id']			= $user['user_id'];
		$params['register_time']	= $user['register_time'];
		
		$result 	= $this->request('\\App\\Service\\Balance::incomeList', $params);
		
		foreach ($result['body']['list'] as &$value) {
			$value['post_day'] 	=  '20' . implode('-', str_split($value['post_day'], 2));
			$value['money'] 	= $value['money']/100;
		}
		 
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
			$date = date('m月d日', strtotime($params['date']));
			$params['date'] = intval(substr($params['date'], 2));
		}
		
		$params['user_id']	= session_get('USER_ID');
		
		$result 	= $this->request('\\App\\Service\\Balance::dailyList', $params);
		
		foreach ($result['body']['list'] as $key => &$value) {
			$value['user_money'] 	=  $value['user_money']/100;
			$value['content_icon'] 	= img_url($value['content_icon']);
			if (2 == $value['balance_type']) {
				$value['phone'] = '';
			} else {
				$value['phone'] = substr_replace($value['second_relation'], '****', 3, 4);
			}  
			unset($value['second_relation']);
			//$value['balance_type'] = balance_type($value['balance_type']);
		}

		$result['body']['date'] = $date;
 
		$result['body']['meta'] = ['title' => $date.'收益纪录'];
		
		$this->response($result);
	}
}