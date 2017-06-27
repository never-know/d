<?php
namespace App\Service;

use Min\App;

class BalanceService extends \Min\Service
{
	public function list($p)
	{
		$param			= [];
		$user_id 		= intval($p['user_id']??0);
		 
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}

		$sql_count = 'SELECT count(1) as count FROM {{balance_log}} WHERE user_id = ' .$user_id . '  LIMIT 1'
	  
		$count = $this->query($sql_count);
		
		if (!isset($count['count'])) {
			return $this->error('加载失败', 20106);
		}  
		
		$page 	= \result_page($count['count']);
		
		if ($page['current_page'] > $page['total_page']) {

			$list = [];
			
		} else {
		
			$sql = 'SELECT * FROM {{balance_log}} WHERE ' . build_query_common(' AND ', $param) . ' ORDER BY log_id DESC ' . $page['limit'];
		
			$list = $this->query($sql);
			
			if (false === $list) {
				return $this->error('加载失败', 20106);
			} 	
		}
		
		return $this->success(['page' => $page, 'list' => $list]);
	}
	
	public function summarylist($p)
	{
		$user_id 		= intval($p['user_id']??0);

		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}
 
		$today	= strtotime(date('Y-m-d') . ' 23:59:59');
		 
		$_REQUEST['page_size']	= 3600*24*30;
		
		$page 	= \result_page(($today - 1497456000));

		if ($page['current_page'] > $page['total_page']) {
		
			$list = [];
			
		} else {
		
			$begin 	= $today - (($page['current_page'] - 1) * $page['page_size']);
			$end 	= $begin - $page['page_size'];
		
			$sql = 'SELECT sum(money) AS money, post_day FROM {{balance_log}} WHERE user_id = '.$user_id . ' AND post_day <= ' . $begin . ' AND post_day > ' . $end . ' AND balance_type >= 1 AND balance_type <= 3 GROUY BY post_day ORDER BY log_id DESC';
	  
			$list = $this->query($sql);
			
			if (false === $list) {
				return $this->error('加载失败', 20106);
			} 	
		}
		
		return $this->success(['page' => $page, 'list' => $list]);
	}
	
	public function daylist($p)
	{
		$user_id 		= intval($p['user_id']??0);
		 
		if ($user_id < 1) {
			return $this->error('参数错误', 30000);
		}

		$start = intval($p['start']);
		
		if ($start < 1497456000) {
			return $this->error('参数错误', 30000);
		}
 
		$param				= [];
		$param['user_id'] 	= $user_id;
		$param['post_day'] 	= $start;
		
		$sql_count = 'SELECT count(1) as count FROM {{balance_log}} WHERE ' . build_query_common(' AND ', $param) . ' AND balance_type >= 1 AND balance_type <= 3 LIMIT 1'
	  
		$count = $this->query($sql_count);
		
		if (!isset($count['count'])) {
			return $this->error('加载失败', 20106);
		}  
		
		$page 	= \result_page($count['count']);
		
		if ($page['current_page'] > $page['total_page']) {

			$list = [];
			
		} else {
		
			$sql = 'SELECT * FROM {{balance_log}} WHERE ' . build_query_common(' AND ', $param) . ' ORDER BY log_id DESC ' . $page['limit'];
		
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
	
	private function read($data) 
	{
		$param = [];
 
		$param['id'] 		= intval($data['id']);
		$param['uid'] 		= intval($data['uid']);
		$param['read_time'] = intval($data['read_time']);
		
		if ($param['uid'] < 0 || $param['type'] < 1 || $param['read_time'] < 1) {
			$this->error('参数错误', 20107);
		}
 
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
 
		$param['uid'] 		= intval($data['uid']);
		$param['id'] 		= intval($data['id']);
		$param['regtime'] 	= intval($data['regtime']);
		$param['read_time'] = intval($data['read_time']);
		
		if ($param['uid'] < 1 || $param['type'] < 1 || $param['regtime'] < 1 || $param['read_time'] < 1) {
			return $this->error('参数错误', 20107);
		}
		
		$sql = 'SELECT m.*, ifnull(mr.read_time, 0) as readed FROM {{message}} AS m LEFT JOIN {{message_read}} mr ON m.uid = 0 AND m.id = mr.message_id AND mr.uid = ' . $param['uid'] . ' WHERE m.id = ' . $param['id'] .' LIMIT 1';
		
		$info = $this->query($sql);
		
		if (empty($info)) {
			return $this->error('参数错误', 2000);
		}
		
		if (($info['uid'] > 0 && $info['uid'] != $param['uid']) || $info['post_time'] < $param['regtime']) {
			return $this->error('没有权限查看', 2000);
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