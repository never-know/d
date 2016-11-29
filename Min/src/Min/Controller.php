<?php
namespace Min;

use Min\App;

class Controller
{	
	protected $sharedService = [];
	
	public function __construct()
	{
		set_error_handler([$this, 'appError']);
		set_exception_handler([$this, 'appException']);
		register_shutdown_function([$this, 'appTails']);
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

	final public function response($result = [], $layout = 'frame')
	{	
		if (is_numeric($result)) $result['status'] = $result;
		
		defined('IS_AJAX') 	&& IS_AJAX  && ajax_return($result); 		
		defined('IS_JSONP') && IS_JSONP && jsonp_return($result);
	
		if(isset($result['redirect'])) {
			redirect($result['redirect']);
			exit;
		}

		if ( -1 == $result['status']) {
			$path = '/common/request_error_found';
		}
		require VIEW_PATH.'/layout/'.$layout.VIEW_EXT;
		exit;
	}

	final public function appTails()
	{
		// fatal errors 
		$error = error_get_last();
		$log = App::getService('Logger');
		if ($error['type'] == E_ERROR) {
			$error['title'] = 'Fatal Error';
			$message = error_message_format($error);
			$log->log($message, 'CRITICAL', debug_backtrace(), 'default');
		}
		$log->record();
	}

	final public function appError($errno, $errstr, $errfile, $errline)
	{	
		$level = [  E_WARNING => 1,
					E_NOTICE => 1,
					E_USER_WARNING => 1,
					E_USER_NOTICE => 1,
					E_STRICT => 1,
					E_DEPRECATED => 1,
					E_USER_DEPRECATED => 1
				];
				
		$type = isset($level[$errno]) ? 'WARNING' : 'ERROR'; 

		$me	=  [	
			'title'		=> 'Unexpected Error', 
			'message'	=> $errstr, 
			'file'		=> $errfile, 
			'line'		=> $errline, 
			'type'		=> $errno
		];
		
		watchdog(error_message_format($me), $type, [], 'default');
		
		if ($type == 'ERROR') {
			$this->response(-1);
		}
		return true;
	}

	final public function appException($e)
	{	
		$me  =  [	
			'title'		=> 'Unexpected Expection', 
			'message'	=> $e->getMessage(), 
			'file'		=> $e->getFile(), 
			'line'		=> $e->getLine(),
			'type'		=> $e->getCode()
		];
		watchdog(error_message_format($me), 'CRITICAL', debug_backtrace(), 'default');
		$this->response(-1); 
	}

}