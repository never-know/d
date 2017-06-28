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

			$sql = 'SELECT w.wx_id, w.parent_id, w.open_id, w.subscribe_status, u.user_id, u.phone, u.register_time  FROM {user_wx} AS w left join {user} AS u ON u.user_id = w.user_id WHERE  w.'. $type. ' = '. $name .' LIMIT 1';
			 
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
	*/
	public function addUserByOpenid($data) 
	{
		if (!validate('open_id', $data['open_id']) || !in_array($data['subscribe_status'],[2,3]) || !isset($data['wx_ip']) || empty($data['subscribe_time'])) {
			return $this->error('参数错误', 30200);
		}

		$data['wx_ip'] 			= intval($data['wx_ip']);
		$data['subscribe_time'] = intval($data['subscribe_time']);
		
		$check = $this->checkAccount($data['open_id']);
		 
		if (0 !== $check['statusCode'] && 30206 != $check['statusCode']) {
			return $check;	
		}
		 
		if (0 === $check['statusCode'] && 2 == $data['subscribe']) {
			return $check;	
		}
		 
		$data['parent_id']	= max(intval($data['parent_id']), 0);
		
		if ( $data['parent_id'] > 0) {
		
			$parent = $this->checkAccount($data['parent_id'], 'wx_id');
			
			if (empty($parent['body']['user_id']) || $parent['body']['open_id'] == $data['open_id']) {
				$data['parent_id'] = 0;
			}
		}
		
		if (30206 == $check['statusCode']) {
		
			$inserts =  [
				$data['wx_ip'], 
				$data['parent_id'], 
				$data['subscribe_status'], 
				$data['subscribe_time'], 
				json_encode($data['open_id'])
			];
			
			$sql = 'INSERT INTO {user_wx} (wx_ip, parent_id, subscribe_status, subscribe_time, open_id) VALUES ( '. implode(',', $inserts) .') ON DUPLICATE KEY UPDATE wx_id = LAST_INSERT_ID(wx_id)';
			
		} else {
		
			$sql = 'UPDATE {user_wx} set subscribe_status = 3 ';
			
			if (empty($check['body']['wx_ip'])) {
				$sql .= ' , wx_ip = '. $data['wx_ip'];
			}
			
			if ($check['body']['parent_id'] == 0 && $data['parent_id'] != 0) {
				$sql .= ', parent_id = '. $data['parent_id']; 
			}
			$sql .= ' WHERE wx_id = ' .$check['body']['wx_id'];
		}
		
		$result =  $this->query($sql);
		
		watchdog($result);
		
		if (empty($result)) {
			return $this->error('注册失败', 30204);
		}
		
		if ($check['statusCode'] == 30206) {
			if (2 == $data['subscribe_status'])  $result['wx_id'] = $result['id'];
			return $this->success($result);
		} else {
			$code = (($check['subscribe_status'] == 2 ) ? 30208 : (empty($check['phone']) ? 30205 : 30207));
			return $this->error('帐号已存在', $code);
		}
		 
	}

	public function login($openid) 
	{
		return $this->checkAccount($openid);
	}
	
	public function member($pid)
	{
		$pid	= intval($pid);
		
		if ($pid < 0) {
			return $this->error('参数错误', 30000);
		}

		$sql_count 	= 'SELECT count(1) AS count FROM {user_wx} WHERE parent_id = ' . $pid . ' LIMIT 1';
		$sql_list	= 'SELECT u1.wx_id, u2.phone, count(u3.parent_id) as children FROM {user_wx} AS u1 
			LEFT JOIN {user} AS u2 ON u1.user_id = u2.user_id
			LEFT JOIN {user_wx} AS u3 ON u1.wx_id = u3.parent_id 
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