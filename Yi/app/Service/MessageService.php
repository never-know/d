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

		$sql_count = 'SELECT count(m.id) as count FROM {{message}} AS m 
			LEFT JOIN {{message_read}} AS r 
			ON m.uid = 0 AND r.message_id = m.id AND r.uid = '. $p . 
			' WHERE (m.uid = ' . $p . ' OR  m.uid = 0) AND m.post_time > '. session_get('user')['regtime']. ' LIMIT 1'
	  
		$count = $this->query($sql_count);
		
		if (!isset($count['count'])) {
			return $this->error('加载失败', 20106);
		}  
		
		$page 	= \result_page($count['count']);
		
		if ($page['current_page'] > $page['total_page']) {

			$list = [];
			
		} else {
			
			$sql = 'SELECT m.id, m.uid, m.read_time , m.type, m.title, r.read_time as readed FROM {{message}} AS m 
			LEFT JOIN {{message_read}} AS r 
			ON m.uid = 0 AND r.message_id = m.id AND r.uid = '. $p . 
			' WHERE (m.uid = ' . $p . ' OR  m.uid = 0) AND m.post_time > '. session_get('user')['regtime'] .' ORDER BY m.id DESC ' . $page['limit'];
		
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
 
		$param['uid'] 	= intval($data['uid']);
		$param['type'] 	= intval($data['type']);
		
		if ( $param['uid'] < 0 || $param['type'] < 1) {
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
	
	public function read($data) 
	{
		$param = [];
 
		$param['id'] 	= intval($data['id']);
		$param['uid'] 	= intval($data['uid']);
		
		if ($param['uid'] < 0 || $param['type'] < 1) {
			$this->error('参数错误', 20107);
		}
		
		$param['read_time']	= time();
		
		if (empty($data['private'])) {
			$param['message_id'] = $param['id'];
			unset($param['id']);
			$sql = 'INSERT INTO {{message_read}} ' . query_build_insert($param);
		} else {
			$sql = 'UPDATE {{message}} SET read_time = ' . $param['read_time'] . ' WHERE ' . query_build_common(' AND ', $param);
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
 
		$param['uid'] 	= intval($data['uid']);
		$param['id'] 	= intval($data['id']);
		$param['time'] 	= intval($data['time']);
		
		if ($param['uid'] < 1 || $param['type'] < 1 || $param['time'] < 1) {
			$this->error('参数错误', 20107);
		}
		
		$sql = 'SELECT m.*, ifnull(mr.read_time, 0) as readed FROM {{message}} AS m LEFT JOIN {{message_read}} mr ON m.uid = 0 AND m.id = mr.message_id AND mr.uid = ' . $param['uid'] . ' WHERE m.id = ' . $param['id'] .' LIMIT 1';
		
		$info = $this->query($sql);
		
		if (empty($info)) {
			$this->error('', 2000);
		}
		
		if (($info['uid'] > 0 && $info['uid'] != $param['uid']) || $info['post_time'] < $param['time']) {
			$this->error('没有权限查看', 2000);
		}
		
		if ($info['uid'] > 0 && 0 == $info['read_time']) {
			$param['private'] = 1;
			$this->read($param);
		} elseif (0 == $info['uid']  && 0 == $info['readed']) {
			$this->read($param);
		}
		
		return $this->success($info);
	
	}
	
	
}