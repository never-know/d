<?php
namespace App\Service;

use Min\App;

class AccountService extends \Min\Service
{
	// 是否清理 checkaccount 产生的缓存
	private $clean_cache = false;
	public $db_key = 'user';
	public $cache_key = 'login';
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

	public function checkAccount($arr) 
	{	
		if (empty($arr['type'])) {			
			if (validate('phone',$arr['name'])) {	
				$arr['type'] = 'phone';			
			} elseif (validate('email',$arr['name'])) {			
				$arr['type'] = 'email';		
			} elseif (validate('username',$arr['name'])) {	 
				$arr['type'] = 'username';	
			} else {	 
				$this->error('账号格式错误', 30200);		
			}
			
		} elseif (!in_array($arr['type'], ['phone','email','name'])) {
			throw new \Min\MinException('帐号类型错误', 20100);		
		}
		$cache 	= $this->cache('account');
		$key 	= $this->getCacheKey($arr['type'], $arr['name']);
		$result = $cache->get($key);
			
		if (empty($result) || $cache->getDisc() === $result) {
			
			if($arr['type'] == 'phone') $arr['name'] = intval($arr['name']);

			// mysqli prepare
			
			/* 
			$mark = ($arr['type'] == 'phone') ? 'd': 's';
			$sql = 'SELECT * FROM {user}  WHERE '.$arr['type'].' = ? ';
			$result	= $this->queryi($sql, $mark, [$arr['name']]);
			 */
			
			// mysqli normal 
			 
			$sql = 'SELECT * FROM {user}  WHERE '. $arr['type']. ' = '. $arr['name'];
			$result	= $this->query($sql);

			// pdo 
			/*
			$sql = 'SELECT phone FROM {user}  WHERE '. $arr['type']. ' = :type Limit 1';
			$result	= $this->query($sql, [':type' => $arr['name']]);
			 */
			// pdo normal 
			 /*
			$sql = 'SELECT * FROM {user}  WHERE '. $arr['type']. ' = '. $arr['name'];
			$result	= $this->query($sql);
 			 */
			if (!empty($result)) $cache->set($key, $result, 7200);
		}
		
		if (!empty($result)) {	
			return $this->success($result);
		} else {
			return $this->error('账号不存在', 30206);
		}
	}

	public function addUserByPhone($regist_data) {
	
		if ($regist_data['pwd'] = password_hash($regist_data['pwd'], PASSWORD_BCRYPT, ['cost' => 9])) {	
		
			$sql = 'INSERT INTO {user} (phone, regtime, regip, pwd) VALUES ('. 
			implode(',', [intval($regist_data['phone']), intval($regist_data['regtime']), intval($regist_data['regip']), "'". $regist_data['pwd']. "')"]);
			
			$reg_result =  $this->query($sql);
			watchdog($reg_result);
			if ($reg_result > 1) {
				//清理 注册缓存
				$this->cache()->delete($this->getCacheKey('phone', intval($regist_data['phone'])));
				return $this->success(['uid' => $reg_result]);
			} else {
				return $this->error('注册失败', 30204);
			}
		} else {
			throw new \Min\MinException('password_hash failed', 20104);
		}
	}
	
	private function getCacheKey($type, $value)
	{
		
		return '{account}:'.$type. ':'. $value;
	}

}