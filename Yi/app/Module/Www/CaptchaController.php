<?php
namespace App\Module\Www;

use Min\App;

class CaptchaController extends \Min\Controller
{
	public function get_get()
	{
		if (preg_match('/^[a-z]+$/',$_GET['type'])) {
			$code = new \Min\Captcha;
			$code->getCode($_GET['type']);
		}
	}
	
	public function check_get()
	{ 
		if (is_numeric($_GET['callback']) && preg_match('/^[a-z]+$/',$_GET['type'])) { 
			$code = new \Min\Captcha;
			if (true === $code->checkCode($_GET['code'], $_GET['type'])) {
				$this->success();
			}
		}
		$this->error('验证码错误', 30102);	
	}

}