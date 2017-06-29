<?php
namespace App\Service;

use Min\App;

class BalanceService extends \Min\Service
{
	/*   
		@param 
		
		user_id
		
	 */	
	 
	public function allList($user_id)
	{
		$user_id 		= intval($user_id);
		 
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}

		$sql_count 	= 'SELECT count(1) as count FROM {{balance_log}} WHERE user_id = ' .$user_id . '  LIMIT 1';
		$sql_list 	= 'SELECT * FROM {{balance_log}} WHERE user_id = ' .$user_id . ' ORDER BY log_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	}
	
	/*   
		@param 
		
		user_id
		register_time
		
	 */	  
	
	public function incomeList($p)
	{
		$user_id 		= intval($p['user_id']);
		$register_time 	= intval($p['register_time']);

		if ($user_id < 1 || $register_time < 1483367129) {
			return $this->error('参数错误', 30000);
		}
 
		$today	= strtotime(date('Y-m-d') . ' 23:59:59');
		 
		$_REQUEST['page_size']	= 2592000;
		
		$page 	= \result_page(($today - $register_time));

		if ($page['current_page'] > $page['total_page']) {
		
			$list = [];
			
		} else {
		
			$begin 	= date('ymd', $today - (($page['current_page'] - 1) * $page['page_size']));
			$end 	= date('ymd', $begin - $page['page_size']);
		
			$sql = 'SELECT sum(money) AS money, post_day FROM {{balance_log}} WHERE user_id = '.$user_id . ' AND post_day <= ' . $begin . ' AND post_day > ' . $end . ' AND balance_type > 0 AND balance_type < 3 GROUY BY post_day ORDER BY log_id DESC';
	  
			$list = $this->query($sql);
			
			if (false === $list) {
				return $this->error('加载失败', 20106);
			} 	
		}
		
		return $this->success(['page' => $page, 'list' => $list]);
	}
	
	/*   
		@param 
		
		user_id
		start		ymd, between(170803, 991230)
		
		@result
		
		
		
	 */	  
	
	public function dailyList($p)
	{
		$param				= [];
		$param['user_id'] 	= intval($p['user_id']);
		$param['post_day'] 	= intval($p['start']);
		
		if ($param['user_id'] < 1 || $param['post_day'] < 170803 || $param['post_day'] > 991230) {
			return $this->error('参数错误', 30000);
		}

		$sql_count 	= 'SELECT count(1) as count, sum(money) as money, balance_type FROM {{balance_log}} WHERE ' . build_query_common(' AND ', $param) . ' AND balance_type > 0 AND balance_type < 3 GROUP BY balance_type';
		
		$summary = $this->query($sql_count);
		
		if (empty($summary)) {
			return $this->error('加载失败', 20106);
		}
		
		$count 	= 0;
		
		$money	= [ 0 ];
		
		foreach ($summary as $value) {
			$count += $value['count'];
			$money[$value['balance_type']] = $value['money'];
			$money[0] += $value['money'];
			
		}
		
		$sql_list 	= 'SELECT * FROM {{balance_log}} WHERE ' . build_query_common(' AND ', $param) . ' ORDER BY log_id DESC';
		
		$result = $this->commonList($count, $sql_list);
		
		if (0 === $result['statusCode']) {
			$result['body']['summary'] = $money
		}
		
		return $result;
	}
	
	/*   
		@param 
		
		user_id
		balance_type
		relation_id
		balance
		money
		post_time		UNIX时间戳
		
	 */	 
	 
	private function record($data) 
	{
		$param = [];
 
		$param['user_id'] 		= intval($data['user_id']);
		$param['balance_type'] 	= intval($data['balance_type']);
		$param['relation_id'] 	= intval($data['relation_id']);
		
		if (!in_array($param['balance_type'], config_get('balance_type')) || $param['user_id'] < 1 || $param['relation_id'] < 1) {
			$this->error('参数错误', 20107);
		}
 
	
		$param['balance'] 		= intval($data['balance']);
		$param['money'] 		= intval($data['money']);
		$data['post_time'] 		= intval($data['post_time']);
		
		if (!isset($data['balance']) || $param['balance'] < 0 || $data['post_time'] < 0 || $param['money'] == 0) {
			$this->error('参数错误', 20107);
		}			
	
		list($param['post_day'], $param['post_hour'])	= explode(' ', date('ymd His', $data['post_time']), 2);

		$sql = 'INSERT INTO {{balance_log}} ' . query_build_insert($param);
		
		$result = $this->query($sql);

		if ($result['id'] > 0) {
			return $this->success();
		} else {
			return $this->error('fail', 30204);
		}
	}
	
	/*   
		@param
		
		log_id
		user_id
		
	 */	
	
	public function info($data)
	{
		$param = [];

		$param['log_id'] 		= intval($data['log_id']);
		$param['user_id'] 		= intval($data['user_id']);		 
		
		if ($param['log_id'] < 1 || $param['user_id'] < 1) {
			return $this->error('参数错误', 20107);
		}
		
		$sql = 'SELECT * FROM {{balance_log}} AS bl 
		LEFT JOIN {{share_view}} AS sv ON bl.balance_type = 3 AND bl.realtion_id = sv.view_id 
		LEFT JOIN {{draw}} AS d ON bl.balance_type = 4 AND bl.realtion_id = d.draw_id 
		LEFT JOIN {{user}} AS u ON bl.balance_type = 5 AND bl.realtion_id = u.user_id 

		WHERE ' . \query_bulid_common(' AND ', $param) . '  LIMIT 1';
		
		$info = $this->query($sql);
		
		if (empty($info)) {
			return $this->error('参数错误', 2000);
		}

		return $this->success($info);
	
	}
	
	
}