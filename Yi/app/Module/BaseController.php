<?php
namespace App\Module;

use Min\App;

class BaseController
{	

	protected $sharedService = [];
	
	public function request($server, $params, $construct = null, $shared = false){
	
		if (empty($server)) {
			return null;
		}
		
		$concrete = explode('::',$server);
		
		if (empty($concrete[0]) || empty($concrete[1])) {
			return null;
		}
		
		$class	= $concrete[0] .'Service';
		$method = $concrete[1];
		
		if (isset($this->sharedService[$class])) {
			$obj = $sharedService[$class];
		} else {
			try {
				$obj = new $class;	
			} catch (\Throwable $t) {
				return null;
			}
			if (true === $shared) {
				$this->sharedService[$class] = $obj;
			}
		}
		
		if (!empty($construct)) {
			$obj->init($construct);	
		} 
		
		try {
			return $obj->{$method}($params);	
		} catch (\Throwable $t) {
			return null;
		}
	}	
}