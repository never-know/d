<?php
namespace Min;

use Min\App;

class Controller
{	
	const EXITNONE  = 0;
	
	const EXITALL 	= 1; 
	
	const EXITERROR = 2;
	
	const EXITOK 	= 3;

	protected $sharedService = [];
	
	protected $cache_key = 'default';
	
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
	
	/*
	 *	params: 
	 *		$server : CLASS::METHOD,
	 *		$params : (init)
	 *		$exit   : 0   1  2 on_error  3 on
	 *
	 */
	
	final public function request($server, $params = null, $exit_on_error = Controller::EXITNONE, $shared = false)
	{
		$concrete = explode('::',$server);
		
		if (empty($concrete[0])) {
			$this->error('服务请求参数错误', 20101);
		}
		
		$class	= $concrete[0] .'Service';
		if (isset($this->sharedService[$class])) {
			$obj = $this->sharedService[$class];
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
		
		if (isset($params['init'])) {
			$obj->init($params['init']);	
			unset($params['init']);
			if (empty($params)) {
				$params = null;
			}
		}
		
		if (empty($concrete[1])) {
			return $obj;
		}

		if (is_array($params) && 1 == count($params)) {
			$params = reset($params);
		}
		
		try {	
			record_time('service start:'. $server);
			
			$result = $obj->{$concrete[1]}($params);
			
			record_time('service end:'. $server);
			
			if (empty($result)) {
				$this->error('返回丢失', 20101);
			}
			
			switch ($exit_on_error) {
				case self::EXITALL :
				case self::EXITOK :
					if (0 === $result['statusCode']) {
						$this->success($result['message']);
					}
				case self::EXITERROR :
					if (0 !== $result['statusCode']) {
						$this->error($result['message'], $result['statusCode']);
					}
				
				case self::EXITNONE :
				default	:
					return $result;
			}
 
		} catch (\Throwable $t) {
			app_exception($t);
		}
	}
	
	final public function layout($result, $layout = 'layout_frame')
	{	
		require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		exit;
	}
	
	final public function success($body = [], $layout = null)
	{	
		$result = ['statusCode' => 0, 'message' => '操作成功'];
 
		if (!empty($body)) {
			if (is_string($body)) {
				$result['message'] = $body;
			} elseif (is_array($body)) {
				$result['body'] = $body;
			}
		}
		 
		final_response($result, $layout);
	}

	final public function error($message, $code, $redirect = null, $return_type = null)
	{	
		request_error_found($code, $message, $redirect, $return_type);
	}
	
	final public function response($result = [], $layout = null)
	{		
		final_response($result, $layout);
	}
	
	final public function validToken($value)
	{	
		if ((!IS_GET || IS_JSONP) && (empty($_POST['csrf_token']) || !valid_token($_POST['csrf_token'], $value))) {
			$this->error('表单已过期', 30101);
		}
	}
	
	final public function cache($key = null)
	{
		if (!empty($key)) {
			$this->cache_key = $key;
		}
		
		$cache_setting = config_get('cache');
		$value = $cache_setting[$this->cache_key] ?? $cache_setting['default'];
		return App::getService($value['bin'], $value['key']);
	}

}