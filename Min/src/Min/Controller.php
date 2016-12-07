<?php
namespace Min;

use Min\App;

class Controller
{	
	protected $sharedService = [];
	
	public function __construct($action)
	{	
		$this->validToken();
		
		$key = $action.'_'.(strtolower($_SERVER['REQUEST_METHOD'])?:'get');
		
		if (method_exists($this, $key)){
			$this->{$key}();
		}else{
			$this->error(404);
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
	
	final public function success($result = [], $layout = 'frame')
	{	
		$result['code'] = 0;		
		IS_AJAX  && ajax_return($result); 		
		IS_JSONP && jsonp_return($result);
		
		require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		exit;
	}
	
	final public function error($code, $message = '', $redirect = '')
	{	
		request_not_found($code, $message, $redirect);
	}
	
	
	final public function validToken(){
		
		if (IS_POST && (empty($_POST['crsf_token']) || !valid_token($_POST['crsf_token']))) {
			$this->error(30101);
		}
	}

}