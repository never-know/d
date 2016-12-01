<?php

use Min\App;

function autoload($class)
{
	// new \min\service\login;
	// new \min\module\passport\login;
	$path 	= strtr($class, '\\', '/');
	$path_info 	= explode('/', $path, 2);
	
	switch ($path_info[0]) {
		case 'App' :
			$file	= APP_PATH .'/'. $path_info[1] . PHP_EXT;
			break;
		case 'Min' :
			$file	= MIN_PATH . '/' . $path . PHP_EXT;
			break;
		default :
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
	
	if (!isset($ip_address)) {
		
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if ( 1 == REVERSE_PROXY) {
   
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
				$untrusted = array_diff($forwarded, get_config('reverse_proxy_addresses', []));

				// The right-most IP is the most specific we can trust.
				$ip_address = array_pop($untrusted);
			}
		}
	}

	return $ip_address;
}

function redirect($url, $time = 0, $msg = '') 
{
	$url = str_replace(array('\n', '\r'), '', $url);
	$url = check_url($url);
	$msg = $msg ?: '系统跳转中！';
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
		if ($time != 0) $str .= $msg;
		exit($str);
	}
}

function save_gz($data, $filename)
{
	$gzdata = gzencode($data,6);
	$fp 	= fopen($filename, 'w');
	fwrite($fp, $gzdata);
	fclose($fp);	
}

function validate($type,$value)
{
	if (!validate_utf8($value)) return false;
	
	if ('email' == $type) return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
	
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
	if (!headers_sent()) {
		header('Content-Type:application/json; charset=utf-8');
	}
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

function get_token($value = '') 
{
	$key = session_id() . conf_get['private_key'] . conf_get['hash_salt'];
	$hmac = base64_encode(hash_hmac('sha256', $value, $key, TRUE));
	return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
}

function drupal_valid_token($token, $value = '', $skip_anonymous = FALSE) 
{
  return ($skip || ($token === get_token($value)));
}
	
function check_plain($text) 
{
	return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function check_plain_from_html($string) {
    return html_entity_decode(strip_tags($string));
}

function check_url($uri) 
{
    $uri = html_entity_decode($uri, ENT_QUOTES, 'UTF-8');
    return check_plain(strip_dangerous_protocols($uri));
}

function strip_dangerous_protocols($uri) 
{
	$allowed_protocols = array_flip(['http', 'https']);
    //$allowed_protocols = array_flip(['ftp', 'http', 'https', 'irc', 'mailto', 'news', 'nntp', 'rtsp', 'sftp', 'ssh', 'tel', 'telnet', 'webcal']);
  
  // Iteratively remove any invalid protocol found.
	do {
		$before = $uri;
		$colonpos = strpos($uri, ':');
		if ($colonpos > 0) {
		// We found a colon, possibly a protocol. Verify.
			$protocol = substr($uri, 0, $colonpos);
			// If a colon is preceded by a slash, question mark or hash, it cannot
			// possibly be part of the URL scheme. This must be a relative URL, which
			// inherits the (safe) protocol of the base document.
			if (preg_match('![/?#]!', $protocol)) {
				break;
			}
			// Check if this is a disallowed protocol. Per RFC2616, section 3.2.3
			// (URI Comparison) scheme comparison must be case-insensitive.
			if (!isset($allowed_protocols[strtolower($protocol)])) {
				$uri = substr($uri, $colonpos + 1);
			}
		}
	} while ($before != $uri);

	return $uri;
}
	
function t($string, array $args = [], array $options = []) 
{ 
	if (empty($args)) {
		return $string;		
	} else {
		foreach ($args as $key => $value) {
			switch ($key[0]) {
				case '@':
					$value = check_plain($value);
					break;
				case ':':
					$value = check_url($value);
					break;
				case '%':
				default:
					$value = '<em class="placeholder">' . check_plain($value) . '</em>';
					break;

				case '!':
			}
		}
		return strtr($string, $args);
	}
}

function error_message_format(array $error)
{
	$message = '{ ['
		.	$error['title']
		.	': '
		.	rtrim($error['message'],PHP_EOL)
		.	'] in file ['
		.	$error['file']
		.	']  at line ['
		.	$error['line']
		.	'] [error code/type: '
		.	$error['type']
		.	'] }';
	
	return $message;
}

function watchdog($msg = '', $level = 'INFO', $extra = [], $channel = null)
{
	App::getService('Logger')->init($channel)->log($msg, $level, $extra);		
}

function DB($key)
{
	return App::getService('DB')->init($key);		
}

function cache_manager($type, $key = null)
{
	$type = ucfirst($type).'Cache';
	return App::getService($type)->init($key);
}

function get_config($section, $default = null)
{
	static $conf;
	if(empty($conf)) {
		require CONF_PATH.'/settings.php';
	}
	return $conf[$section]?:$default;
}

function view($result, $path = '')
{
	if (empty($path)) {
		$path =  '/'.App::getModule().'/'.  App::getController().'/'.  App::getAction();
	}
	require VIEW_PATH.$path.VIEW_EXT;
}

function request_not_found() 
{	
	$result['status'] = 404;
	
	defined('IS_AJAX') 	&& IS_AJAX  && ajax_return($result); 		
	defined('IS_JSONP') && IS_JSONP && jsonp_return($result);

	$url = empty($_SERVER['HTTP_REFERER'])?'':check_plain($_SERVER['HTTP_REFERER']);
	$result = (!empty($url) || preg_match('!^http[s]?\:[a-z]+\.'.SITE_DOMAIN.'!', $url))?[':url'=>$url, '@title'=>t('上一页')]:[':url'=>HOME_PAGE, '@title'=> t('首页')];
	
	view($result, '/layout/404');
	exit;
}	

function request_error_found() 
{	
	$result['status'] = 500;
	
	defined('IS_AJAX') 	&& IS_AJAX  && ajax_return($result); 		
	defined('IS_JSONP') && IS_JSONP && jsonp_return($result);
	
	require VIEW_PATH.'/layout/500'.VIEW_EXT;
	exit;
} 

function site_offline() 
{
    redirect(OFFLINE_PAGE);
}

function app_tails()
{
	// fatal errors 
	$error = error_get_last();
	$log = App::getService('Logger');
	if (isset($error['type']) && $error['type'] == E_ERROR) {
		$error['title'] = 'Fatal Error Catched By app_tails ';
		$message = error_message_format($error);
		$log->log($message, 'CRITICAL', debug_backtrace(), 'default');
	}
	$log->record();
	if (isset($error['type']) && $error['type'] == E_ERROR) {
		request_error_found();
	}
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

	$me	=  [	
		'title'		=> 'Unexpected Error', 
		'message'	=> $errstr, 
		'file'		=> $errfile, 
		'line'		=> $errline, 
		'type'		=> $errno
	];
	
	watchdog(error_message_format($me), $type, [], 'default');
	
	if ($type == 'ERROR') {
		request_error_found();
	}
	return true;
}

function app_exception($e)
{	
	$me  =  [	
		'title'		=> 'Unexpected Expection', 
		'message'	=> $e->getMessage(), 
		'file'		=> $e->getFile(), 
		'line'		=> $e->getLine(),
		'type'		=> $e->getCode()
	];
	watchdog(error_message_format($me), 'CRITICAL', debug_backtrace(), 'default');
	request_error_found();
}
