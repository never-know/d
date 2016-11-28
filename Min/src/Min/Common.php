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
	response(['redirect'=>ERROR_PAGE]);	
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
	$url        = str_replace(array('\n', '\r'), '', $url);
	$url 		= strip_tags($url);
	if (empty($msg))
		$msg   = '系统跳转中！';
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
function drupal_hmac_base64($data, $key) 
{
  // Casting $data and $key to strings here is necessary to avoid empty string
  // results of the hash function if they are not scalar values. As this
  // function is used in security-critical contexts like token validation it is
  // important that it never returns an empty string.
  $hmac = base64_encode(hash_hmac('sha256', (string) $data, (string) $key, TRUE));
  // Modify the hmac so it's safe to use in URLs.
  return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
}

function drupal_get_token($value = '') 
{
  return drupal_hmac_base64($value, session_id() . drupal_get_private_key() . drupal_get_hash_salt());
}

function drupal_valid_token($token, $value = '', $skip_anonymous = FALSE) 
{
  global $user;
  return (($skip_anonymous && $user->uid == 0) || ($token === drupal_get_token($value)));
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
    $string = html_entity_decode($uri, ENT_QUOTES, 'UTF-8');
    return check_plain(strip_dangerous_protocols($uri));
}

function strip_dangerous_protocols($uri) 
{
  static $allowed_protocols;

  if (!isset($allowed_protocols)) {
    //$allowed_protocols = array_flip(['ftp', 'http', 'https', 'irc', 'mailto', 'news', 'nntp', 'rtsp', 'sftp', 'ssh', 'tel', 'telnet', 'webcal']);
    $allowed_protocols = array_flip(['http', 'https']);
  }

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
function cache_manager($type, $key = null)
{
	$type = ucfirst($type).'Cache';
	return App::getService($type)->init($key);
}

function get_config($section)
{
	static $conf;
	if(empty($conf)) {
		require CONF_PATH.'/settings.php';
	}
	return $conf[$section]?:null;
}