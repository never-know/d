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
 
			$sql = 'SELECT * FROM {{share}}  WHERE '. $type. ' = '. $name .' LIMIT 1';
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
		
			return $this->success($result);
			
		} else {
			 
			return $this->error('不存在', 30206);
		}
	}

	/*
		params: 
				sid  // share_id
				content_id
				current_user
				viewer_id
				view_time
	
	
	*/
	
	public function view($data) 
	{
		$check = $this->check($data['sid']);
		
		if (0 !== $check['statusCode'] || $check['body']['content_id'] != $data['content_id'] || $data['current_user'] == $check['body']['user_id']) {	
		
			return $this->success();	//$data['salary'] = $data['sid'] = $check['body']['user_id'] = 0;
		}
		
		if ($check['body']['user_id'] == $check['body']['adv_id']) {
			$check['body']['share_salary'] 	= 0;
			$check['body']['adv_cost'] 	= 0;
		}
				
		$db = $this->DBManager();
		
		try {

			$params 				= [];
			$params['viewer_id'] 	= intval($data['viewer_id']);
			$params['content_id'] 	= intval($data['content_id']);
			$params['share_user'] 	= intval($check['body']['user_id']);
			
			if ($check['body']['adv_cost'] > 0) {

				$parent_sql = 'SELECT ' . $check['body']['adv_id'] .'  AS u, u1.user_id as u1, IFNULL(u2.user_id, 0) AS u2, IFNULL(u3.user_id, 0) AS u3 FROM {{user_wx}} as u1 INNER JOIN {{user_wx}} as u2 ON u1.parent_id > 0 and u1.parent_id = u2.wx_id LEFT JOIN {{user_wx}} as u3 ON u2.parent_id > 0 and u2.parent_id = u3.wx_id  WHERE u1.user_id = ' . $params['share_user'] . ' LIMIT 1';
				
				$parent = $db->query($parent_sql);
				
				foreach ($parent as $key => $value) {
					if (empty($value)) unset($parent[$key]);
				}
				
				$ids = implode(',', $parent);

				$db->begin();
				// 加锁
				$sql = 'SELECT * FROM {{user_balance}} WHERE user_id in (' . $ids . ') FOR UPDATE';
				
				$balance = $db->query($sql);
				
				if (empty($balance)) {
					throw new \Exception('操作失败', 20102);
				}
				
				foreach ($balance as $key => $value) {
					unset($balance[$key]);
					$balance[$value['user_id']] = $value;	
				}
				
				$sql_count = 'SELECT count(1) as count FROM {share_view} WHERE '. build_query_common(' AND ', $params). ' LIMIT 1';
	
				$count = $db->query($sql_count);
				
				if ($count['count'] > 2) {
					$check['body']['share_salary'] 	= 0;
					$check['body']['adv_cost'] 	= 0;
				}
			}

			$params['view_time'] 	= intval($data['view_time']);
			$params['share_salary'] = $check['body']['share_salary'];
			$params['share_id'] 	= json_encode($data['sid']);
			$params['adv_id'] 		= $check['body']['adv_id'];
			$params['adv_cost'] 	= $check['body']['adv_cost'];
			
			$view_sql = 'INSERT INTO {share_view} ' . build_query_insert($params);
			
			$view_result =  $db->query($view_sql);
			
			// adv account finished when record view log;
			
			if ($check['body']['adv_cost'] <= 0) {
				return $this->success();
			}

			$balance_log = [];
			$balance_log['user_id'] 		= $params['share_user'];
			$balance_log['user_money'] 		= $params['share_salary'];
			$balance_log['balance_type'] 	= 2;
			$balance_log['adv_id'] 			= $params['adv_id'];	//缺省值
			$balance_log['adv_cost'] 		= $params['adv_cost'];	//缺省值
			$balance_log['relation_id'] 	= $view_result['id'];
			$balance_log['user_current_balance'] 	= $params['share_salary'] + $balance[$params['share_user']]['balance'];;
			$balance_log['adv_current_balance'] 	= $balance[$params['adv_id']]['balance'] - $params['adv_cost'];
			
			list($balance_log['post_day'], $balance_log['post_hour']) = explode(' ', date('ymd His', $params['draw_time']), 2);
			
			$balance_log_insert_data 		= [];
			
			// share user balance log
			
			$balance_log_insert_data[0] 	= $balance_log;
			
			$fa = config_get('fa', 100) * 100;
			
			// parent user balance log
			
			if (intval($share_left/$fa) == (intval($balance[$params['share_user']]['share_part']/$fa) + 1)) {

				if (!empty($parent['u2'])) {
					$balance_log_insert_data[1] = $balance_log;
					$balance_log_insert_data[1]['user_id'] 			= $parent['u2'];
					$balance_log_insert_data[1]['balance_type'] 	= 3;
					$balance_log_insert_data[1]['user_money'] 		= config_get('level_one_salary', 0);	
					$balance_log_insert_data[1]['adv_id'] 			= 0;	
					$balance_log_insert_data[1]['adv_cost'] 		= 0;
					$balance_log_insert_data[1]['adv_current_balance'] 		= 0;
					$balance_log_insert_data[1]['user_current_balance'] 	= $balance_log_insert_data[1]['user_money'] + $balance[$parent['u2']]['balance'];	
				}
				
				if (!empty($parent['u3'])) {
					$balance_log_insert_data[2] = $balance_log_insert_data[1];
					$balance_log_insert_data[2]['user_id'] 			= $parent['u3'];
					$balance_log_insert_data[2]['balance_type'] 	= 4;
					$balance_log_insert_data[2]['user_money'] 		= config_get('level_two_salary', 0);	
					$balance_log_insert_data[2]['user_current_balance'] 	= $balance_log_insert_data[2]['user_money'] + $balance[$parent['u3']]['balance'];
				}
			}
			
			$balance_log_sql = 'INSERT INTO {{user_balance_log}} ' . build_query_insert($balance_log_insert_data);
			
			$db->query($balance_log_sql);
			
			$update = 'UPDATE user_balance 
				SET balance = CASE user_id 
					  WHEN ' .  $parent['u']  . ' THEN  balance - ' . $params['adv_cost'] .
					' WHEN ' .  $parent['u1'] . ' THEN  balance + ' . $params['share_salary'] . 
					(empty($parent['u2']) ? '': ('  WHEN ' . $parent['u2'] . ' THEN balance + ' . $balance_log_insert_data[1]['user_money'])) .
					(empty($parent['u3']) ? '': ('  WHEN ' . $parent['u3'] . ' THEN balance + ' . $balance_log_insert_data[2]['user_money'])) .				
					' ELSE balance 
				END, 
				share_part = CASE user_id 
					WHEN ' .  $parent['u1'] . ' THEN share_part + '.  $params['share_salary'] . 
					' ELSE share_part
				END' . 
				(empty($parent['u2']) ? '': (',
				team_part  = CASE user_id
					WHEN ' . $parent['u2'] . ' THEN team_part + ' . $balance_log_insert_data[1]['user_money'] .
					(empty($parent['u3']) ? '': (' WHEN ' . $parent['u3'] . ' THEN team_part + ' . $balance_log_insert_data[2]['user_money'])) .
					' ELSE team_part 
				END	' )) . 
			' WHERE user_id IN (' . $ids . ')';

			$result = $db->query($update);

			$db->commit();
			 
			return $this->success(['user_id' => $params['share_user']]);
		
		} catch (\Throwable $t) {
		
			watchdog($t);
			
			$db->rollBack();
			
			return $this->error('操作失败', 20102);
		}

	}
	
	public function logs($uid)
	{
		$uid = intval($uid);
		if ($uid < 1) {
			return $this->error('参数错误', 30000);
		}
 
		$sql_count 	= 'SELECT count(1) AS count FROM {share_record} WHERE user_id = ' . $uid . ' LIMIT 1';
		$sql_list 	= 'SELECT a.title,a.icon, s.*, count(v.share_id) AS views FROM {share_record} as s LEFT JOIN {article} AS a ON a.id = s.content_id LEFT JOIN {share_views} AS v on v.share_id = s.share_id WHERE s.user_id = ' . $uid . ' GROUP BY s.share_id ORDER BY s.share_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	}
	
	public function share()
	{
		$params['content_id'] 	= intval($data['content_id']);
		
		if ($params['content_id'] < 1) {
			return $this->error('参数错误', 20001);
		}
		$content_sql = 'SELECT c.*, u.balance FROM {{content}} AS c INNER JOIN {{user_balance}} AS u ON c.author = u.user_id  WHERE c.content_id = ' . $params['content_id'] . ' LIMIT 1';
		
		$content = $this->query($content_sql);
		$params['share_time'] 	= intval($data['share_time']);
		$current = date('ymd', $params['share_time']);
	 
		if (empty($content) || $content['content_status'] || $content['content_status'] < 1 || $current < $content['start_date'] || ($content['end_date'] > 0 && $current > $content['end_date'])) {
			return $this->error('操作失败', 20001);
		}
		
		$params['user_id'] 		= intval($data['user_id']);
		$params['share_type'] 	= intval($data['type']);
		
		$params['adv_id'] 		= intval($content['content_author']);
		$params['share_salary'] = intval($content['share_salary']);
		$params['adv_cost'] 	= intval($content['adv_cost']);
		
		$params['share_id'] = \shareid($params['content_id'], $params['share_type'], $params['user_id']);
 
		$ins_sql = 'INSERT INGORE INTO {{share}} ' . build_query_insert($params);
	
		$result = $this->query($ins_sql);
	
		if ($result['effect'] != 1) {
			watchdog('error insert share log');
		}
		
		return $this->success();
		
	}
	
	 
}