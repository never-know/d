<?php
namespace App\Module\Www;

use Min\App;

class UserController
{
	public function __construct($action)
	{
		$key = $action.':'.strtolower($_SERVER['REQUEST_METHOD']);
		
		switch ($key) {
			
			case 'login:get' :
				$this->login();
				break;
			case 'regist:get' :
				$this->login();
				break;
			case 'login:post' :
				$this->login();
				break;
			default :
				request_not_found();
		}
		
	}

	private function login()
	{
		layout('type-login');
	}
	private function reg()
	{
		if(!isset($_SESSION)){
			App::initSession(true);  
		} 
		
		layout('type-login');
	}



}