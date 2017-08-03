<?php
namespace App\Service;

use Min\App;

class ShareService extends \Min\Service
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
 
			$sql = 'SELECT s.*, u.phone FROM {{user_share}} AS s INNER JOIN {{user}} AS u ON s.user_id = u.user_id  WHERE s.'. $type. ' = '. $name .' LIMIT 1';
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result);
		}
		
		if (!empty($result)) {
		
			return $this->success($result);
			
		} else {
			 
			return $this->error('不存在', 30206);
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
		
		try {

			$params 				= [];
			$params['viewer_id'] 	= intval($data['viewer_id']);
			$params['content_id'] 	= intval($data['content_id']);
			$params['share_user'] 	= intval($check['body']['user_id']);
			
			if ($check['body']['adv_cost'] > 0) {

				$parent_sql = 'SELECT ' . $check['body']['adv_id'] .'  AS u, u1.user_id as u1, u1.wx_id, IFNULL(u2.user_id, 0) AS u2, IFNULL(u3.user_id, 0) AS u3 FROM {{user_wx}} as u1 LEFT JOIN {{user_wx}} as u2 ON u1.parent_id > 0 and u1.parent_id = u2.wx_id LEFT JOIN {{user_wx}} as u3 ON u2.parent_id > 0 and u2.parent_id = u3.wx_id  WHERE u1.user_id = ' . $params['share_user'] . ' LIMIT 1';
				
				$parent = $db->query($parent_sql);
				
				if (empty($parent['u1'])) {
					return $this->error('用户不存在', 10000);
				}
				
				$wx_id = $parent['wx_id'];
				
				unset($parent['wx_id']);
				
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
				return $this->success(['wx_id' => $wx_id]);
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
			 
			return $this->success(['wx_id' => $wx_id]);
		
		} catch (\Throwable $t) {
		
			watchdog($t);
			
			$db->rollBack();
			
			return $this->error('操作失败', 20102);
		}

	}
	
	/*  用户分享数据列表*/
	
	public function logs($uid)
	{
		$uid = intval($uid);
		if ($uid < 1) {
			return $this->error('参数错误', 30000);
		}
 
		$sql_count 	= 'SELECT count(1) AS count FROM {{user_share}} WHERE user_id = ' . $uid . ' LIMIT 1';
		$sql_list 	= 'SELECT s.*, a.content_title, a.content_icon FROM {{user_share}} as s LEFT JOIN {{article}} AS a ON a.content_id = s.content_id WHERE s.user_id = ' . $uid . ' ORDER BY s.share_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	}
	
	/*
		记录用户分享
		params:
			share_no
			content_id
			share_time
			user_id
			type
	
	*/
	
	public function share($data)
	{
		$params['content_id'] 	= intval($data['content_id']);
		
		if ($params['content_id'] < 1) {
			return $this->error('参数错误', 20001);
		}
		
		$content_sql = 'SELECT * FROM {{article}}   WHERE content_id = ' . $params['content_id'] . ' LIMIT 1';
		
		$content = $this->query($content_sql);
		$params['share_time'] 	= intval($data['share_time']);
		$current = date('ymd', $params['share_time']);
	 
		if (empty($content) || $content['content_status'] < 1 || $current < $content['start_date'] || ($content['end_date'] > 0 && $current > $content['end_date'])) {
			return $this->error('操作失败', 20001);
		}
		
		$params['user_id'] 			= intval($data['user_id']);
		$params['share_type'] 		= intval($data['share_type']);
		
		$params['adv_id'] 			= intval($content['content_author']);
		$params['share_salary'] 	= intval($content['share_salary']);
		$params['adv_cost'] 		= intval($content['adv_cost']);

		$params['share_no'] 		= safe_json_encode($data['share_no']);
		$params['content_icon'] 	= safe_json_encode($content['content_icon']);
		$params['content_title'] 	= safe_json_encode($content['content_title']);
 
		$ins_sql = 'INSERT IGNORE INTO {{user_share}} ' . build_query_insert($params);
	
		$result = $this->query($ins_sql);
	
		if ($result['effect'] != 1) {
			watchdog('error insert share log SHARE_NO = ' . $params['share_no'] , 'share_id error', 'ERROR');
		} else {
			// 跳过手机号码范围
			if ($result['id'] > 9999999999 && $result['id'] < 10000900000) {
				$this->query('ALERT TABLE {{user_share}} auto_increment =  ' . (30000000000 + 1000 * ($result['id'] - 10000000000)));
			}
		}

		return $this->success();		
	}
	
	/* 用户分享总 浏览次数 */
	
	public function readed($user_id)
	{
		$uid = intval($user_id);
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}
		
		$sql = 'SELECT count(1) as count FROM {{user_share_view}} WHERE share_user = ' . $user_id . ' AND status = 1 LIMIT 1';
		$result = $this->query($sql);
		if (isset($result['count'])) {
			return $this->success($result);
		} else {
			return $this->error('操作失败', 20106);
		}
	
	}

	/* 某个分享的查看记录 （只含有效）*/
	
	public function shareViews($p)
	{
		$params  = [];
		$params['share_id'] = intval($p['share_id']);
		$params['user_id'] 	= intval($p['user_id']);
		$params['status'] = 1;
		
		if ($params['user_id'] < 1 || $params['share_id'] < 1) {
			return $this->error('参数错误', 30000);
		}
 
		$sql_count 	= 'SELECT count(1) AS count FROM {{user_share_view}} USE INDEX(share_id) WHERE ' . build_query_common(' AND ', $params) .' LIMIT 1';
		$sql_list 	= 'SELECT * FROM {{user_share_view}} USE INDEX(share_id) WHERE ' .  build_query_common(' AND ', $params) . ' ORDER BY view_id DESC';
		
		return $this->commonList($sql_count, $sql_list);
	}
  
}