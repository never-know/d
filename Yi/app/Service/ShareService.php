<?php
namespace App\Service;

use Min\App;

class ShareService extends \Min\Service
{
	protected $cache_key = 'share';
 
	/*  用户分享数据列表*/
	
	public function logs($uid)
	{
		$uid = intval($uid);
		if ($uid < 1) {
			return $this->error('参数错误', 30000);
		}
 
		$sql_count 	= 'SELECT count(1) AS count FROM {{user_share}} WHERE user_id = ' . $uid . ' LIMIT 1';
		$sql_list 	= 'SELECT * FROM {{user_share}} WHERE user_id = ' . $uid . ' ORDER BY share_id DESC';
		
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