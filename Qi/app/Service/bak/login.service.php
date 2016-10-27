<?php
namespace min\service;
use \min\inc\app;
class login
{

	public function checkpwd($name) {
	
		if(validate('phone',$name)){
			$sql = 'SELECT uid,name, email, pwd FROM user  WHERE phone =?';
         }elseif(validate('email',$name)){
			$sql = 'SELECT uid,name, email, pwd FROM user  WHERE email =?';
		 }elseif(validate('username',$name)){
			$sql = 'SELECT uid,name, email, pwd FROM user  WHERE name =?';
		 }else{
			app::usrerror(0,'用户名或密码错误',['loginname'=>$name]);
			
		 }

		$sql_result	= app::mysqli('user#user')->query('single',$sql,'s',[$name]);

		return $sql_result;
		
	}
	public function showcaptcha($name=''){
		 
		 $var1 = empty($name) ? 0 : app::cache('loginerror')->get($name);
		 if( $var1 >9){
			app::usrerror( 0 ,'账户已锁定，请2小时后再登录');
		 }
		 $var2 = empty($_SESSION['loginerror']) ? 0 : $_SESSION['loginerror'] ;
		 
		 return ( $var1 > 2 || $var2 > 7 );
	}
	
}