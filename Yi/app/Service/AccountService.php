<?php
namespace App\Service;

use Min\App;

class AccountService extends \Min\Service
{
	private $db_key = 'user';
	private $cache_key = 'account';
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
		if ('uid' === $type) {
			$name = intval($name);
		} elseif (validate('phone', $name)) {
			$type = 'phone';
		} elseif (validate('email', $name)) {			
			$type = 'email';
			$name = safe_json_encode($name);
		} elseif (validate('username', $name)) {	 
			$type = 'username';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
		 
		$cache 	= $this->cache('account');
		$key 	= $this->getCacheKey($type, $name);
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {

		
			/* 
			$mark = ($arr['type'] == 'phone') ? 'd': 's';
			$sql = 'SELECT * FROM {user}  WHERE '.$arr['type'].' = ? '; 	// mysqli prepare
			$result	= $this->queryi($sql, $mark, [$arr['name']]);
			 */
			
			
			/*
			$sql = 'SELECT * FROM {user}  WHERE '. $arr['type']. ' = '. $arr['name']; // mysqli normal 
			$result	= $this->query($sql);
			*/
			
			/*
			$sql = 'SELECT phone FROM {user}  WHERE '. $arr['type']. ' = :type Limit 1'; // pdo 
			$result	= $this->query($sql, [':type' => $arr['name']]);
			 */
			 
			
			$sql = 'SELECT * FROM {user} WHERE '. $type. ' = '. $name .' LIMIT 1'; // pdo normal 
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
			 
			return $this->success($result);
		} else {
			 
			return $this->error('账号不存在', 30206);
		}
	}

	public function addUserByPhone($data) 
	{
		$check = $this->checkAccount($data['phone']);
		
		if (0 == $check['statusCode']) {
			return $this->error('该手机号码已被注册', 30205);
		} elseif ($check['statusCode'] != 30206) {
			return $check;
		}
		
		if ($data['pwd'] = password_hash($data['pwd'], PASSWORD_BCRYPT, ['cost' => 9])) {	
		
			$sql = 'INSERT IGNORE INTO {user} (phone, regtime, regip, pwd) VALUES ('. 
			
			implode(',', [intval($data['phone']), intval($data['regtime']), intval($data['regip']), '"'. $data['pwd']. '")']);
			
			$result =  $this->query($sql);
			
			watchdog($result);
			
			if ($result['id'] > 0) {
				//$this->cache()->delete($this->getCacheKey('phone', intval($data['phone'])));//清理 注册缓存
				$this->initUser(['uid' => $result['id']]);
				return $this->success();
			} else {
				return $this->error('注册失败', 30204);
			}
			
		} else {
			throw new \Min\MinException('password_hash failed', 20104);
		}
	}
	
	public function addUserByWx($data) 
	{
		$check = $this->checkAccount($data['phone']);
		
		if (0 == $check['statusCode']) {
			return $this->error('该手机号码已被注册', 30205);
		} elseif ($check['statusCode'] != 30206) {
			return $check;
		}
		
		$wxid = intval($data['wxuser_id']);
		if ($wxid < 1 || empty($data['openid'][24])) {
			return $this->error('参数错误', 30205);
		}

		$processed_data = [
			'phone' 	=> $data['phone'], 
			'regtime' 	=> intval($data['regtime']), 
			'regip' 	=> intval($data['regip'])
		];
		
		$sql = 'INSERT INTO {user} (phone, regtime, regip) VALUES ('. implode(',', $processed_data) . ')';

		$this->DBManager()->transaction_start();
		$ins = $this->query($sql);
		
		if ($ins['id'] > 0) {
		
			$openid = safe_json_encode($data['openid']); 
			
			$sql2 = 'UPDATE {user_wx} SET userid = ' .$ins['id'] . ' WHERE id = ' . $wxid . ' and openid = ' . $openid ;
			
			$upd = $this->query($sql2);
			
			if ($upd['effect'] > 0) {
				$this->DBManager()->transaction_commit();
				$this->cache()->delete($this->getCacheKey('wxid', 	$wxid)); 	//清理 缓存
				$this->cache()->delete($this->getCacheKey('openid', $openid));		//清理 缓存
				return $this->success(['uid' => $ins['id'], 'wxid' => $wxid]);
			} 
		}
		
		$this->DBManager()->transaction_rollback();
		return $this->error('失败', 1);
	}
	
	public function login($params) 
	{
		$result = $this->checkAccount($params['name']);
		
		if ($result['statusCode'] !== 0) {
			return $result;
		}
		
		if (password_verify($params['pwd'], $result['body']['pwd'])) {					 
			$this->initUser($result['body']);
			return $this->success();
		} else {
			return $this->error('帐号密码错误', 30201);
		}		
	}
	
	public function resetPwd($params) 
	{
		$result = $this->checkAccount($params['uid'], 'uid');
		
		if ($result['statusCode'] !== 0) {
			return $result;
		}
		
		if (password_verify($params['pwd'], $result['body']['pwd'])) {					 
			$pwd = password_hash($params['newpwd'], PASSWORD_BCRYPT, ['cost' => 9]);
			$update = $this->query('UPDATE {user} SET pwd ="'. $pwd.'" WHERE uid = ' . $result['body']['uid']);
			if ($update) {
				return $this->success('修改成功');
			} else {
				return $this->error('修改失败', 30201);
			}
		} else {
			return $this->error('密码错误', 30201);
		}		
	}
	
	private function initUser($user)
	{ 
		if($user['uid'] > 0) {
			
			session_regenerate_id();// 每次登陆都需要更换session id ;
			//if (!empty($user['nick'])) setcookie('nick', $user['nick'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			session_set('logined', 1);
			session_set('UID', $user['uid']);
			session_set('user', $user);
		}
	}
}