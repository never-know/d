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
 
			$sql = 'SELECT * FROM {share_record} WHERE '. $type. ' = '. $name .' LIMIT 1';
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
		
			return $this->success($result);
			
		} else {
			 
			return $this->error('不存在', 30206);
		}
	}

	public function record($data) 
	{
		$check = $this->check($data['sid']);
		
		if (0 !== $check['statusCode'] || $check['body']['content_id'] != $data['id'] || $data['current_user'] == $check['body']['user_id']) {
		
			return $this->success();		// can not find share user then return directly
			$data['salary'] = $data['sid'] = $check['body']['user_id'] = 0;
		}
		
		$params 				= [];
		$params['viewer_id'] 	= intval($data['viewer_id']);
		$params['content_id'] 	= intval($data['id']);
		$params['share_user'] 	= intval($check['body']['user_id']);
		
		$sql_count = 'SELECT count(1) as count FROM {share_view} WHERE '. build_query_common(' AND ', $params). ' LIMIT 1';
		$count = $this->query($sql_count);
		if ($count['count'] > 2) {
			return $this->success(['userid' => $params['share_user']]);
		}
		 
		$params['view_time'] 	= intval($data['view_time'])?:time();
		$params['salary'] 		= intval($data['salary']);
		$params['share_id'] 	= json_encode($data['sid']);
		
		
		$sql = 'INSERT INTO {share_view} ' . build_query_insert($params);
		
		$result =  $this->query($sql);
		
		if ($result['id'] > 0) {
			return $this->success(['userid' => $params['share_user']]);
		} else {
			return $this->error('fail', 30204);
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