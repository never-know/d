<?php
namespace App\Module\Www;

use Min\App;

class UserController extends \App\Module\Www\BaseController
{
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