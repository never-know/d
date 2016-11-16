<?php
namespace App\Module;

use Min\App;

class BaseController
{
	public function $service = [];
	
	public function getService($name){
		return $this->service[$name]?:$this->service[$name] = new $name;
	}
}