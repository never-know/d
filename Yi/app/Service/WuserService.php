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

	public function checkAccount($name) 
	{	
		if ( is_numeric($name)) {
			$type  = 'id';
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

			$sql = 'SELECT w.id, w.pid, w.subscribe, w.openid, u.uid, u.phone  FROM {user_wx} AS w left join {user} AS u ON u.uid = w.userid WHERE  w.'. $type. ' = '. $name .' LIMIT 1';
			 
			$result	= $this->query($sql, false);
 			  
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
		if (!empty($data['pid'])) {
			$parent = $this->checkAccount($data['pid']);
			if (0 != $parent['statusCode'] || empty($parent['phone']) || $parent['openid'] == $data['openid']) {
				$data['pid'] = 0;
			}
		} else {
			$data['pid'] = 0;
		}
		
		$check = $this->checkAccount($data['openid']);
		
		if (0 === $check['statusCode']) {
			$code = (($check['body']['subscribe'] == 2) ? '30208' : (empty($check['phone']) ? 30205 : 30207));
			$sql = 'UPDATE {user_wx} SET subscribe = 3, pid = ( CASE pid WHEN 0 THEN ' . $data['pid'] . ' ELSE pid end )  WHERE id = ' . $check['body']['id'];	
		} else {
			$sql = 'INSERT INTO {user_wx} (wip, pid, ctime, subscribe, openid) VALUES ('. 
		
			implode(',', [intval($data['wip']), intval($data['pid']), intval($data['ctime']), 3, '"'. $data['openid']. '")']);
		
			/*
				$sql = 'INSERT INTO {user_wx} (wip, pid,ctime, subscribe,openid) VALUES ('. 
				implode(',', [intval($data['wip']), intval($data['pid']), intval($data['ctime']), 3, '"'. $data['openid']. '")']) . ' ON DUPLICATE KEY  UPDATE id = LAST_INSERT_ID(id), subscribe = 3 ,pid = ( CASE pid WHEN 0 THEN ' . $data['pid'] . ' ELSE pid end ) ';
			*/
		}
		
		$result =  $this->query($sql);
		
		watchdog($result);
		
		if ($result === false) {
			return $this->error('注册失败', 30204);
		} else {
			if (isset($code)) {
				return $this->error('微信帐号已存在', $code);
			} else {
				return $this->success();
			}
		}
		 
	}

	public function login($params) 
	{
		$result = $this->checkAccount($params, true);
		
		if ($result['statusCode'] !== 0) {
			return $result;
		} else {
			$this->initUser($result['body']);
			return $this->success();
		}	
	}
	

	private function initUser($user)
	{ 
		if (!empty($user['uid'])) {
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			//setcookie('nick', $user['nick'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			session_set('logined', 1);
			session_set('UID', $user['uid']);
		} 
		
		session_set('user', $user);	 
	}
	
	private function getCacheKey($type, $value)
	{
		return '{account}:'.$type. ':'. $value;
	}
}