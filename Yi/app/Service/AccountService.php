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
			$result 	= retrieve('cacheManager', 'checkAccount')>get($key);
			$this->clean_cache 	= true;
			
			if( empty($result) ){	
				$sql = "SELECT 1 FROM user  WHERE  $r['type'] = ? limit 1";
				$sql_result	= App::db('user#user')->query('single',$sql,'s',[$r['name']]);
				if( !empty($sql_result) ){
					$result = 1 ;
				}elseif( $sql_result === null){
					$result = 2 ;	
				}else{
					trigger_error('system error', E_USER_ERROR);
				}
				app::cache()->set($key,$result,3600);
			}
			return $result;
		}
	}

	public function addUserByPhone($phone,$pwd) {
	
		$pwd = password_hash($pwd, PASSWORD_BCRYPT,['cost'=>10]);
		$sql = 'insert into user (phone,pwd) values(? ,?)';
		return app::mysqli('user#user')->query('insert',$sql,'ss',[$phone,$pwd]);
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

		$sql_result	= app::mysqli('user#user')->query('single',$sql);
		
		return $sql_result;	
	}
	
	public function initUser($user){
	
		if($user['uid']>0){
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			 
			$nickname = empty($user['name']) ? $user['phone'] : $user['name'];
			
			app::usrerror(-999,$nickname,$user);
			setcookie('nickname',$nickname,0,'/',COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged',1, time()+ ini_get('session.gc_maxlifetime')-10,'/',COOKIE_DOMAIN);
			$_SESSION['logined'] = true;
			$_SESSION['UID'] = $user['uid'];

			//清理 注册缓存
			if($this->clean_cache)
			app::cache('checkaccount')->delete('{phone:}'.md5($_user['phone']));
	
		}
	}



}