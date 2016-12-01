<?php
namespace Min;

use Min\App;

class Controller
{	
	protected $sharedService = [];
	
	public function __construct($action)
	{	
		$key = $action.'_'.(strtolower($_SERVER['REQUEST_METHOD'])?:'get');
		
		if (method_exists($this, $key)){
			$this->{$key}();
		}else{
			$this->response(404);
		}
		exit; 
	}
	
	final public function request($server, $params, $construct = null, $shared = false)
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

	final public function response($result = null, $layout = 'frame')
	{	
		if ($result == 404) {
			request_not_found();
		} elseif ($result == 500) {
			request_error_found();
		} else {
			
			defined('IS_AJAX') 	&& IS_AJAX  && ajax_return($result); 		
			defined('IS_JSONP') && IS_JSONP && jsonp_return($result);
		
			if(isset($result['redirect'])) {
				redirect($result['redirect']);
				exit;
			}
		
			require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		}
		exit;
	}

}