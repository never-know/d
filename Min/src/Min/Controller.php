<?php
namespace Min;

use Min\App;

class Controller
{	
	protected $sharedService = [];
	
	final public function __construct($action)
	{	
		$csrf_key = implode('_', [App::getModule(), App::getController(), App::getAction()]);
		$this->validToken($csrf_key);
		
		if (method_exists($this, 'onConstruct') === true) {
            $this->onConstruct();
        }
		
		$key = $action.'_'.(strtolower($_SERVER['REQUEST_METHOD'])?:'get');
		
		if (method_exists($this, $key)){
			$this->{$key}();
		} else {
			$this->error('无效请求', 404);
		}
		exit; 
	}
	
	final public function request($server, $params, $exit_on_error = true, $shared = false)
	{
		$concrete = explode('::',$server);
		
		if (empty($concrete[0]) || empty($concrete[1])) {
			$this->error('服务请求参数错误', 20101);
		}
		
		$class	= $concrete[0] .'Service';
		
		if (isset($this->sharedService[$class])) {
			$obj = $sharedService[$class];
		} else {
			try {
				$obj = new $class;	
			} catch (\Throwable $t) {
				app_exception($t);
			}
			
			if (true === $shared) {
				$this->sharedService[$class] = $obj;
			}
		}
		
		if (!empty($params['init'])) {
			$obj->init($params['init']);	
			unset($params['init']);
		} 
		
		try {	
		
			$time1 = microtime(true);
			$result = $obj->{$concrete[1]}($params);
			watchdog('controller time cost:'. (microtime(true) - $time1) * 1000 .'ms');
			if (isset($result['code']) && 0 !== $result['code'] &&  true === $exit_on_error) {
				$this->response($result);
			}
			
			return $result;
			
		} catch (\Throwable $t) {
			app_exception($t);
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

	final public function error($message, $code, $redirect = '')
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
	
	final public function validToken($value)
	{	
		if (!IS_GET && (empty($_POST['csrf_token']) || !valid_token($_POST['csrf_token'], $value))) {
			$this->error($value, 30101);
		}
	}

}