<?php
namespace Min;

use Min\App;

class Error 
{	
	public static function errorMessage($error)
	{
		$message = '{ ['
			.	$error['title']
			.	': '
		//	.	rtrim($error['message'],PHP_EOL)
			.	$error['message']
			.	'] in file ['
			.	$error['file']
			.	']  at line ['
			.	$error['line']
			.	'] [error code/type: '
			.	$error['type']
			.	'] }';
		
		return $message;

	}
	
	public static function appTails()
	{
		// fatal errors 
		$error = error_get_last();
		$log = App::getService('Logger');
		if ($error['type'] == E_ERROR) {
			$error['title'] = 'Fatal Error';
			$message = self::errorMessage($error);
			$log->log($message, 'CRITICAL', debug_backtrace(), 'default');
		}
		$log->record();
	}

	public static function appError($errno, $errstr, $errfile, $errline)
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

		$message = self::errorMessage([	'title'		=> 'Unexpected Error', 
									'message'	=> $errstr, 
									'file'		=> $errfile, 
									'line'		=> $errline, 
									'type'		=> $errno
								]);
		
		App::getService('Logger')->log($message, $type, [], 'default');
		
		if ($type == 'ERROR') {
			response(-1);
		}
		return true;
	}

	public static function appException($e)
	{	
		$message = self::errorMessage([	'title'		=> 'Unexpected Expection', 
									'message'	=> $e->getMessage(), 
									'file'		=> $e->getFile(), 
									'line'		=> $e->getLine(),
									'type'		=> $e->getCode()
								]);
		App::getService('Logger')->log($message, 'CRITICAL', debug_backtrace(), 'default');
		response(-1); 
	}

	public static function usrError($code = 0, $msg = '', $level = 'INFO', $extra = [], $channel = '')
	{
		App::getService('Logger')->log($msg, $level, $extra, $channel);		
		if ($code == -999) return;
		response($code, $msg);
		exit;
	}
}