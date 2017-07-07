<?php
namespace App\Traits;

trait BalanceServiceTrait
{
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
}