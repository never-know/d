<?php
namespace App\Module\Www;

use Min\App;

class CaptchaController
{
	public function __construct($action) 
	{	
		if ($action == 'get') {
			$this->get();
		} elseif ($action == 'check') {
			$this->check();
		}
		exit;
	}
	
	private function get()
	{
		if (preg_match('/^[a-z]+$/',$_GET['type'])) {
			$code = new \Min\Captcha;
			$code->getCode($_GET['type']);
		}
	}
	
	
	private function check()
	{ 
		if (is_numeric($_GET['callback']) && preg_match('/^[a-z]+$/',$_GET['type'])) { 
			$code = new \Min\Captcha;
			if (true === $code->checkCode($_GET['code'], $_GET['type'])) {
				response(1);
			}
		}
		response(0, '验证码错误');	
	}

}