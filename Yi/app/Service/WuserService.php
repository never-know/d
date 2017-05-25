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
		if ($type = 'id') {
			$name = intval($name);
		} elseif (validate('openid', $name)) {
			$type = 'openid';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
 
		$cache 	= $this->cache('account');
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {

			$sql = 'SELECT w.id, w.pid, w.openid, w.subscribe, u.uid, u.phone  FROM {user_wx} AS w left join {user} AS u ON u.uid = w.userid WHERE  w.'. $type. ' = '. $name .' LIMIT 1';
			 
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
			return $this->success($result);
		} else { 
			return $this->error('账号不存在', 30206);
		}
	}

	public function addUserByOpenid($data) 
	{
		if ( !validate('openid', $data['openid']) || !in_array($data['subscribe'],[2,3]) || !isset($data['wip']) || empty($data['ctime']) ) {
			return $this->error('参数错误', 30200);
		}
		$data['wip'] 	= intval($data['wip']);
		$data['ctime'] 	= intval($data['ctime']);
		
		$check = $this->checkAccount($data['openid']);
		 
		if (0 === $check['statusCode']) {
			if ($data['subscribe'] == 2) {
				$this->initUser($check['body']);
				return $this->success();
			}
			$code = (($check['subscribe'] == 2 ) ? 30208 : (empty($check['phone']) ? 30205 : 30207));
			
		} elseif ($check['statusCode'] != 30206) {
			return $check;
		}

		$data['pid']	= max(intval($data['pid']), 0);
		
		if ( $data['pid'] > 0) {
			$parent = $this->checkAccount($data['pid'], 'id');
			if (0 !== $parent['statusCode'] || empty($parent['phone']) || empty($parent['uid']) || $parent['subscribe'] == 2 || $parent['openid'] == $data['openid']) {
				$data['pid'] = 0;
			}
		}
		
		if ($check['statusCode'] == 30206) {
		
			$inserts =  [
				$data['wip'], 
				$data['pid'], 
				$data['subscribe'], 
				$data['ctime'], 
				'"'. $data['openid']. '")'
			];
			
			$sql = 'INSERT INTO {user_wx} (wip, pid, subscribe, ctime, openid) VALUES ( '. implode(',', $inserts) .' ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)';
		} else {
		
			$sql = 'UPDATE {user_wx} set subscribe = 3 ';
			
			if (empty($check['wip'])) {
				$sql .= ' , wid = '. $data['wip'];
			}
			
			if ($check['pid'] == 0 && $data['pid'] != 0) {
				$sql .= ', pid = '. $data['pid']; 
			}
			$sql .= ' WHERE id = ' .$check['body']['id'];
		}
		
		$result =  $this->query($sql);
		
		watchdog($result);
		
		if ($result !== false) {
			if ($check['statusCode'] == 30206) {
				if ($data['subscribe']) $this->initUser($result['body']);
				return $this->success();
			} else {
				return $this->error('帐号已存在', $code);
			}
		} else {
			
			return $this->error('注册失败', 30204);
		}
	}

	public function login($params) 
	{
		$result = $this->checkAccount($params);
		
		if ($result['statusCode'] !== 0) {
			return $result;
		} else {
			$this->initUser($result['body']);
			return $this->success();
		}	
	}
 
	private function initUser($user)
	{ 
		session_regenerate_id();
		
		if (!empty($user['id'])) {
			session_set('openid_id', $user['id']);
		} 
		if (!empty($user['uid'])) {
			session_set('UID', $user['uid']);
		}
		session_set('user', $user);	 
	}
	
	private function getCacheKey($type, $value)
	{
		return '{account}:'.$type. ':'. $value;
	}
}