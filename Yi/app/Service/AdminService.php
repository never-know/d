<?php
namespace App\Service;

use Min\App;

class AdminService extends \Min\Service
{
	 
	public function index() 
	{
		for ($i=0; $i< 101; $i++) {
			$sql = 'create table yi_user_balance_'.$i .' like yi_user_balance';
			
			
		}
	}
	 
	 
}