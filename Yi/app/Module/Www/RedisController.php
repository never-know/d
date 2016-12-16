<?php
namespace App\Module\Www;

use Min\App;

class RedisController extends \Min\Controller
{
	public function sms_get($name)
	{
		$regkey = '{sms:}reg'. $name;
		echo   CM('sms')->get($regkey);
	}
	public function set($name, $value)
	{
		$regkey = '{sms:}'. $this->type. $name;
		return   CM('sms')->set($regkey, $value);
	}
	 
}