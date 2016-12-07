<?php
namespace Min;

use Min\App;

class Controller
{	
	protected $sharedService = [];
	
	public function __construct($action)
	{	
		$this->validToken('form_'.$action);
		
		$key = $action.'_'.(strtolower($_SERVER['REQUEST_METHOD'])?:'get');
		
		if (method_exists($this, $key)){
			$this->{$key}();
		}else{
			$this->error(404);
		}
		exit; 
	}
	
	final public function request($server, $params, $exit_when_error = true, $shared = false)
	{
		$concrete = explode('::',$server);
		
		if (empty($concrete[0]) || empty($concrete[1])) {
			$this->error(20500, 'request 参数错误');
		}
		
		$class	= $concrete[0] .'Service';
		
		if (isset($this->sharedService[$class])) {
			$obj = $sharedService[$class];
		} else {
			try {
				$obj = new $class;	
			} catch (\Throwable $t) {
				$this->error($t->getCode(), $t->getMessage());
			}
			if (true === $shared) {
				$this->sharedService[$class] = $obj;
			}
		}
		
		if (!empty($params['init'])) {
			$obj->init($construct);	
			unset($params['init']);
		} 
		
		try {			
			$result = $obj->{$concrete[1]}($params);
			
			if (isset($result['code']) && 0 !== $result['code'] &&  true === $exit_when_error) {
				$this->response($result);
			}
			
			return $result;
			
		} catch (\Throwable $t) {
			$this->error($t->getCode(), $t->getMessage());
		}
	}
	final public function layout($layout = 'frame')
	{	
		require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		exit;
	}
	
	final public function success($result = [], $layout = 'frame')
	{	
		$result['code'] = 0;		
		$this->response($result, $layout);
	}

	final public function error($code, $message = '', $redirect = '')
	{	
		request_not_found($code, $message, $redirect);
	}
	
	final public function response($result = [], $layout = 'frame')
	{		
		IS_AJAX  && ajax_return($result); 		
		IS_JSONP && jsonp_return($result);
		
		require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		exit;
	}
	
	final public function validToken($value){
		
		if (IS_POST && (empty($_POST['crsf_token']) || !valid_token($_POST['crsf_token']))) {
			$this->error(30101);
		}
	}

}