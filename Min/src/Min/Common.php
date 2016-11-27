<?php

use Min\App;

function autoload($class)
{
	// new \min\service\login;
	// new \min\module\passport\login;
	$path 	= strtr($class, '\\', '/');
	$path_info 	= explode('/', $path, 2);
	if ('App' == $path_info[0]) {	
		$file	= APP_PATH .'/'. $path_info[1] . PHP_EXT;
		
	} elseif ('Min' == $path_info[0]) {
		$file	= MIN_PATH . '/' . $path . PHP_EXT;
	} else {
		return;
	}
	if (is_file($file)) {
		require $file;
	}else{
		throw new \Exception($file.' can not be autoloaded');
	}	
}
  
function ip_address() 
{
	static  $ip_address = '';
	Global $reverse_proxy_addresses;
	if (!isset($ip_address)) {
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if ( $conf['reverse_proxy']==1 ) {
   
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				// If an array of known reverse proxy IPs is provided, then trust
				// the XFF header if request really comes from one of them.
				// $reverse_proxy_addresses = variable_get('reverse_proxy_addresses', array());

				// Turn XFF header into an array.
				$forwarded = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

				// Trim the forwarded IPs; they may have been delimited by commas and spaces.
				$forwarded = array_map('trim', $forwarded);

				// Tack direct client IP onto end of forwarded array.
				$forwarded[] = $ip_address;

				// Eliminate all trusted IPs.
				$untrusted = array_diff($forwarded, $reverse_proxy_addresses);

				// The right-most IP is the most specific we can trust.
				$ip_address = array_pop($untrusted);
			}
		}
	}

	return $ip_address;
}
	
function error_message($error)
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

function request_not_found() 
{	
	response(0, '请求错误', ERROR_PAGE);	
}
	
function request_error_found($arr) 
{
	if (defined('IS_AJAX') && IS_AJAX) {
		ajax_return($arr);	 
	} elseif (defined('IS_JSONP') && IS_JSONP) {
		if (is_numeric($_GET['callback'])) {
			echo 'callback'.$_GET['callback'].'(-1);';
		}
	} else {
		header('Location: '. ERROR_PAGE);
	}
	exit();
}
	 
function redirect($url, $time=0, $msg='') 
{
//多行URL地址支持
	$url        = str_replace(array('\n', '\r'), '', $url);
	$url 		= strip_tags($url);
	if (empty($msg))
		$msg   = "系统将在{$time}秒之后自动跳转到{$url}！";
	if (!headers_sent()) {
		if (0 === $time) {
			header('Location: ' . $url);
		} else {
			header("refresh:{$time};url={$url}");
			echo($msg);
		}
		exit();
	} else {
		$str  = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if ($time != 0)
			$str .= $msg;
		exit($str);
	}
}

function response($code = 0, $msg = '', $flag = 0)
{
	$result = ['status' => $code];
	if ('' != $msg) $result['message'] = $msg;
	if (1 === $flag) return $result;

	defined('IS_AJAX') && IS_AJAX  && ajax_return($result); 		
	defined('IS_JSONP') && IS_JSONP && jsonp_return($result);
	
	// 系统错误 跳转ERROR PAGE
	
	if ( -1 == $code ) { 
		redirect(ERROR_PAGE);
	} else {
		if (stripos($flag, 'http') !== 0) {
			if (isset($_SERVER['HTTP_REFERER']) && preg_match('@^http[s]?://[a-z][a-z0-9\.]*\.'.explode('.',HOME_PAGE,2)[1] .'(?:/[a-zA-Z0-9]+)+\.html@', $_SERVER['HTTP_REFERER'])) {
				$flag = strip_tags($_SERVER['HTTP_REFERER']);
			} else {
				$flag = HOME_PAGE;
			}
		}
		redirect($flag);
	}
	exit;
}

function view($result, $path = '')
{
	if (empty($path)) {
		$path =  App::getModule().'/'.  App::getController().'/'.  App::getAction();
	}
	require APP_PATH.'/View/'.$path.VIEW_EXT;
}

function layout($result, $name = 'frame')
{
	require APP_PATH.'/View/layout/'.$name.VIEW_EXT;
}

function save_gz($data, $filename)
{
	$gzdata = gzencode($data,6);
	$fp = fopen($filename, 'w');
	fwrite($fp, $gzdata);
	fclose($fp);	
}

function validate($type,$value)
{
	if (!validate_utf8($value)) return false;
	
	if ('email' == $type)
	return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
	
	$pattern = [
		'quotes'=>'/["\'\s]+/u',
		'nickname'	=> '/^[a-zA-Z0-9\-_\x{4e00}-\x{9fa5}]{3,31}$/u',
		'username'=>'/^[a-zA-Z0-9\-_]{3,31}$/',
		'email' => '/^\w+([-.]\w+)*@[A-Za-z0-9]+([.-][A-Za-z0-9]+)*\.[A-Za-z0-9]+$/',
		'phone'		=> '/^(13|15|18|14|17)[\d]{9}$/',
		'alphabet'	=> '/^[a-z]+$/i',
	];
	
	return preg_match($pattern[$type],$value);
}

function validate_utf8($text) 
{
	if (strlen($text) == 0) {
		return TRUE;
	}
	return (preg_match('/^./us', $text) == 1);
}
	
function ajax_return($arr)
{
	if (!headers_sent()) header('Content-Type:application/json; charset=utf-8');
	exit(json_encode($arr));	
}

function jsonp_return($arr)
{ 
	if (is_numeric($_GET['callback'])) {
		if (!headers_sent()) {
			header('Content-Type:application/html; charset=utf-8');
		}
		echo 'callback',$_GET['callback'],'(',json_encode($arr),')';
	}
	exit;
}
	
function check_plain($text) 
{
	return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
	
function t($string, array $args = [], array $options = []) 
{ 
	if (empty($args)) {
		return $string;		
	} else {
		foreach ($args as $key => &$value) {
			switch ($key[0]) {
				case '@':
					// Escaped only.
					$value = check_plain($value);
					break;
				case ':':
					// Escaped only.
					$value = check_url($value);
					break;
				case '%':
				default:
					// Escaped and placeholder.
					$value = '<em class="placeholder">' . check_plain($value) . '</em>';
					break;

				case '!':
					// Pass-through.
			}
		}
		return strtr($string, $args);
	}
}

function app_tails()
{
	// fatal errors 
	$error = error_get_last();
	$log = App::getService('Logger');
	if ($error['type'] == E_ERROR) {
		$error['title'] = 'Fatal Error';
		$message = error_message($error);
		$log->log($message, 'CRITICA', debug_backtrace(), 'default');
	}
	$log->record();
}

function app_error($errno, $errstr, $errfile, $errline)
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

	$message = error_message([	'title'		=> 'Unexpected Error', 
								'message'	=> $errstr, 
								'file'		=> $errfile, 
								'line'		=> $errline, 
								'type'		=> $errno
							]);
	
	App::getService('Logger')->log($message, $type, [], 'default');
	
	if ($type == 'error') {
		response(-1);
	}
	return true;
}

function app_exception($e)
{	
	$message = error_message([	'title'		=> 'Unexpected Expection', 
								'message'	=> $e->getMessage(), 
								'file'		=> $e->getFile(), 
								'line'		=> $e->getLine(),
								'type'		=> $e->getCode()
							]);
	App::getService('Logger')->log($message, 'CRITICA', debug_backtrace(), 'default');
	response(-1); 
}


function usr_error($code = 0, $msg = '', $level = 'INFO', $extra = [], $channel = '')
{
	App::getService('Logger')->log($msg, $level, $extra, $channel);		
	if ($code == -999) return;
	response($code, $msg);
	exit;
}

function watchdog($msg = '', $level = 'INFO', $extra = [], $channel = '')
{
	App::getService('Logger')->log($msg, $level, $extra, $channel);		
}
function DB($key)
{
	return App::getService('DB')->init($key);		
}
function cache_manager($type, $key = null){
	$type = ucfirst($type).'Cache';
	return App::getService($type)->init($key);
}

function get_config($section){
	static $conf;
	if(empty($conf)) {
		require CONF_PATH.'/settings.php';
	}
	return $conf[$section]?:null;
}