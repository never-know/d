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

	public function checkAccount($r) {

		if( !in_array($r['type'],['phone','email','name'])) {
		
			throw new \Exception('帐号类型错误');	
			
		}else{	
			$key		= $r['type'] .md5($r['name']);
			$cache_result 	= CM('checkAccount')->get($key);
			$this->clean_cache 	= true;
			
			if (empty($cache_result)){	
				$sql = 'SELECT 1 FROM user  WHERE '. $r['type'].' = ? limit 1 ';
				$sql_result	= DM('user')->query('single',$sql,'s',[$r['name']]);
				
				if (!empty($sql_result)) {
					$result = 1 ;
				} elseif ($sql_result === null) {
					$result = 2 ;	
				}
				app::cache()->set($key, $result, 3600);
			}
			return $result;
		}
	}

	public function addUserByPhone($regist_data) {
	
		$regist_data['pwd'] = password_hash($regist_data['pwd'], PASSWORD_BCRYPT, ['cost' => 9]);		
		$sql = 'insert into user (phone, pwd, regtime, regip) values(?,?,?,?)';
		$reg_result =  DM('user')->query('insert',$sql,'isii',$regist_data);
		if ($reg_result > 1) {
			//清理 注册缓存
			if($this->clean_cache) CM('checkaccount')->delete('{phone:}'.md5($_user['phone']));
			$this->success('注册成功', ['uid' => $reg_result]);
		} else {
			$this->error(301100, '注册失败');
		}
	}
	
	public function checkPwd($name) {
	
		if(validate('phone',$name)){
			$sql = 'SELECT uid,name, email, phone, pwd FROM user  WHERE phone = \''.$name.'\'';
         }elseif(validate('email',$name)){
			$sql = 'SELECT uid,name, email, pwd , phone FROM user  WHERE email = \''.$name.'\'';
		 }elseif(validate('username',$name)){
			$sql = 'SELECT uid,name, email, phone, pwd FROM user  WHERE name = \''.$name.'\'';
		 }else{
			app::usrerror(0,'用户名或密码错误',['loginname'=>$name]);	
		 }

		$sql_result	= DM('user')->query('single',$sql);
		
		return $sql_result;	
	}
	
}