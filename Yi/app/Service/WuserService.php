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

	/*
		when subscribe 	subscribe  => 3
		when view 		subscribe  => 2
	*/
	public function addUserByOpenid($data) 
	{
		if (!validate('openid', $data['openid']) || !in_array($data['subscribe'],[2,3]) || !isset($data['wip']) || empty($data['ctime'])) {
			return $this->error('参数错误', 30200);
		}

		$data['wip'] 	= intval($data['wip']);
		$data['ctime'] 	= intval($data['ctime']);
		
		$check = $this->checkAccount($data['openid']);
		 
		if (0 !== $check['statusCode'] && 30206 != $check['statusCode']) {
			return $check;	
		}
		 
		if (0 === $check['statusCode'] && 2 == $data['subscribe']) {
			return $check;	
		}
		 
		$data['pid']	= max(intval($data['pid']), 0);
		
		if ( $data['pid'] > 0) {
		
			$parent = $this->checkAccount($data['pid'], 'wxid');
			
			if (empty($parent['body']['uid']) || $parent['body']['openid'] == $data['openid']) {
				$data['pid'] = 0;
			}
		}
		
		if (30206 == $check['statusCode']) {
		
			$inserts =  [
				$data['wip'], 
				$data['pid'], 
				$data['subscribe'], 
				$data['ctime'], 
				json_encode($data['openid'])
			];
			
			$sql = 'INSERT INTO {user_wx} (wip, pid, subscribe, ctime, openid) VALUES ( '. implode(',', $inserts) .') ON DUPLICATE KEY UPDATE wxid = LAST_INSERT_ID(wxid)';
			
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
			if (2 == $data['subscribe'])  $result['wxid'] = $result['id'];
			return $this->success($result);
		} else {
			$code = (($check['subscribe'] == 2 ) ? 30208 : (empty($check['phone']) ? 30205 : 30207));
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
		
		$sql = 'SELECT u1.wxid, count(u2.pid) as children FROM {user_wx} AS u1 LEFT JOIN {user_wx} AS u2 ON u1.wxid = u2.pid WHERE u1.pid = ' . $pid . ' GROUP BY u1.wxid';
		
		$result = $this->query($sql);
		
		if ($result) {
			return $this->success($result);
		} else {
			return $this->error('加载失败', 122222);
		}

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