<?php
namespace App\Module\Www;

use Min\App;

class UserController
{
	public function __construct($args)
	{
		if ($args=='login') {
			$this->login();
		} elseif ($args=='reg') {
			$this->reg();
		}
	}

	private function login()
	{
		layout('type-login');
	}
	private function reg()
	{
		layout('type-login');
	}



}