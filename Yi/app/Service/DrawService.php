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

		$sql_count 	= 'SELECT count(1) as count FROM {{draw}} WHERE user_id = ' .$user_id . '  LIMIT 1';
		$sql_list 	= 'SELECT * FROM {{draw}} WHERE user_id = ' .$user_id . ' ORDER BY draw_id DESC';
		
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
		
		$times = 3;
		
		while ($times > 0) {

			try {
			
				$db->start();
				
				$sql = 'SELECT * FROM {{balance}} WHERE user_id = ' . $param['user_id'] . ' LIMIT 1';
				$balance = $db->query($sql);
				
				if (!isset($balance['balance'])) {
					return $this->error('error', 20107); 
				}
				
				if ($balance['balance'] < $param['draw_money']) {
					return $this->error('余额不足或已有提现', 20107); 
				}
				
				$balance_left = $balance['balance'] - $param['draw_money'];
				
				$update = 'UPDATE {{balance}} SET balance = ' . $balance_left .' WHERE user_id = ' . $param['user_id'] . ' AND balance = ' . $balance['balance'];
	 
				$update_result = $db->query($update);
				
				if (empty($update_result)) {
					throw new \Exception('操作失败', 20102);
				}
				
				if ( $update_result['effect'] == 0) {
					$db->rollBack();
					$times--;
					continue;
				}
 
				$param['draw_status'] 	= 2;
				$param['update_time'] 	= 0;
		 
				$draw_sql = 'INSERT INTO {{draw}} ' . query_build_insert($param);
				
				$draw_result = $db->query($draw_sql);
				
				$balance_log = [];
				$balance_log['user_id'] 		= $param['user_id'];
				$balance_log['balance_type'] 	= 11;
				$balance_log['relation_id'] 	= $draw_result['id'];
				$balance_log['money'] 			= 0 - $param['draw_money'];
				$balance_log['balance'] 		= $balance_left;
				 
				list($balance_log['post_day'], $balance_log['post_hour']) = explode(' ', date('ymd His', $param['draw_time']), 2);

				$log_sql = 'INSERT INTO {{balance_log}} ' . query_build_insert($balance_log);
					
				$log_result = $db->query($log_sql);
 
				$db->commit();
				
				return $this->success();

			} catch (\Throwable $t) {
				 
				watchdog($t);
				
				$db->rollBack();
				
				return $this->error('操作失败', 20102);
			}
		
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
		
		if ($param['draw_id'] < 1 || $param['user_id'] < 1 || !in_array($update['draw_status'], [3, 4]) || !is_numeric($update['transaction_id'] || $update['update_time'] < 1) {
			return $this->error('参数错误', 20107);
		}
		
		$sql = 'UPDATE {{draw}} SET ' . query_build_common(', ', $update) . ' WHERE ' . query_build_common(' AND ', $param);
		
		$result = $this->query($sql);
  
		if ($result['effect'] > 0) {
			return $this->success();
		} else {
			return $this->error('fail', 30204);
		}	
	}
 
}