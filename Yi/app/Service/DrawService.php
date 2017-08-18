<?php
namespace App\Service;

use Min\App;

class DrawService extends \Min\Service
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

		$sql_count 	= 'SELECT count(1) as count FROM {{user_draw}} WHERE user_id = ' .$user_id . '  LIMIT 1';
		$sql_list 	= 'SELECT draw_id, draw_money, draw_time, update_time FROM {{user_draw}} WHERE user_id = ' .$user_id . ' ORDER BY draw_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	} 
	
	/*   
		@param 
		
		user_id
		draw_account
		draw_money
		draw_time			UNIX时间戳
		
	 */	 
	 
	private function record($data) 
	{
		$param = [];

		$param['user_id'] 		= intval($data['user_id']);
		$param['draw_account'] 	= intval($data['draw_account']);
		$param['draw_money'] 	= intval($data['draw_money']);
		$param['draw_time'] 	= intval($data['draw_time']);

		foreach ($param as $value) {
			if ($value < 1)  return $this->error('参数错误', 20107); 
		}
		
		$db = $this->DBManager();

		try {
		
			$db->begin();
			
			$sql = 'SELECT * FROM {{user_balance}} WHERE user_id = ' . $param['user_id'] . ' LIMIT 1 FOR UPDATE';
			$balance = $db->query($sql);
			
			if (!isset($balance['balance'])) {
				 throw new \Exception('操作失败', 20102);
			}
			
			if ($balance['balance'] < $param['draw_money']) {
				$db->rollBack();
				return $this->error('余额不足或已有提现', 20107); 
			}

			$update = 'UPDATE {{user_balance}} SET balance = balance -' . $param['draw_money'] . ' WHERE user_id = ' . $param['user_id'] ;
 
			$update_result = $db->query($update);
			
			if (empty($update_result)) {
				throw new \Exception('操作失败', 20102);
			}
			
			$param['draw_status'] 	= 2;
			$param['update_time'] 	= 0;
	 
			$draw_sql = 'INSERT INTO {{user_draw}} ' . query_build_insert($param);
			
			$draw_result = $db->query($draw_sql);
			
			$balance_log = [];
			$balance_log['user_id'] 			= $param['user_id'];
			$balance_log['user_money'] 			= 0 - $param['draw_money'];
			$balance_log['balance_type'] 		= 11;
			$balance_log['adv_id'] 				= 0;	//缺省值
			$balance_log['adv_cost'] 			= 0;	//缺省值
			$balance_log['relation_id'] 		= $draw_result['id'];
			$balance_log['user_current_balance']	= $balance['balance'] - $param['draw_money'];
			$balance_log['adv_current_balance'] 	= 0;
			 
			list($balance_log['post_day'], $balance_log['post_hour']) = explode(' ', date('ymd His', $param['draw_time']), 2);

			$log_sql = 'INSERT INTO {{user_balance_log}} ' . query_build_insert($balance_log);
				
			$log_result = $db->query($log_sql);

			$db->commit();
			
			return $this->success();

		} catch (\Throwable $t) {
			 
			watchdog($t);
			
			$db->rollBack();
			
			return $this->error('操作失败', 20102);
		}
		
		 
	}
	
	/*   
		@param
		
		draw_id
		user_id
		draw_status			3|4
		transaction_id		when draw_status = 3
		
	 */	
	
	public function update($data)
	{
		$param 	= [];
		$update = [];
		$param['draw_id'] 			= intval($data['draw_id']);
		$param['user_id'] 			= intval($data['user_id']);	
		$update['draw_status']		= intval($data['draw_status']);		
		$update['update_time']		= intval($data['update_time']);		
		$update['transaction_id'] 	= trim($data['transaction_id']);
		
		if ($param['draw_id'] < 1 || $param['user_id'] < 1 || !in_array($update['draw_status'], [3, 4]) || !is_numeric($update['transaction_id']) || $update['update_time'] < 1) {
			return $this->error('参数错误', 20107);
		}
		
		$db = $this->DBManager();
		
		$sql = 'SELECT * FROM {{draw}}  WHERE '. query_build_common(' AND ', $param) . ' LIMIT 1';
		
		$draw = $db->query($sql);
		
		if (empty($draw)) {
			return $this->error('参数错误', 2001);
		}
 
		try {
			if (4 == $update['draw_status'] || 5 == $update['draw_status']) {
				$db->begin();
			}
			
			$sql = 'UPDATE {{draw}} SET ' . query_build_common(', ', $update) . ' WHERE ' . query_build_common(' AND ', $param);
			
			$result = $db->query($sql);
			
			if (4 == $update['draw_status'] || 5 == $update['draw_status']) {
			
				$sql = 'SELECT * FROM {{user_balance}} WHERE user_id = ' . $param['user_id'] . ' LIMIT 1 FOR UPDATE';
				$balance = $db->query($sql);
				
				if (!isset($balance['balance'])) {
					 throw new \Exception('操作失败', 20102);
				}
			 
				$sql = 'UPDATE {{user_balance}} SET balance = balance + ' . $draw['draw_money'] . ' WHERE user_id = ' . $param['user_id'];
			
				$result = $this->query($sql);
				
				$balance_log = [];
				$balance_log['user_id'] 		= $param['user_id'];
				$balance_log['user_money'] 		= $draw['draw_money'];
				$balance_log['balance_type'] 	= 11 + $update['draw_status'];
				$balance_log['adv_id'] 			= 0;	//缺省值
				$balance_log['adv_cost'] 		= 0;	//缺省值
				$balance_log['relation_id'] 	= $draw['draw_id'];
				$balance_log['user_current_balance'] 	= $draw['draw_money'] + $balance['balance'];
				$balance_log['adv_current_balance'] 	= 0;
	 
				list($balance_log['post_day'], $balance_log['post_hour']) = explode(' ', date('ymd His', $param['draw_time']), 2);

				$log_sql = 'INSERT INTO {{balance_log}} ' . query_build_insert($balance_log);
					
				$log_result = $db->query($log_sql);
				
				$db->commit();
				return $this->success();
			}
			
		} catch (\Throwable $t) {
			
			watchdog($t);
			if (4 == $update['draw_status'] || 5 == $update['draw_status']) {
				$db->rollBack();
			}

			return $this->error('fail', 30204);
		}
	}
	
	
	/* 提现帐户  */
	
	public function account($user_id)
	{
		$user_id = intval($user_id);
		
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}
		
		$sql = 'SELECT * FROM {{user_balance_account}} WHERE status = 1 and user_id = ' . $user_id;
		$result = $this->query($sql);
		if (false === $result) {
			return $this->error('操作失败', 20106);
		} else {
			return $this->success(['list' => $result]);
		}
	}
	
	
	public function accountAdd($data)
	{
		$params = [];
		$params['status'] 		= intval($data['status']);
		$params['user_id'] 		= intval($data['user_id']);
		$params['account_type'] = intval($data['account_type']);
		
		if ($params['user_id']  < 1) {
			return $this->error('参数错误', 30000);
		}
		
		$params['account_name'] = trim($data['account_name']);
		$params['real_name'] 	= trim($data['real_name']);
		$params['extra_info'] 	= trim($data['extra_info']);
		
		
		if (!validate('account_id', $params['account_name']) || !validate('text', $params['real_name'], 20, 2) || !validate('text', $params['extra_info'], 32, 0)) {
			return $this->error('参数错误', 30000);
		}
		
		$params['account_name'] = safe_json_encode($params['account_name']);
		$params['real_name'] 	= safe_json_encode($params['real_name']);
		$params['extra_info'] 	= safe_json_encode($params['extra_info']);
		
		$sql = 'INSERT INTO {{user_balance_account}} ' . build_query_insert($params);
		$result = $this->query($sql);
		if ($result['id'] > 0) {
			return $this->success();
		} else {
			return $this->error('操作失败', 10000);
		}
	}
	
	public function accountDel($data)
	{
		$params = [];
		$params['account_id'] 	= intval($data['account_id']);
		$params['user_id'] 		= intval($data['user_id']);
		
		$sql = 'UPDATE {{user_balance_account}} SET status = 0  WHERE ' .  build_query_common(' AND ', $params); 
		
		$result = $this->query($sql);
		if ($result['effect'] > 0) {
			return $this->success();
		} else {
			return $this->error('操作失败', 10000);
		}
	
	}
	
 
}