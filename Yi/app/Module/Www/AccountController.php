<?php
namespace App\Module\Www;

use Min\App;

class AccountController extends \Min\Controller
{ 
	public function onConstruct() 
	{
		$this->checkLogin();
	}
	
	public function index_get()
	{
		if (PHP_SESSION_NONE === session_status()) {
			App::initSession(true);  
		} 
		$this->layout('type-login');
	}
	
	public function repwd_post()
	{		 
		$pwd 		= $_POST['pwd'];
		$newpwd 	= $_POST['newpwd'];
		$newpwd2 	= $_POST['newpwd2'];
		
		if (empty($newpwd) || empty($pwd) || empty($newpwd2) ) {	
			$this->error('密码不能为空', 30208);		
		}
		
		if ($newpwd != $newpwd2) {	
			$this->error('两次新密码不相同', 30208);		
		}

		$params = ['uid' => session_get('UID'), 'pwd' = $pwd, 'newpwd' => $newpwd];
		$result =  $this->request('\\App\\Service\\Account::resetPwd', $params);
		 
		if (0 === $result['statusCode']) {
			$this->success('修改成功');
		} else {
			$this->error($result['message'], $result['statusCode']);
		}	 	 
	}
}