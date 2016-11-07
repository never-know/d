<?php
namespace App\Module\Passport;

use Min\App;

class AccountController
{    

    public function __construct($action) 
	{  
		switch ($action) {
		
			case 'phone':
				$this->phone();
				break;
			case 'email':
				$this->email();
				break;
			case 'name':
				$this->name();
				break;
			default:
				exit;
		
		}
		exit;
		 
    }
	
// jsonp

	private function phone()
	{
		if (!validate('phone', $_GET['phone'])) {
		
			usrerror( 0,'手机号码格式错误');
			
		} else {

			$result = $this->callService($_GET['phone'], 'phone');
			
			response($result);
		}
	
	}
	
	
	private function callService($name,$type){
	
		$service = new \App\Service\AccountService;
		return $service->checkAccount($name,$type);
	
	}
	
}