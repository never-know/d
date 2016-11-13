<?php
namespace App\Module\Www;

use Min\App;

class CaptchaController
{
	public function __construct($action) 
	{	
		if($action=='get'){
			$this->get();
		}elseif($action=='check'){
			$this->check();
		}
		exit;
	}
	
	private function get()
	{
		if( !preg_match('/^[a-z]+$/',$_GET['type']) ){
			trigger_error( 'captcha parameter error', E_USER_ERROR);
		}
		
		$code = new \Min\Captcha;
		$code->getCode($_GET['type']);
		
	}
	
	
	private function check()
	{ 
		if( !is_numeric($_GET['callback']) || !preg_match('/^[a-z]+$/',$_GET['type']) ){ 
			trigger_error( 'captcha parameter error', E_USER_ERROR);
		}
		 
		$code = new \Min\Captcha;
		retrieve('logger')->log(json_encode($_SESSION),'DEBUG');
		if( true === $code->checkCode($_GET['code'],$_GET['type']) ) {
			response(1);
		}else{
			response(0, '验证码错误');
		}
	}

}