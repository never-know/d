<?php
namespace App\Service;

use Min\App;

class MessageService extends \Min\Service
{

	public function list($p)
	{
		$p = intval($p);
		
		if ($p < 0) {
			return $this->error('参数错误', 30000);
		}

		$sql_count = 'SELECT count(m.message_id) as count FROM {{message}} AS m 
			LEFT JOIN {{message_read}} AS r 
			ON m.user_id = 0 AND r.message_id = m.message_id AND r.user_id = '. $p . 
			' WHERE (m.user_id = ' . $p . ' OR  m.user_id = 0) AND m.post_time > '. session_get('user')['register_time']. ' LIMIT 1'
	  
		$count = $this->query($sql_count);
		
		if (!isset($count['count'])) {
			return $this->error('加载失败', 20106);
		}  
		
		$page 	= \result_page($count['count']);
		
		if ($page['current_page'] > $page['total_page']) {

			$list = [];
			
		} else {
			
			$sql = 'SELECT m.message_id, m.user_id, m.read_time , m.message_type, m.title, r.read_time as readed FROM {{message}} AS m 
			LEFT JOIN {{message_read}} AS r 
			ON m.user_id = 0 AND r.message_id = m.message_id AND r.user_id = '. $p . 
			' WHERE (m.user_id = ' . $p . ' OR  m.user_id = 0) AND m.post_time > '. session_get('user')['register_time'] .' ORDER BY m.message_id DESC ' . $page['limit'];
		
			$list = $this->query($sql);
			
			if (false === $list) {
				return $this->error('加载失败', 20106);
			} 
		}

		return $this->success(['page' => $page, 'list' => $list]);
	}
	
	

	public function add($data) 
	{
		$param = [];
 
		$param['user_id'] 		= intval($data['user_id']);
		$param['message_type'] 	= intval($data['message_type']);
		
		if ( $param['user_id'] < 0 || $param['message_type'] < 1) {
			$this->error('参数错误', 20107);
		}

		$param['post_time'] = intval($data['post_time'])?:time();
		$param['read_time'] = 0;
		$param['title'] 	= ':title';
		$param['content'] 	= ':content';
		
		$bind = [
			':title' 	=> $data['title'], 
			':content'	=> $data['content']
		];
		
		$sql = 'INSERT INTO {{message}} ' . query_build_insert($param);

		$result = $this->query($sql, $bind);

		if ($result['id'] > 0) {
			return $this->success();
		} else {
			return $this->error('fail', 30204);
		}
	}
	
	private function read($data) 
	{
		$param = [];
 
		$read_time				= intval($data['read_time']);
		$param['user_id'] 		= intval($data['user_id']);
		$param['message_id'] 	= intval($data['message_id']);

		if ($param['user_id'] < 0 || $param['message_type'] < 1 || $read_time < 1) {
			$this->error('参数错误', 20107);
		}
 
		if (empty($data['private'])) {
			$param['read_time'] 	= $read_time;
			$sql = 'INSERT INTO {{message_read}} ' . query_build_insert($param);
		} else {
			
			$sql = 'UPDATE {{message}} SET read_time = ' . $read_time. ' WHERE ' . query_build_common(' AND ', $param);
		}
		
		$result = $this->query($sql);

		if ($result['effect'] > 0) {
			return $this->success();
		} else {
			return $this->error('fail', 30204);
		}
	}
	
	public function info($data)
	{
		$param = [];
 
		$param['user_id'] 		= intval($data['user_id']);
		$param['read_time'] 	= intval($data['read_time']);
		$param['message_id'] 	= intval($data['message_id']);
		$param['register_time'] = intval($data['register_time']);
		
		
		if ($param['user_id'] < 1 || $param['message_type'] < 1 || $param['register_time'] < 1 || $param['read_time'] < 1) {
			return $this->error('参数错误', 20107);
		}
		
		$sql = 'SELECT m.*, ifnull(mr.read_time, 0) as readed FROM {{message}} AS m LEFT JOIN {{message_read}} mr ON m.user_id = 0 AND m.message_id = mr.message_id AND mr.user_id = ' . $param['user_id'] . ' WHERE m.message_id = ' . $param['message_id'] .' LIMIT 1';
		
		$info = $this->query($sql);
		
		if (empty($info)) {
			return $this->error('参数错误', 2000);
		}
		
		if (($info['uid'] > 0 && $info['user_id'] != $param['user_id']) || $info['post_time'] < $param['register_time']) {
			return $this->error('没有权限查看', 2000);
		}
		
		if ($info['user_id'] > 0 && 0 == $info['read_time']) {
			$param['private'] = 1;
			$this->read($param);
		} elseif (0 == $info['user_id']  && 0 == $info['readed']) {
			$this->read($param);
		}
		
		return $this->success($info);
	
	}
	
	
}