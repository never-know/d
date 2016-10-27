<?php
namespace min\service;
use \min\inc\app;
class regist
{
	 

	public function adduserbyphone($phone,$pwd) {
	
	 
		$pwd = password_hash($pwd, PASSWORD_BCRYPT,['cost'=>10]);
		$sql = 'insert into user (`phone`,`pwd`) values(? ,?)';
		return app::mysqli('user#user')->query('insert',$sql,'ss',[$phone,$pwd]);
	}


}