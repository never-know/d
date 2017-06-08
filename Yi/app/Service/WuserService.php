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

	private function checkAccount($name, $type = null) 
	{	
		if ('wxid' == $type) {
			$name = intval($name);
		} elseif (validate('openid', $name)) {
			$type = 'openid';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
 
		$cache 	= $this->cache();
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {

			$sql = 'SELECT w.wxid, w.pid, w.openid, w.subscribe, u.uid, u.phone  FROM {user_wx} AS w left join {user} AS u ON u.uid = w.userid WHERE  w.'. $type. ' = '. $name .' LIMIT 1';
			 
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result);
		}
		
		if (!empty($result)) {
			return $this->success($result);
		} else { 
			return $this->error('账号不存在', 30206);
		}
	}

	public function addUserByOpenid($data) 
	{
		if (!validate('openid', $data['openid']) || !in_array($data['subscribe'],[2,3]) || !isset($data['wip']) || empty($data['ctime'])) {
			return $this->error('参数错误', 30200);
		}

		$data['wip'] 	= intval($data['wip']);
		$data['ctime'] 	= intval($data['ctime']);
		
		$check = $this->checkAccount($data['openid']);
		 
		if (0 === $check['statusCode']) {
			if ($data['subscribe'] == 2) {
				return $this->success($check['body']);
			}
			
			$code = (($check['subscribe'] == 2 ) ? 30208 : (empty($check['phone']) ? 30205 : 30207));
			
		} elseif ($check['statusCode'] != 30206) {
			return $check;
		}

		$data['pid']	= max(intval($data['pid']), 0);
		
		if ( $data['pid'] > 0) {
			$parent = $this->checkAccount($data['pid'], 'wxid');
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
		
		if ($result != false) {
			if ($check['statusCode'] == 30206) {
				if (2 != $data['subscribe'])  $result['body'] = [];
				return $this->success($result['body']);
			} else {
				return $this->error('帐号已存在', $code);
			}
		} else {
			return $this->error('注册失败', 30204);
		} 
	}
	
	
	public function addUserByOpenidWhenSubscribe($data) 
	{
		if (!validate('openid', $data['openid']) ||$data['subscribe'] != 3 || !isset($data['wip']) || empty($data['ctime'])) {
			return $this->error('参数错误', 30200);
		}

		$data['wip'] 	= intval($data['wip']);
		$data['ctime'] 	= intval($data['ctime']);
		
		$check = $this->checkAccount($data['openid']);
		 
		if ((0 !== $check['statusCode'] || 2 == $data['subscribe']) && 30206 != $check['statusCode']) {
			return $check;	
		}
		
		if (0 === $check['statusCode'] && 2 == $data['subscribe']) {
			return $check;	
		}

		$data['pid']	= max(intval($data['pid']), 0);
		
		if ( $data['pid'] > 0) {
			$parent = $this->checkAccount($data['pid'], 'wxid');
			if (0 !== $parent['statusCode'] || empty($parent['body']['phone']) || empty($parent['body']['uid']) || $parent['body']['subscribe'] == 2 || $parent['body']['openid'] == $data['openid']) {
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
			
			if (empty($check['body']['wip'])) {
				$sql .= ' , wid = '. $data['wip'];
			}
			
			if ($check['body']['pid'] == 0 && $data['pid'] != 0) {
				$sql .= ', pid = '. $data['pid']; 
			}
			$sql .= ' WHERE id = ' .$check['body']['id'];
		}
		
		$result =  $this->query($sql);
		
		watchdog($result);
		
		if (empty($result)) {
			return $this->error('注册失败', 30204);
		}
		
		if ($check['statusCode'] == 30206) {
			return $this->success();
		} else {
			$code = (($check['subscribe'] == 2 ) ? 30208 : (empty($check['phone']) ? 30205 : 30207));
			return $this->error('帐号已存在', $code);
		}
		 
	}

	public function login($openid) 
	{
		return $this->checkAccount($openid);
		/*
		if ($result['statusCode'] !== 0) {
			return $result;
		} else {
			$this->initUser($result['body']);
			return $this->success();
		}
		*/		
	}
	
}