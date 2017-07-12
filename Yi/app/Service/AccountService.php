<?php
namespace App\Service;

use Min\App;

class AccountService extends \Min\Service
{
	protected $db_key = 'user';
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
		if ('uid' === $type) {
			$type = 'user_id';
			$name = intval($name);
		} elseif (validate('phone', $name)) {
			$type = 'phone';
		} elseif (validate('email', $name)) {			
			$type = 'email';
			$name = safe_json_encode($name);
		} elseif (validate('user_name', $name)) {	 
			$type = 'user_name';
			$name = safe_json_encode($name);
		} else {	 
			return $this->error('账号格式错误', 30200);		
		}
		 
		$cache 	= $this->cache();
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
			 
			
			$sql = 'SELECT u.*, uw.wx_id, uw.open_id, uw.balance_index FROM {{user}} AS u LEFT JOIN {{user_wx}} AS uw ON u.user_id = uw.user_id WHERE u.'. $type. ' = '. $name .' LIMIT 1'; // pdo normal 
			$result	= $this->query($sql);
 			  
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {
			 
			return $this->success($result);
		} else {
			 
			return $this->error('账号不存在', 30206);
		}
	}
	
	/*
		shop account
	*/
	
	public function addUserByPhone($data) 
	{
		$check = $this->checkAccount($data['phone']);
		
		if (0 == $check['statusCode']) {
			return $this->error('该手机号码已被注册', 30205);
		} elseif ($check['statusCode'] != 30206) {
			return $check;
		}
		
		if ($data['passwordhash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 9])) {	
		
			$ins_data = [
				'phone' 		=> intval($data['phone']),
				'user_type' 	=> config_get('adv_balance_index', 1),
				'register_time' => intval($data['register_time']),
				'register_ip' 	=> intval($data['register_ip']),
				'password' 		=> "'" . $data['passwordhash']) . "'"
			];
		
			$sql = 'INSERT INTO {{user}} ' . build_query_insert($ins_data);
			
			$result =  $this->query($sql);
			
			watchdog($result);
			
			if ($result['id'] > 0) {
				$balance_data = [
					'user_id'		=> $result['id'],
					'balance' 		=> 0, 
					'share_paret' 	=> 0, 
					'team_part' 	=> 0,
					'drawing'		=> 0
				];
				
				$balance_sql = 'INSERT IGNORE INTO {{advertiser_balance' . $ins_data['user_type'] . '}} ' . build_query_insert($balance_data);
				
				$db->query($balance_sql);
			
				$this->cache()->delete($this->getCacheKey('phone', $data['phone']));//清理 注册缓存
				return $this->success(['user_id' => $result['id']]);
			} else {
				return $this->error('注册失败', 30204);
			}
			
		} else {
			throw new \Min\MinException('password_hash failed', 20104);
		}
	}
 
	public function addUserByWx($data) 
	{
		$wx_id 			= intval($data['wx_id']);
		$phone 			= intval($data['phone']);
		$balance_index 	= intval($data['balance_index']);
		
		if ($balance_index < 1 || $wx_id < 1 || !validate('open_id', $data['open_id'])) {
			return $this->error('参数错误', 30205);
		}
		
		$check = $this->checkAccount($phone);
		
		if (0 === $check['statusCode']) {
			if (!empty($check['body']['open_id']) || 2 === $check['body']['user_type']) {
				return $this->error('该手机号码已被绑定', 30205);
			} 
		} elseif ($check['statusCode'] != 30206) {
			return $check;
		}
 
		$db = $this->DBManager();
		
		try {
		
			$db->begin();
			
			if (30206 == $check['statusCode']) {
			
				$processed_data = [
					'user_type'		=> 0,
					'phone' 		=> $phone, 
					'register_time' => intval($data['register_time']), 
					'register_ip' 	=> intval($data['register_ip'])
				];
			 
				$sql = 'INSERT INTO {{user}} ' . build_query_insert($processed_data);

				$ins = $db->query($sql);
 
				$check['body']['user_id'] = $ins['id'];	
			}
			
			if ($check['body']['user_id'] > 0) {
			
				$open_id = safe_json_encode($data['open_id']);

				//$wx_sql = 'SELECT * FROM {{user_wx}} WHERE wx_id = '
				
				$sql2 = 'UPDATE {{user_wx}} SET user_id = ' . $check['body']['user_id'] . ' WHERE wx_id = ' . $wx_id . ' AND open_id = ' . $open_id . ' AND balance_index = ' . $balance_index . ' AND (user_id = 0 OR user_id is null) ';
				
				$upd = $db->query($sql2);
				
				if ($upd['effect'] > 0) {
				
					$balance_data = [
						'user_id'		=> $check['body']['user_id'],
						'balance' 		=> 0, 
						'share_paret' 	=> 0, 
						'team_part' 	=> 0,
						'drawing'		=> 0
					];
				
					$balance_sql = 'INSERT IGNORE INTO {{user_balance_' . $balance_index . '}} ' . build_query_insert($balance_data);
					
					$db->query($balance_sql);

					$db->commit();
					
					$this->cache()->delete($this->getCacheKey('wx_id', 	$wx_id)); 		//清理 缓存
					$this->cache()->delete($this->getCacheKey('open_id', $open_id));		//清理 缓存
					if (!empty($ins['id'])) $this->cache()->delete($this->getCacheKey('phone', $phone));	
					
					return $this->success(['user_id' => $check['body']['user_id'], 'wx_id' => $wx_id, 'phone' => $phone]);
				} 
			}
			
			$db->rollBack();
			return $this->error('失败', 1);
			
		} catch (\Throwable $t) {
			watchdog($t);
			$db->rollBack();
			return $this->error('失败', 1);
		}
	}
	
	public function login($params) 
	{
		$result = $this->checkAccount($params['name']);
		
		if ($result['statusCode'] !== 0) {
			return $result;
		}
		
		if (password_verify($params['password'], $result['body']['password'])) {					 
			return $this->success($result['body']);
		} else {
			return $this->error('帐号密码错误', 30201);
		}		
	}
	
	public function resetPwd($params) 
	{
		$result = $this->checkAccount($params['user_id'], 'uid');
		
		if ($result['statusCode'] !== 0) {
			return $result;
		}
		
		if (password_verify($params['password'], $result['body']['password'])) {					 
			$pwd = password_hash($params['newpwd'], PASSWORD_BCRYPT, ['cost' => 9]);
			$update = $this->query('UPDATE {{user}} SET password ="'. $pwd.'" WHERE user_id = ' . $result['body']['user_id']);
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
		if($user['user_id'] > 0) {
			
			session_regenerate_id();// 每次登陆都需要更换session id ;
			//if (!empty($user['nick'])) setcookie('nick', $user['nick'], 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 100, '/', COOKIE_DOMAIN);
			session_set('logined', 1);
			session_set('USER_ID', $user['user_id']);
			session_set('user', $user);
		}
	}
}