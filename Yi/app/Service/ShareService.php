<?php
namespace App\Service;

use Min\App;

class ShareService extends \Min\Service
{
	protected $cache_key = 'share';
	
	public function check($name) 
	{	
		if (validate('word', $name)) {
			$type = 'share_id';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
		 
		$cache 	= $this->cache('share');
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
 
			$sql = 'SELECT * FROM {share} WHERE '. $type. ' = '. $name .' LIMIT 1';
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
		
			return $this->success($result);
			
		} else {
			 
			return $this->error('不存在', 30206);
		}
	}

	public function view($data) 
	{
		$check = $this->check($data['sid']);
		
		if (0 !== $check['statusCode'] || $check['body']['content_id'] != $data['id'] || $data['current_user'] == $check['body']['user_id']) {	
		
			return $this->success();//$data['salary'] = $data['sid'] = $check['body']['user_id'] = 0;
		}
				
		$db = $this->DBManager();

		$times = 3;
		
		while ($times > 0) {
		
			try {
				
				$db->start();
				
				$params 				= [];
				$params['viewer_id'] 	= intval($data['viewer_id']);
				$params['content_id'] 	= intval($data['id']);
				$params['share_user'] 	= intval($check['body']['user_id']);
				
				$sql_count = 'SELECT count(1) as count FROM {share_view} WHERE '. build_query_common(' AND ', $params). ' LIMIT 1';
		
				$count = $db->query($sql_count);
				
				if ($count['count'] > 2) {
					return $this->success(['userid' => $params['share_user']]);
				}
 
				$sql = 'SELECT * FROM {{balance}} WHERE user_id = ' . $param['share_user'] . ' LIMIT 1';
				$balance = $db->query($sql);
				
				if (!isset($balance['balance'])) {
					return $this->error('error', 20107); 
				}
 
				$balance_left = $balance['balance'] + $param['salary'];
				
				$update = 'UPDATE {{balance}} SET balance = ' . $balance_left .' WHERE user_id = ' . $param['share_user'] . ' AND balance = ' . $balance['balance'];
	 
				$update_result = $db->query($update);
				
				if (empty($update_result)) {
					throw new \Exception('操作失败', 20102);
				}
				
				if ( $update_result['effect'] == 0) {
					$db->rollBack();
					$times--;
					continue;
				}
				
				$params['view_time'] 	= intval($data['view_time']);
				$params['salary'] 		= intval($data['salary']);
				$params['share_id'] 	= json_encode($data['sid']);
				
				$view_sql = 'INSERT INTO {share_view} ' . build_query_insert($params);
				
				$view_result =  $db->query($view_sql);
				
				$count = $db->query($sql_count);
				
				if ($count['count'] > 2) {
					$db->rollBack();
					return $this->success(['userid' => $params['share_user']]);
				}
			
				$balance_log = [];
				$balance_log['user_id'] 		= $param['share_user'];
				$balance_log['balance_type'] 	= 2;
				$balance_log['relation_id'] 	= $view_result['id'];
				$balance_log['money'] 			= $params['salary'];
				$balance_log['balance'] 		= $balance_left;
				 
				list($balance_log['post_day'], $balance_log['post_hour']) = explode(' ', date('ymd His', $param['draw_time']), 2);

				$log_sql = 'INSERT INTO {{balance_log}} ' . query_build_insert($balance_log);
					
				$db->query($log_sql);
				
				$adv_sql = 'UPDATE {{advertiser}} SET balance =  balance - ' . ($params['salary'] * 3) . ' WHERE adv_id = ' . $check['adv_id']; 
				
				$db->query($adv_sql);
				
				$db->commit();
				
				return $this->success(['userid' => $params['share_user']]);
			
			} catch (\Throwable $t) {
			
				watchdog($t);
				
				$db->rollBack();
				
				return $this->error('操作失败', 20102);
			}
			
		}	
			
		 
	}
	
	public function logs($uid)
	{
		$uid = intval($uid);
		if ($uid < 1) {
			return $this->error('参数错误', 30000);
		}
 
		$sql_count 	= 'SELECT count(1) AS count FROM {share_record} WHERE user_id = ' . $uid . ' LIMIT 1';
		$sql_list 	= 'SELECT a.title,a.icon, s.*, count(v.share_id) AS views FROM {share_record} as s LEFT JOIN {article} AS a ON a.id = s.content_id LEF T JOIN {share_views} AS v on v.share_id = s.share_id WHERE s.user_id = ' . $uid . ' GROUP BY s.share_id ORDER BY s.share_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	}
	
	 
}