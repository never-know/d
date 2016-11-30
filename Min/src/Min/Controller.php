<?php
namespace Min;

use Min\App;

class Controller
{	
	protected $sharedService = [];
	
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

	final public function response($result = [], $layout = 'frame')
	{	
		if (is_numeric($result)) $result['status'] = $result;
		
		defined('IS_AJAX') 	&& IS_AJAX  && ajax_return($result); 		
		defined('IS_JSONP') && IS_JSONP && jsonp_return($result);
	
		if(isset($result['redirect'])) {
			redirect($result['redirect']);
			exit;
		}
		
		// 读缓存
		
		
		
		require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		exit;
	}

}