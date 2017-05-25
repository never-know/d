<?php

use Min\App;

function min_init()
{
	defined('APP_PATH')			or exit;
	defined('VIEW_EXT') 		or define('VIEW_EXT','.tpl');
	defined('PHP_EXT')  		or define('PHP_EXT','.php');
	defined('DEFAULT_ACTION') 	or define('DEFAULT_ACTION','index');
	
	defined('LOG_PATH') 	or define('LOG_PATH', APP_PATH.'/../log');	
	defined('CACHE_PATH') 	or define('CACHE_PATH', APP_PATH.'/../cache');	
	defined('PUBLIC_PATH') 	or define('PUBLIC_PATH', APP_PATH.'/../webroot/public');		// 图片上传在服务器上的基址
	defined('ASSETS_URL') 	or define('ASSETS_URL', '//www.'. SITE_DOMAIN. '/public');					// 图片上传后的URL基址

	defined('CONF_PATH') 	or define('CONF_PATH', APP_PATH.'/Conf');	
	defined('VIEW_PATH') 	or define('VIEW_PATH', APP_PATH.'/View');
	defined('MODULE_PATH') 	or define('MODULE_PATH', APP_PATH.'/Module');	
	defined('SERVICE_PATH') or define('SERVICE_PATH', APP_PATH.'/Service');	
	defined('VENDOR_PATH') 	or define('VENDOR_PATH', MIN_PATH.'/../vendor');
	
	define('REQUEST_METHOD', strtoupper($_SERVER['REQUEST_METHOD']));
	define('IS_GET', 	(REQUEST_METHOD === 'GET'));
	define('IS_POST', 	(REQUEST_METHOD === 'POST'));
	define('IS_PUT', 	(REQUEST_METHOD === 'PUT'));
	define('IS_DELETE', (REQUEST_METHOD === 'DELETE'));
	define('IS_JSONP', 	isset($_GET['isJsonp']));
	define('IS_AJAX', 	(isset($_REQUEST['isAjax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')));
	define('IS_HTTPS', 	(isset($_SERVER['HTTPS']) && strtoupper($_SERVER['HTTPS']) == 'ON'));
	
	if (!IS_GET && !IS_POST) {
		parse_str(file_get_contents("php://input", $_POST));
	}

	spl_autoload_register('autoload');

	set_error_handler('app_error');
	set_exception_handler('app_exception');
	register_shutdown_function('app_tails');
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

function view(array $result = [])
{
	if (empty($result['template_path'])) {
		$result['template_path'] =  '/'. App::getModule().'/'.  App::getController().'/'.  App::getAction();
	}
	require VIEW_PATH. $result['template_path']. VIEW_EXT;
	 
}

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
		throw new \Min\MinException($file.' can not be autoloaded');
	}	
}

function vendor($class) 
{
	

}

function session_get($name)
{
	return $_SESSION[$name] ?? null;
}

function session_set($name, $value)
{	
	$_SESSION[$name] = $value;
}
function session_inrc($name){
	$value = intval(session_get($name));
	session_set($name, ++$value);
	return $value;
}
function session_derc($name){
	$value = intval(session_get($name));
	session_set($name, --$value);
	return $value;
}
function current_path() 
{
  return $_SERVER['PATH_INFO_ORIGIN'].'.html?'.http_build_query($_GET);
}
 
function ip_address() 
{
	static  $ip = null;
	
	if (!isset($ip)) {		
		$ip_address = $_SERVER['REMOTE_ADDR'];

		if (1 == config_get('reverse_proxy')) {
   
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
				$untrusted = array_diff($forwarded, config_get('reverse_proxy_addresses', []));

				// The right-most IP is the most specific we can trust.
				$ip_address = array_pop($untrusted);
			}
		}
		
		$ip = ip2long($ip_address);
		if (false == $ip){
			watchdog('invalid ip address : '.$ip_address, 'USER_ABNORMAL_IP', 'NOTICE');
		}
	}

	return $ip;
}

function redirect($url, $time = 0, $msg = '') 
{
	$url = str_replace(array('\n', '\r'), '', $url);
	$url = check_url($url);
	$msg = $msg ?: '系统跳转中！';
	if (!headers_sent()) {
		if (0 == $time) {
			header('Location: ' . $url);
		} else {
			header('refresh:'. intval($time). ';url='. $url);
			echo($msg);
		}
		exit();
	} else {
		$str  = '<meta http-equiv="Refresh" content="'. intval($time). ';URL='. $url. '">';
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

function int2str($value)
{	
	$value = intval($value);
	if ($value > -1 && $value < \PHP_INT_MAX) {	
		return base_convert($value, 10, 36);
	} else {
		throw new \Exception('整型值错误', 1);
	}
	 
}

function str2int($value)
{
	//if (validate('id_base36', $value) && strnatcmp('1y2p0ij32e8e7', $value) > 0){
	// id_base36 最大12位，不会越界
	if (validate('id_base36', $value)) {
		return intval(base_convert($value, 36, 10));
	} else {
		return false;
	}
}

function validate($type, $value, int $max = 0, int $min = 1)
{
	if (!validate_utf8($value)) return false;
	
	$pattern = [
		'words' 		=> '/^[a-zA-Z0-9_]+$/',  			// 标准ascii字符串
		'quotes'		=>'/["\'\s]+/u',					// 引号空格
		'nickname'		=> '/^[a-zA-Z0-9\-_\x{4e00}-\x{9fa5}]{3,31}$/u',   // 含中文昵称
		'username'		=>'/^[a-zA-Z0-9\-_]{3,31}$/',						// 用户名
		//'openid'		=> '/^(?=[a-zA-Z0-9\-_]{26,32}$)(.*[a-zA-Z].*)$/',	//'/^[a-zA-Z0-9\-_][a-zA-Z]{20,36}$/',						// openid
		'openid'		=> '/^(?=.*?[a-zA-Z])([a-zA-Z0-9\-_]{26,32})$/',		//'/^[a-zA-Z0-9\-_][a-zA-Z]{20,36}$/',						// openid
		'email' 		=>'/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',	// 邮箱
		'phone'			=> '/^(13|15|18|14|17)[\d]{9}$/',						// 手机
		'alphabet'		=> '/^[a-z]+$/i',										// 字母不区分大小写
		'date_Y-m-d' 	=> '/^20(1[789]|2[\d])\-(0[1-9]|1[012])\-(0[1-9]|[12][\d]|3[01])$/', //合法日期
		'img_url'	 	=> '@^(http[s]?:)?//[a-zA-Z0-9_.]+(/[a-zA-Z0-9_]+)+(\.(jpg|png|jpeg))?$@',  // 合法图片地址	
		'length'		=> '/^.{'. $min. ','. $max. '}$/us',
		'id_base36'			=> '/^[a-z0-9]{1,12}$/'  // 最大12位,暂不使用13位的数据
	];
	/*
	if ($type != 'length' && $max > 0) {
		$length = '/^.{'. $min. ','. $max .'}$/us';
		$length_check =  (preg_match($length, $value) == 1);
	} else {
		$length_check = true;
	}
	*/
	 
	return (isset($pattern[$type]) && (preg_match($pattern[$type], $value) == 1) && ($type == 'length' || $max < 1 || preg_match($pattern['length'], $value) == 1));
	 
}

function validate_utf8($text) 
{
	if (strlen($text) == 0) {
		return TRUE;
	}
	return (preg_match('/^./us', $text) == 1);
}
	
function get_token($value = '', $seed = false) 
{	
	if (empty($value)) {
		$value = implode('_', [App::getModule(), App::getController(), App::getAction()]);
	}
	
	$form_id = $value. '_FORM';
	
	if (false === $seed) {
		 $_SESSION[$form_id] = mt_rand(111111, 999999);
	}
	$key = session_id() . config_get('private_key') .$_SESSION[$form_id]. config_get('hash_salt');
	$hmac = base64_encode(hash_hmac('sha256', $value, $key, TRUE));
	return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
}

function valid_token($token, $value) 
{
	$form_id = $value. '_FORM';
	if (empty($_SESSION[$form_id])) return false;
	return ($token === get_token($value, true));
}
// 处理url
function check_url($uri) 
{
    $uri = html_entity_decode($uri, ENT_QUOTES, 'UTF-8');
    return check_plain(str_replace(['(', ')', '%28', '%29'], '', strip_dangerous_protocols($uri))); 
   // return check_plain( strip_dangerous_protocols($uri)); 
}
// 安全的在html中输出字符串	
function check_plain($text) 
{
	return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
// 安全的在js中插入Php代码
function safe_json_encode($var) 
{ 
    return json_encode($var, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
// 从php, html 代码中提取文本
function check_plain_from_html($string) {
    return html_entity_decode(strip_tags($string));
}


function strip_dangerous_protocols($uri) 
{
	$allowed_protocols = array_flip(['http', 'https', 'tel']);
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

function cache($key)
{
	$cache_setting = config_get('cache');
	$value = $cache_setting[$key] ?? $cache_setting['default'];
	return \Min\App::getService($value['bin'], $value['key']);
}

function watchdog($msg, $channel = 'debug', $level = 'DEBUG',  $extra = [])
{
	if ($msg instanceof \Throwable) {
		$msg = error_message_format($msg);
	} elseif (is_resource($msg)) {
		$msg = 'this is a resource '. get_resource_type($msg);
	} else {
		$msg = json_encode($msg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	
	App::getService('logger')->log($msg, $level, $channel, $extra);		
}

function config_get($section, $default = null)
{
	static $conf;
	if (empty($conf)) {
		require CONF_PATH.'/settings.php';
	}
	if (!isset($conf[$section]) && !isset($default)) {
		throw new \Exception('未定义的配置节点' . $section, 1);
	}
	return $conf[$section] ?? $default;
}

function site_offline() 
{
    redirect(OFFLINE_PAGE);
}

/**
 * GET 请求
 * @param string $url
 */
function http_get($url) 
{
	$oCurl = curl_init();
	if (stripos($url,'https://') !== FALSE) {
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	$sContent 	= curl_exec($oCurl);
	$aStatus 	= curl_getinfo($oCurl);
	curl_close($oCurl);
	if (intval($aStatus['http_code']) == 200) {
		return $sContent;
	} else {
		return false;
	}
}

/**
 * POST 请求
 * @param string $url
 * @param array $param
 * @param boolean $post_file 是否文件上传
 * @return string content
 */
 
function http_post($url, $param, $post_file = false)
{
	$oCurl = curl_init();
	
	if (stripos($url,'https://') !== FALSE) {
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	 
	if (is_string($param)) {
		$strPOST = $param;	
	} elseif (!$post_file) {
		$strPOST =   http_build_query($param);
	} else {
		foreach ($param as $key => $val) {
			if (substr($val, 0, 1) == '@') {
				$param[$key] = new \CURLFile(realpath(substr($val,1)));
			}
		}		
		$strPOST = $param;
	}  
	 
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POST, true);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
	$sContent 	= curl_exec($oCurl);
	$aStatus 	= curl_getinfo($oCurl);
	curl_close($oCurl);
	if (intval($aStatus['http_code']) == 200) {
		return $sContent;
	} else {
		return false;
	}
}

function request_error_found($code, $message = '请求失败', $redirect = null, $layout = null) 
{	
	$result['statusCode'] = $code;
	$result['message'] 	  = $message;
	if (isset($redirect)) 	$result['redirect'] = $redirect;
	if (!isset($layout)) 	$layout = 'layout_404';
	 
	final_response($result, $layout);
}

function min_header($headers)
{
	if (!headers_sent($file, $line)) {
		header($headers);
	} else {
		exit("Headers sent in {$file} on line {$line}");
	}
}	

// $layout means layout if it starts with 'layout_', or  type like  json, xml otherwise

function final_response($result, $layout) {

	if (IS_AJAX) {
		$layout = 'JSON';
	} elseif (IS_JSONP) {
		$layout = 'JSONP';
	}  
 
	switch (strtoupper($layout)) {
		case 'JSON' :
			header('Content-Type:application/json; charset=utf-8');
			exit(safe_json_encode($result));
		case 'XML'  :
			header('Content-Type:text/xml; charset=utf-8');
			exit(xml_encode($result));
		case 'JSONP':
			header('Content-Type:application/json; charset=utf-8');
			echo 'callback', intval($_GET['callback']), '(', safe_json_encode($result), ')';
			exit;
		case 'HTML' :
		default :
			if (!empty($result['body'])) $result = $result['body'];
			require VIEW_PATH. '/layout/'. ($layout ?: 'layout_frame'). VIEW_EXT;
			exit;
	  
	} 
}

function app_tails()
{
	record_time('request end');
	// fatal errors 
	$error = error_get_last(); 
	if (isset($error['type'])) {
		$message = 'Fatal Error Catched By app_tails: '
		.	$error['message']
		.	' in file '
		.	$error['file']
		.	'  at line '
		.	$error['line']
		.	' error code/type: '
		.	$error['type'];
		
		watchdog($message, 'Fatal_Error', 'CRITICAL', debug_backtrace());
	}
	App::getService('logger')->record();
	if (isset($error['type'])) {
		request_error_found(500);
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
	
	$message = rtrim($errstr,PHP_EOL)
		.	' in file '
		.	$errfile
		.	'  at line '
		.	$errline
		.	' error code/type: '
		.	$errno;
	
	watchdog($message, 'unexpected_error', $type);
	
	if ($type == 'ERROR') {
		request_error_found(500);
	}
	return true;
}

function app_exception($e, $channel = 'unexpected_expection')
{	
	if ($e instanceof \PDOException) {
		$channel = 'mysql_exception';
	} elseif ($e instanceof \Min\MinException) {
		$channel = 'catched_exception';
	}  
	
	watchdog(error_message_format($e), $channel, 'CRITICAL', $e->getTrace());
	request_error_found(500);
}

function min_error($t)
{
	$dest_file = LOG_PATH.'/FatalError'.date('/Y-m-d').'.log';
		
	$records =  date('Y/m/d H:i:s', $_SERVER['REQUEST_TIME'])
			. ' [IP: '
			. long2ip(ip_address())
			. '] ['
			. $_SERVER['REQUEST_URI']
			. '] ['
			. ($_SERVER['HTTP_REFERER']??'')
			. '] [pid '
			. getmypid()
			. '] ['
			. session_id()
			. '] ['
			. (session_get('UID') ?: 0)
			. ']'
			. PHP_EOL;
		
		$records   .= error_message_format($t);
		$records   .= PHP_EOL;	
		$records   .= safe_json_encode(debug_backtrace());
		$records   .= PHP_EOL;
		error_log($records, 3, $dest_file, '');

}

function error_message_format(\Throwable $e)
{
	$message =	rtrim($e->getMessage(),PHP_EOL)
		.	' in file '
		.	$e->getFile()
		.	'  at line '
		.	$e->getLine()
		.	' error code/type: '
		.	$e->getCode();
	
	return $message;
}

function  record_time($tag)
{	
	static $last_time;
	if (is_null($last_time)) $last_time = $_SERVER['REQUEST_TIME_FLOAT'];
	$now = microtime(true);
	watchdog($tag. ' total:'. ($now - $_SERVER['REQUEST_TIME_FLOAT']) * 1000 . ';#this:'. ($now - $last_time) * 1000, 'timelog');
	$last_time = $now;
}

function result_page($total, $page_size, $current_page){
	return array(
		'page_total' 	=> ceil($total/$page_size),
		'current_page' 	=> $current_page?:1,
		'data_total' 	=> $total
	);
}

function plain_build_query($params, $separator){
	
	$joined = [];
	foreach($params as $key => $value) {
	   $joined[] = "`$key`=$value";
	}
	return implode($separator, $joined);
}

/* 
 * id格式 ：{article_id}{6}{time}{6}{type}{1}{salt}{2}{md5}{6}{uid}{n}
 * 最小6位36进制对应的十进制 100000 => 60466176; 最大zzzzzz => 2176782335
 * article_id 61000000 到 2176782335  转化为 6位字符串
 * user_id  同上，n位字符串,  {id*n}{n}
 *share_time   时间戳减 （1487411575-61000000）= 1325000000
 * param id : int;
 */

function shareid_encode($id, $type = null)
{
	if (isset($type) && $type != 1 && $type != 2 ) {
		watchdog('shareid_encode type 目前只支持 1和2', 'code', 'NOTICE');
		return '';
	}
	
	$params 	= [];
	$sub_time 	= 1325000000;

	$params['hash_salt'] 	= conf_get('hash_salt');

	if (isset($type)) {
		$params['id']		= $id;
		$params['salt'] 	= mt_rand(36, 1295); //mt_rand(str2int('10'), str2int('zz'));		
		$params['time']  	= time();
		$params['type']		= $type;
		$params['uid'] 		= session_get('UID');
	} else {
		$params['id'] 		= str2int(substr($id, 0, 6));
		$params['salt']		= str2int(substr($id, 13, 2));
		$params['time'] 	= str2int(substr($id, 6, 6)) + $sub_time + $params['salt'];
		$params['type'] 	= $id[12];
		$params['uid'] 		= str2int(substr($id, 21))/$params['salt'];
	}
	
	//ksort($params);
	$md5 		= md5(implode(',', $params));
	$sub_md5 	= substr($md5, 11, 3). substr($md5, 21, 3);

	if (isset($type)) {
		$result 	= [];
		$result[] 	= int2str($params['id']);
		$result[] 	= int2str($params['time']- $sub_time - $params['salt']);
		$result[] 	= $params['type'];
		$result[] 	= int2str($params['salt']);
		$result[] 	= $sub_md5;
		$result[] 	= int2str($params['uid']* $params['salt']);
		return  implode('', $result);
	} else {
		if ($sub_md5 == substr($id, 15, 6)) {
			unset($params['hash_salt'], $params['salt']);
			return $params;
		} else {
			return false;
		}
	}
}

/* 
 * id格式 ：{article_id}{6}{time}{6}{type}{1}{salt}{2}{md5}{6}{uid}{n}
 * 最小6位36进制对应的十进制 100000 => 60466176; 最大zzzzzz => 2176782335
 * article_id 61000000 到 2176782335  转化为 6位字符串
 * user_id  同上，n位字符串,  {id*n}{n}
 * share_time   时间戳减 （1487411575-61000000）= 1325000000
 * param id : int;
 */
 
// 6亿用户， 7亿数据 生成 24位字符串

function shareid($id, $uid, $type)
{ 
	// test  test/shareidtest.html;
	//$uid		= session_get('UID');
	if (empty($uid)) {
		return 'shareid';
	}
	
	$time 		= $_SERVER['REQUEST_TIME'] - 1295672286;	//	197889303 
	$uid		= session_get('UID') + 103656280;	
	$aid		= $id  + 103656280;	
	 
	$salt	=   mt_rand(37, 1295);  // 108 ;109   // 129
	if ($salt < 88) {
		// $salt2 < $salt3	差值 12
		$salt2 = 108 - $salt;		//	range:	71-21
		$salt3 = 120 - $salt;		//	range:	83-33		
	
	} elseif ($salt < 130){
		// $salt2 < $salt3 差值 13
		$salt2 = 183 - $salt;	//	range:	95-54
		$salt3 = 196 - $salt;	//	range:	108-67
	} else {
		//$salt2 > $salt3 差值 1-11			
		$salt2  = ($salt%108)?:108;		// 	range: 	22-108
		$salt3  = ($salt%109)?:109;	 	//	range:	21-107
		
		// special process
		if ($salt2 < 21 ) {
			$salt2 = 59 - $salt2;		//	range:	(58-39) +  (21-30)
		}
		
		if ($salt3 < 21) {
			$salt3 = 79 - $salt3;		//	range:	(109-99) + (78-59)
		}
		 
	}

	//zzzzzzz: 78364164095
	//1000000: 2176782336
	echo ((($salt%60)?:60) + 10),'-';
	$salt_36 	= base_convert($salt, 10, 36);
	 
	$parts 		= [];
	$parts[]	= $type;
	$parts[]	= $salt_36[0];
	$parts[]	= base_convert(((($time % 286898) ?: 286898) * $salt2 + $uid) * $salt3, 10, 36);	//	579113185	7位
	$parts[]	= base_convert($time * ((($salt%60)?:60) + 10), 10, 36);							//	2046年	7位
	$parts[]	= base_convert(((($time % 183868) ?: 183868) * $salt3 + $aid) * $salt2, 10, 36);	//  695186871	7位
	$parts[]	= $salt_36[1];		 
	/* 
	$r = [];
  
	if (strlen($parts[2]) < 7) {
		echo 'uid:', $salt,'--', $salt2,'--', $salt3, '<br>';
	}
	if (strlen($parts[3]) < 7) {
		echo 'tim:',$salt,'--', $salt2,'--', $salt3, '<br>';
	}
	
	if (strlen($parts[4])< 7) {
		echo 'aid:',$salt,'--', $salt2,'--', $salt3, '<br>';
	}
	*/
	//return strtr( $type. $salt_36[0]. base_convert(((($time % 286898) ?: 286898) * $salt2 + $uid) * $salt3, 10, 36). base_convert(((($time % 183868) ?: 183868) * $salt3 + $aid) * $salt2, 10, 36). base_convert($time * ($salt - 13), 10, 36). $salt_36[1], $pairs) ;
	//return $r;
	return strtr( implode('', $parts), 'comefu', 'fucome') ;
	//$r['id'] = strtr( implode('', $parts), 'fucome', 'comefu') ;
	//return $r;
}