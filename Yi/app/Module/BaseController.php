<?php
namespace App\Module;

use Min\App;

class BaseController
{	

	protected $sharedService = [];
	
	public function request($server, $params, $construct = null, $shared = false)
	{
		if (empty($server)) {
			return null;
		}
		
		$concrete = explode('::',$server);
		
		if (empty($concrete[0]) || empty($concrete[1])) {
			return null;
		}
		
		$class	= $concrete[0] .'Service';
		
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
			return $obj->{$concrete[1]}($params);	
		} catch (\Throwable $t) {
			return null;
		}
	}

	public function response(array $result = [], $layout = 'frame'){
   
		defined('IS_AJAX') 	&& IS_AJAX  && ajax_return($result); 		
		defined('IS_JSONP') && IS_JSONP && jsonp_return($result);
	
		if(isset($result['redirect'])) {
			redirect($result['redirect']);
			exit;
		}
	
		require APP_PATH.'/View/layout/'.$layout.VIEW_EXT;
		exit;
	
	}

	public function view($result, $path = '')
	{
		if (empty($path)) {
			$path =  App::getModule().'/'.  App::getController().'/'.  App::getAction();
		}
		require APP_PATH.'/View/'.$path.VIEW_EXT;
	}

}