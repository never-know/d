<?php
namespace App\Service;

use Min\App;

class ShareViewService extends \Min\Service
{
	protected $cache_key = 'share';
	
	
	/* 检查 share_id 对应的分享 是否存在 */
	
	public function check($name) 
	{	
		if (validate('words', $name)) {
			$type = 'share_no';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
		 
		$cache 	= $this->cache('share');
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
 
			//$sql = 'SELECT s.*, u.phone FROM {{user_share}} AS s INNER JOIN {{user}} AS u ON s.user_id = u.user_id  WHERE s.'. $type. ' = '. $name .' LIMIT 1';
			//$result	= $this->query($sql);
			
			$sql = 'SELECT * FROM {{user_share}}  WHERE  '. $type. ' = '. $name .' LIMIT 1';
			$result	= $this->query($sql);
			
			if (!empty($result['user_id'])) {
				$user_info = $this->callService('checkAccount')->checkAccount('uid', $result['user_id']);
				$result['wx_id']	= $user_info['body']['wx_id']??'0'; 
				$result['phone']	= $user_info['body']['phone']??'0'; 
				$cache->set($key, $result, 3600*24*20);
			}	
		}
		
		if (!empty($result['user_id'])) {
		
			return $this->success($result);
			
		} else {
		
			return $this->error('不存在', 30206);
		}
	}
	
	public function getShareUser($data)
	{
		$check = $this->check($data['share_no']);
		
		if ($check['statusCode'] != 1 || $check['body']['content_id'] != $data['content_id'] || $data['current_user'] == $check['body']['user_id']) {	
			return $this->success(['share_user' => 0, 'record' => 0]);	//$data['salary'] = $data['share_no'] = $check['body']['user_id'] = 0;
		} else {
		
			$params = [];
			
			$params['view_time'] 	= intval($data['view_time']);
			$params['viewer_id'] 	= intval($data['viewer_id']);
			$params['current_user'] = intval($data['current_user']);
			$params['content_id']   = intval($data['content_id']);
			$params['share_no']   	= safe_json_encode($data['share_no']);
		
			$sql = 'INSERT INTO {{user_share_view_data}} ' . build_query_insert($params);
			
			$result = $this->query($sql);
			 
			$params['data_id'] = $result['id']??0;
			 
			$this->cache('share_view')->push('share_view_logs', json_encode($params));
			return $this->success(['share_user' => intval($check['body']['wx_id']), 'record' => 1]);
		} 
	}

	/*
		params: 
				share_no  
				content_id
				current_user
				viewer_id
				view_time
	*/
	
	/* 浏览者阅读分享 生成的佣金等相关记录 */
	
	public function view($data) 
	{
		 
		$check = $this->check($data['share_no']);
		
		if ($check['statusCode'] != 1 || $check['body']['content_id'] != $data['content_id'] || $data['current_user'] == $check['body']['user_id']) {	
		
			return $this->success();	//$data['salary'] = $data['share_no'] = $check['body']['user_id'] = 0;
		}
		
		if ($check['body']['user_id'] == $check['body']['adv_id']) {
			$check['body']['share_salary'] 	= 0;
			$check['body']['adv_cost'] 	= 0;
		}
				
		$db = $this->DBManager();
		$ok = 0;
		try {

			$params 				= [];
			$params['viewer_id'] 	= intval($data['viewer_id']);
			$params['content_id'] 	= intval($data['content_id']);
			$params['share_user'] 	= intval($check['body']['user_id']);
			
			if ($check['body']['adv_cost'] > 0) {

				$parent_sql = 'SELECT ' . $check['body']['adv_id'] .'  AS u, u1.user_id as u1, IFNULL(u2.user_id, 0) AS u2, IFNULL(u3.user_id, 0) AS u3 FROM {{user_wx}} as u1 LEFT JOIN {{user_wx}} as u2 ON u1.parent_id > 0 and u1.parent_id = u2.wx_id LEFT JOIN {{user_wx}} as u3 ON u2.parent_id > 0 and u2.parent_id = u3.wx_id  WHERE u1.user_id = ' . $params['share_user'] . ' LIMIT 1';
				
				$parent = $db->query($parent_sql);
				
				if (empty($parent['u1'])) {
					$ok = 2;
					return $this->error('用户不存在', 10000);
				}
 
				foreach ($parent as $key => $value) {
					if (empty($value)) unset($parent[$key]);
				}
				
				$ids = implode(',', $parent);

				$db->begin();
				// 加锁
				$sql = 'SELECT * FROM {{user_balance}} WHERE user_id in (' . $ids . ') FOR UPDATE';
				
				$balance_raw = $db->query($sql);
				
				if (empty($balance_raw)) {
					throw new \Exception('操作失败', 20102);
				}
				$balance = [];
				foreach ($balance_raw as $key => $value) {
					$balance[$value['user_id']] = $value;	
				}
 
				$sql_count = 'SELECT view_id FROM {{user_share_view}} WHERE '. build_query_common(' AND ', $params). ' LIMIT 1';
	
				$count = $db->query($sql_count);
				
				if (!empty($count)) {
					$check['body']['share_salary'] 	= 0;
					$check['body']['adv_cost'] 		= 0;
					
				}
			}

			$params['view_time'] 	= intval($data['view_time']);
			$params['share_salary'] = $check['body']['share_salary'];
			$params['share_id'] 	= $check['body']['share_id'];
			$params['adv_id'] 		= $check['body']['adv_id'];
			$params['adv_cost'] 	= $check['body']['adv_cost'];
			$params['status'] 		= (($check['body']['adv_cost'] > 0) ? 1 : 0);

			$view_sql = 'INSERT INTO {{user_share_view}} ' . build_query_insert($params);
			
			$view_result =  $db->query($view_sql);
			
			// adv account finished when record view log;
			
			if ($params['status'] == 0) {
				$ok = 3;
				return $this->success(['wx_id' => $check['body']['wx_id']??0]);
			}
			
			$sql = 'UPDATE {{user_share}} SET view_times = view_times + 1, total_salary = total_salary + '. $params['share_salary'] . ' WHERE share_id = '. $check['body']['share_id'];
			
			$db->query($sql);
 			  
			$balance_log = [];
			$balance_log['user_id'] 		= $params['share_user'];
			$balance_log['user_money'] 		= $params['share_salary'];
			$balance_log['balance_type'] 	= 2;
			$balance_log['adv_id'] 			= $params['adv_id'];	//缺省值
			$balance_log['adv_cost'] 		= $params['adv_cost'];	//缺省值
			$balance_log['relation_id'] 	= $view_result['id'];
			$balance_log['second_relation'] = $params['share_id'];
			$balance_log['post_day']		= date('ymd', $params['view_time']);
			$balance_log['post_time']		= $params['view_time'];
			$balance_log['user_current_balance'] 	= $params['share_salary'] + $balance[$params['share_user']]['balance'];;
			$balance_log['adv_current_balance'] 	= $balance[$params['adv_id']]['balance'] - $params['adv_cost'];
			
			//list($balance_log['post_day'], $balance_log['post_hour']) = explode(' ', date('ymd His', $params['view_time']), 2);
			
			$balance_log_insert_data 		= [];
			
			// share user balance log
			
			$balance_log_insert_data[0] 	= $balance_log;
			
			$fa = config_get('fa', 100) * 100;
			
			$no_team_part = true;
			
			// parent user balance log
			
			if (intval(($balance[$params['share_user']]['share_part'] + $params['share_salary']) /$fa) == (intval($balance[$params['share_user']]['share_part']/$fa) + 1)) {
				$no_team_part = false;
				if (!empty($parent['u2'])) {
					$balance_log_insert_data[1] = $balance_log;
					$balance_log_insert_data[1]['user_id'] 			= $parent['u2'];
					$balance_log_insert_data[1]['balance_type'] 	= 3;
					$balance_log_insert_data[1]['user_money'] 		= config_get('level_one_salary', 0);	
					$balance_log_insert_data[1]['adv_id'] 			= 0;	
					$balance_log_insert_data[1]['adv_cost'] 		= 0;
					$balance_log_insert_data[1]['second_relation'] 	= $check['body']['phone'];
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
			
			$update = 'UPDATE {{user_balance}} 
				SET balance = CASE user_id 
					  WHEN ' .  $parent['u']  . ' THEN  balance - ' . $params['adv_cost'] .
					' WHEN ' .  $parent['u1'] . ' THEN  balance + ' . $params['share_salary'] . 
					(($no_team_part || empty($parent['u2'])) ? '' : ('  WHEN ' . $parent['u2'] . ' THEN balance + ' . $balance_log_insert_data[1]['user_money'])) .
					(($no_team_part || empty($parent['u3'])) ? '' : ('  WHEN ' . $parent['u3'] . ' THEN balance + ' . $balance_log_insert_data[2]['user_money'])) .				
					' ELSE balance 
				END, 
				share_part = CASE user_id 
					WHEN ' .  $parent['u1'] . ' THEN share_part + '.  $params['share_salary'] . 
					' ELSE share_part
				END' . 
				(($no_team_part || empty($parent['u2'])) ? '' : (',
				team_part  = CASE user_id
					WHEN ' . $parent['u2'] . ' THEN team_part + ' . $balance_log_insert_data[1]['user_money'] .
					(empty($parent['u3']) ? '': (' WHEN ' . $parent['u3'] . ' THEN team_part + ' . $balance_log_insert_data[2]['user_money'])) .
					' ELSE team_part 
				END,
				benefit_1 = case user_id
					WHEN ' . $parent['u1'] . ' THEN benefit_1 + ' . $balance_log_insert_data[1]['user_money'] .
					' ELSE benefit_1
				END,
				benefit_2 = case user_id
					WHEN ' . $parent['u1'] . ' THEN benefit_2 + ' . $balance_log_insert_data[2]['user_money'] .
					' ELSE benefit_2
				END ' )) . 
			' WHERE user_id IN (' . $ids . ')';

			$result = $db->query($update);

			$db->commit();
			$ok = 1;
			return $this->success(['wx_id' => $check['body']['wx_id']]);
		
		} catch (\Throwable $t) {
		
			watchdog($t);
			
			$db->rollBack();
			
			return $this->error('操作失败', 20102);
		} finally {
		
			if ($ok > 0 ) {
				$sql = 'UPDATE {{user_share_view_data}} set status = ' . $ok . ' WHERE data_id = ' . intval($data['data_id']);
				$this->query($sql);
			}
		}		
	}

}