<?php
namespace App\Service;

use Min\App;

class WuserService extends \Min\Service
{
	protected $cache_key = 'account';
	 
	/**
	* 检测账号是否存在
	*
	* @param string $name 账号
	* @param string $type : phone/email/name				
	*  
	* @return int
	*   2 账号不存在
	*	1 账号存在
	*/

	public function checkAccount($name, $type = null) 
	{	
		if ('wx_id' == $type) {
			$name = intval($name);
		} elseif (validate('open_id', $name)) {
			$type = 'open_id';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
 
		$cache 	= $this->cache();
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {

			$sql = 'SELECT w.wx_id, w.balance_index, w.parent_id, w.subscribe_status, w.open_id,  u.user_id, u.user_type, u.phone, u.register_time, u.avatar, u.nickname FROM {{user_wx}} AS w LEFT JOIN {{user}} AS u ON u.user_id = w.user_id WHERE  w.'. $type. ' = '. $name .' LIMIT 1';
			 
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result);
		}
		
		if (!empty($result)) {
			return $this->success($result);
		} else { 
			return $this->error('账号不存在', 30206);
		}
	}

	/*
		when subscribe 	subscribe  => 3
		when view 		subscribe  => 2
		
		params :
			wx_ip		
			open_id
			parent_id
			subscribe_time
			subscribe_status
		
		return
			code 30208 first
				 30205 not first no phone
				 30207 not first has phone
			
		
	*/
	
	public function addUserByOpenid($data) 
	{
		$data['subscribe_status'] = intval($data['subscribe_status']);
		
		if (!validate('open_id', $data['open_id']) || !in_array($data['subscribe_status'], [2,3]) || !isset($data['wx_ip'])) {
			return $this->error('参数错误', 30200);
		}

		$data['wx_ip'] 			= intval($data['wx_ip']);
		$data['subscribe_time'] = intval($data['subscribe_time']);
		
		$check = $this->checkAccount($data['open_id']);
		 
		if (1 != $check['statusCode'] && 30206 != $check['statusCode']) {
			return $check;	
		}
		 
		if (1 == $check['statusCode'] && 2 == $data['subscribe_status']) {
			return $check;	
		}
		
		if (empty($check['body']['user_id'])) {
		
			$data['parent_id']		= max(intval($data['parent_id']), 0);
			$data['balance_index']	= 1;
			
			if ($data['parent_id'] > 0) {
			
				$parent = $this->checkAccount($data['parent_id'], 'wx_id');
				
				if (empty($parent['body']['user_id']) || $parent['body']['open_id'] == $data['open_id']) {
					$data['parent_id'] = 0;
				} else {
					$data['balance_index']	= intval($parent['body']['balance_index']);
				}
			}
			
			// 1000 分割 , no parent and not exsit
			
			if (3 == $data['subscribe_status'] && 0 == $data['parent_id']) {
				$sql_count = 'SELECT count(1) AS count FROM {{user_wx}} WHERE parent_id = 0 LIMIT 1';
				$count = $this->query($sql_count);
				if ($count['count'] > 0) {
					$data['balance_index'] = intval($count['count']/100) + 1;
					/*
					if ($data['balance_index'] > 1 && config_get('max_user_balance_index', 1) < $data['balance_index']) {
					
						$tables = [
							'user_balance' 		=> $data['balance_index'],
							'user_balance_log' 	=> $data['balance_index'],
							'user_share' 		=> $data['balance_index'],
							'user_share_view' 	=> $data['balance_index'],
						];
					
						$this->query($tables);
						watchdog('user balance index out', 'WARNING');
					}
					*/
				}
			}
		}
		
		if (30206 == $check['statusCode']) {

			$inserts =  [
				'wx_ip'	 			=> $data['wx_ip'], 
				'parent_id' 		=> $data['parent_id'],
				'balance_index'		=> $data['balance_index'],
				'subscribe_status' 	=> $data['subscribe_status'], 
				'subscribe_time' 	=> $data['subscribe_time'], 
				'open_id'			=> json_encode($data['open_id']),
			];
			
			$sql = 'INSERT INTO {{user_wx}} ' . build_query_insert($inserts) .' ON DUPLICATE KEY UPDATE wx_id = LAST_INSERT_ID(wx_id)';
			
		} else {
		
			$sql = 'UPDATE {{user_wx}} set subscribe_status = 3 , subscribe_time = '. $data['subscribe_time'];
			
			if (empty($check['body']['wx_ip'])) {
				$sql .= ' , wx_ip = '. $data['wx_ip'];
			}
 
			// subscribe relationship first
			
			if (empty($check['body']['user_id'])) {
				$sql .= ', parent_id = ' . $data['parent_id'] . ', balance_index = ' . $data['balance_index']; 
			}
			
			$sql .= ' WHERE wx_id = ' .$check['body']['wx_id'];
		}
		
		$result =  $this->query($sql);
		
		watchdog($result);
		
		if (empty($result)) {
			return $this->error('注册失败', 30204);
		}
		
		
		// 清理缓存
		$del_keys = [];
		$del_keys[] = $this->getCacheKey('open_id', $data['open_id']);		 
		if (1 == $check['statusCode']) {
			$del_keys[] = $this->getCacheKey('wx_id', $check['body']['wx_id']);		 
			if (!empty($check['body']['user_id'])) {
				$del_keys[] = $this->getCacheKey('user_id', $check['body']['user_id']);  
			}
			if (!empty($check['body']['phone'])) {
				$del_keys[] = $this->getCacheKey('phone', $check['body']['phone']);		 
			}
		}
		
		$this->cache()->delete($del_keys);
		
		/* account not exist*/
		
		if ($check['statusCode'] == 30206 || $check['body']['subscribe_status'] == 2) {
			return $this->success($result);
		} else {
			$code = (empty($check['body']['user_id']) ? 30205 : 30207);
			return $this->error('帐号已存在', $code);
		}	 
	}
	
	public function unsubscribe($open_id)
	{
		if (!validate('open_id', $open_id)) {
			return $this->error('参数错误', 30200);
		}
		
		$check = $this->checkAccount($open_id);
		
		if (!empty($check)) {
			$sql = 'UPDATE {{user_wx}} SET subscribe_status = 4 WHERE open_id = ' . safe_json_encode($open_id);
			$result = $this->query($sql);
			if ($result['effect'] == 1) {
				$del_keys 	= [];
				$del_keys[] = $this->getCacheKey('open_id', $open_id);
				$del_keys[] = $this->getCacheKey('wx_id', $check['body']['wx_id']);
				if (!empty($check['body']['user_id'])) {
					$del_keys[] = $this->getCacheKey('user_id', $check['body']['user_id']);
				}
				if (!empty($check['body']['phone'])) {
					$del_keys[] = $this->getCacheKey('phone', $check['body']['phone']);
				}
				$this->cache()->delete($del_keys);		//清理 缓存

				return $this->success();
			} else {
				return $this->error('操作失败', 20000);
			}
		} else {
			return $this->error('参数错误', 20000);
		}
	}

	public function login($open_id) 
	{
		return $this->checkAccount($open_id);
	}
	
	public function member($pid)
	{
		$pid	= intval($pid);
		
		if ($pid < 0) {
			return $this->error('参数错误', 30000);
		}

		$sql_count 	= 'SELECT count(1) AS count FROM {{user_wx}} WHERE parent_id = ' . $pid . ' LIMIT 1';
		$sql_list	= 'SELECT u1.wx_id, u1.user_id, u2.phone, count(u3.parent_id) as children, b.benefit_1, b.benefit_2 FROM {{user_wx}} AS u1 
			LEFT JOIN {{user}} AS u2 ON u1.user_id = u2.user_id
			LEFT JOIN {{user_wx}} AS u3 ON u1.wx_id = u3.parent_id 
			LEFT JOIN {{user_balance}} AS b ON u1.user_id = b.user_id 
			WHERE u1.parent_id = ' . $pid . ' GROUP BY u1.wx_id ORDER BY u1.wx_id DESC';
	  
		return $this->commonList($sql_count, $sql_list);

	}
	
	public function test() {
	
		$inserts =  [
				123, 
				123, 
				2, 
				123, 
				json_encode('o3GfAwTiDSmQaPo_0Vl4Ks-d-5ts')
			];
			
		$sql = 'INSERT INTO {user_wx} (wip, pid, subscribe, ctime, openid) VALUES ( '. implode(',', $inserts) .') ON DUPLICATE KEY UPDATE wxid = LAST_INSERT_ID(wxid)';
		
		$result = $this->query($sql);
		
		var_dump($result);exit;
	}
	
}