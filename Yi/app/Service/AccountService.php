<?php
namespace App\Service;

use Min\App;

class AccountService
{
	// 是否清理 checkaccount 产生的缓存
	private $clean_cache = false;
	
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
			throw new \Exception('帐号类型错误', 20100);		
		}
		
		$key	= md5(implode(':',$arr));
		$result = CM('Login')->get($key);
			
		if (empty($result)) {		
			$mark = ($arr['type'] == 'phone') ? 'i': 's';
			$sql = 'SELECT uid, name, email, phone, pwd FROM user  WHERE '.$arr['type'].' = ? ';
			$result	= DM('user')->query('single', $sql, $mark, [$arr['name']]);
		}
		if (!empty($result)) {	
			CM('Login')->set($key, $result);
			$this->success($result);
		} else {
			$this->error('账号不存在', 30206);
		}
	}

	public function addUserByPhone($regist_data) {
	
		$regist_data['pwd'] = password_hash($regist_data['pwd'], PASSWORD_BCRYPT, ['cost' => 9]);	
		
		$sql = 'insert into user (phone, pwd, regtime, regip) values('. intval($regist_data['phone']).',\''.$regist_data['pwd'].',\''.intval($regist_data['regtime']).','.intval($regist_data['regip']).')';
		
		$reg_result =  DM('user')->query('insert',$sql);
		
		if ($reg_result > 1) {
			//清理 注册缓存
			if($this->clean_cache) CM('checkaccount')->delete('{phone:}'.md5($_user['phone']));
			$this->success(['uid' => $reg_result], '注册成功');
		} else {
			$this->error('注册失败', 30204);
		}
	}
	
	public function checkPwd($arr) {
	
		if( !in_array($arr['type'],['phone','email','name'])) {
		
			throw new \Exception('帐号类型错误', 20100);	
			
		}
		
		$mark = ($arr['type'] == 'phone') ? 'i': 's';
		
		$sql = 'SELECT uid, name, email, phone, pwd FROM user  WHERE '.$arr['type'].' = ? ';
       
		$sql_result	= DM('user')->query('single', $sql, $mark, [$arr['name']]);
		
		return $sql_result;	
	}
	
}