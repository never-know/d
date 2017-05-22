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
		if ('UID' === $type) {
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

			// mysqli prepare
			/* 
			$mark = ($arr['type'] == 'phone') ? 'd': 's';
			$sql = 'SELECT * FROM {user}  WHERE '.$arr['type'].' = ? ';
			$result	= $this->queryi($sql, $mark, [$arr['name']]);
			 */
			
			// mysqli normal 
			/*
			$sql = 'SELECT * FROM {user}  WHERE '. $arr['type']. ' = '. $arr['name'];
			$result	= $this->query($sql);
			*/
			// pdo 
			/*
			$sql = 'SELECT phone FROM {user}  WHERE '. $arr['type']. ' = :type Limit 1';
			$result	= $this->query($sql, [':type' => $arr['name']]);
			 */
			 
			// pdo normal 
			$sql = 'SELECT * FROM {user} WHERE '. $type. ' = '. $name .' LIMIT 1';
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
			
			$reg_result =  $this->query($sql);
			
			watchdog($reg_result);
			
			if ($reg_result['id'] > 0) {
				//清理 注册缓存
				//$this->cache()->delete($this->getCacheKey('phone', intval($data['phone'])));
				$this->initUser(['uid' => $reg_result['id']]);
				return $this->success();
			} else {
				return $this->error('注册失败', 30204);
			}
			
		} else {
			throw new \Min\MinException('password_hash failed', 20104);
		}
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
		$result = $this->checkAccount($params['uid'], 'UID');
		
		if ($result['statusCode'] !== 0) {
			return $result;
		}
		
		if (password_verify($params['pwd'], $result['body']['pwd'])) {					 
			$pwd = password_hash($params['newpwd'], PASSWORD_BCRYPT, ['cost' => 9]);
			$update = $this->query('update {user} set pwd ="'. $pwd.'" where uid = ' . $result['body']['uid']);
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
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			//if (!empty($user['nick'])) setcookie('nick', $user['nick'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			session_set('logined', 1);
			session_set('UID', $user['uid']);
			session_set('user', $user);
		}
	}
	
	private function getCacheKey($type, $value)
	{
		return '{account}:'.$type. ':'. $value;
	}
}