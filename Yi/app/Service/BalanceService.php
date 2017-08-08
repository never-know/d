<?php
namespace App\Service;

use Min\App;

class BalanceService extends \Min\Service
{
	/* 帐户余额 */
	
	public function account($user_id)
	{
		$user_id 		= intval($user_id);
		 
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}

		$sql = 'SELECT * FROM {{user_balance}} WHERE user_id = ' . $user_id . ' LIMIT 1';
		
		$result = $this->query($sql);
		
		if (!empty($result)) {
			return $this->success($result);
		} else {
			return $this->error('操作失败', 20107);
		}
		
	}
	
	/* 今日收益 */
	
	public function today($user_id)
	{
		$user_id 		= intval($user_id);
		 
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}

		$sql = 'SELECT IFNULL(sum(user_money), 0) as total FROM {{user_balance_log}} WHERE user_id = ' . $user_id . ' AND post_day = ' . date('ymd') . ' AND   balance_type IN (2, 3, 4) LIMIT 1';
		
		$result = $this->query($sql);
		if (!empty($result)) {
			return $this->success($result);
		} else {
			return $this->error('操作失败', 20107);
		}
		
	}
	
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

		$sql_count 	= 'SELECT count(1) as count FROM {{user_balance_log}} WHERE user_id = ' .$user_id . '  LIMIT 1';
		$sql_list 	= 'SELECT * FROM {{user_balance_log}} WHERE user_id = ' .$user_id . ' ORDER BY log_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	}
	
	/*   
		@param 
		
			user_id
			register_time
			每一天
		
	 */	  
	
	public function incomeAllList($p)
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
		
			$sql = 'SELECT sum(money) AS money, post_day FROM {{user_balance_log}} WHERE user_id = '.$user_id . ' AND post_day <= ' . $begin . ' AND post_day > ' . $end . ' AND balance_type in (2,3,4) GROUY BY post_day ORDER BY log_id DESC';
	  
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
			register_time
			有数据的一天
		
	 */	  
	
	public function incomeList($p)
	{
		$user_id 		= intval($p['user_id']);
		 

		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}
		
		$sql_count 	= 'SELECT count(t.post_day) as count FROM (SELECT post_day FROM {{user_balance_log}} WHERE user_id = ' . $user_id . ' AND balance_type in (2,3,4) GROUP BY post_day ) t LIMIT 1';
		
		$sql_list 	= 'SELECT log_id , sum(user_money) AS money, post_day FROM {{user_balance_log}} WHERE user_id = '.$user_id . ' AND  balance_type in (2,3,4) GROUP BY post_day ORDER BY log_id DESC ';
		
		return $this->commonList($sql_count, $sql_list);

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
		$param['l.user_id'] 	= intval($p['user_id']);
		$param['l.post_day'] 	= intval($p['date']);
		 
		if ($param['l.user_id'] < 1 || $param['l.post_day'] < 170701 || $param['l.post_day'] > 991230) {
			return $this->error('参数错误', 30000);
		}

		$sql_count 	= 'SELECT count(1) as count, sum(l.user_money) as money, l.balance_type FROM {{user_balance_log}} AS l WHERE ' . build_query_common(' AND ', $param, false) . ' AND   l.balance_type in (2,3,4) GROUP BY l.balance_type';
		
		$summary = $this->query($sql_count);
		
		if (false === $summary) {
			return $this->error('加载失败', 20106);
		}
		
		$count 	= 0;
		
		$money	= ['total' => 0, 'share_part' => 0, 'team_part' => 0];
		
		if (!empty($summary)) {
		
			foreach ($summary as $value) {
				$count 		+= $value['count'];
				$money['total'] 	+= $value['money'];
				if (2 == $value['balance_type']) {
					$money['share_part'] = $value['money'];
				} else {
					$money['team_part']  += $value['money'];
				}
			}
		}
		
		$sql_list 	= 'SELECT l.balance_type, l.user_money, l.second_relation, l.post_time, s.content_icon, s.content_title, s.share_time, s.share_type FROM {{user_balance_log}} AS l LEFT JOIN {{user_share}} AS s ON l.balance_type = 2 AND l.second_relation = s.share_id WHERE ' . build_query_common(' AND ', $param, false) . ' AND l.balance_type in (2,3,4) ORDER BY l.log_id DESC';
		
		$result = $this->commonList($count, $sql_list);
		
		if (1 != $result['statusCode']) {
			return $result;
		}
 
		$result['body']['summary'] = $money;
		
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

		弃用
	 */	 
	 
	private function record($data) 
	{
		$param = [];
 
		$param['user_id'] 		= intval($data['user_id']);
		$param['balance_type'] 	= intval($data['balance_type']);
		$param['relation_id'] 	= intval($data['relation_id']);
		
		if (!in_array($param['balance_type'], config_get('balance_type')) || $param['user_id'] < 1 || $param['relation_id'] < 1) {
			return $this->error('参数错误', 20107);
		}

		$param['balance'] 		= intval($data['balance']);
		$param['money'] 		= intval($data['money']);
		$param['post_time'] 	= intval($data['post_time']);
		$param['post_day']		= date('ymd His', $param['post_time']);
		
		if (!isset($data['balance']) || $param['balance'] < 0 || $data['post_time'] < 0 || $param['money'] == 0) {
			return $this->error('参数错误', 20107);
		}			

		$sql = 'INSERT INTO {{user_balance_log}} ' . query_build_insert($param);
		
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
		
		$sql = 'SELECT * FROM {{user_balance_log}} AS bl 
		LEFT JOIN {{user_share_view}} AS sv ON bl.balance_type = 3 AND bl.realtion_id = sv.view_id 
		LEFT JOIN {{user_draw}} AS d ON bl.balance_type = 4 AND bl.realtion_id = d.draw_id 
		LEFT JOIN {{user}} AS u ON bl.balance_type = 5 AND bl.realtion_id = u.user_id 

		WHERE ' . \query_bulid_common(' AND ', $param) . '  LIMIT 1';
		
		$info = $this->query($sql);
		
		if (empty($info)) {
			return $this->error('参数错误', 2000);
		}

		return $this->success($info);
	
	}
	
	public function benefit($params)
	{
		if (empty($params['phone']) || !is_array($params['phone']) || !in_array($params['type'], [3,4], true)) {
			return $this->error('参数错误', 2000);
		}
		
		$phones = array_map('intval', $params['phone']);
		
		$sql = 'SELECT ifnull(sum(user_money), 0) as benefit, second_relation as phone from {{user_balance_log}} WHERE second_relation in ( ' . implode(',', $phones) .' ) and balance_type = ' . $params['type'];
		
		$result = $this->query($sql);
		
		if (false === $result) {
			return $this->error('操作失败', 20107);
		} else {
			return $this->success($result);
		}
	
	}
	
	
}