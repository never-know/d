<?php
namespace Min;

class App
{
	protected  static $module;
	protected  static $controller;
	protected  static $action;
	protected  static $args;
	protected  static $container;
	protected  static $variables;
	protected  static $booted = false;
	
	protected static function setContainer($di)
	{
		self::$container = $di;
	}
	protected static function dispatch()
	{
		// path info 在服务器完成配置
		if (empty($_SERVER['PATH_INFO']) || !preg_match('/^(?:[a-zA-Z0-9]+\/)+[a-zA-Z0-9]+$/', $_SERVER['PATH_INFO'])) {	
			request_not_found();
		} else {	
			$pathinfo 	= explode('/', $_SERVER['PATH_INFO'], 4);
			if (empty($pathinfo[3])) $pathinfo[3]	= '';
			if (empty($pathinfo[2])) $pathinfo[2]	= 'index';
			list(self::$module, self::$controller, self::$action, self::$args) 	= $pathinfo; 
		} 
		$controller_name = '\\App\\Module\\'.ucfirst(self::$module).'\\'.ucfirst(self::$controller).'Controller';
		
	 
		new $controller_name(self::$action);
	}
	
	
	public static function getContainer()
	{
		return self::$container;		 
	}

	public static function getModule()
	{
		return self::$module;		 
	}
	public static function getController()
	{
		return self::$controller;		 
	}
	public static function getAction()
	{
		return self::$action;		 
	}
	public static function getArgs()
	{
		return self::$args;		 
	}
	public static function setVar( $key, $value)
	{
		self::$variables[$key] = $value;
	}
	public static function getVar($key)
	{	
		return self::$variables[$key] ?? null;
	}
	public static function __callStatic($name, $arguments = [])
	{  
		return self::$container->get($name, $arguments);
	
	
	}
	public static function initSession($force = false)
	{
		// 与 未登陆不使用session
		$session_name = 'appid';
		//var_dump($_COOKIE);
		if (empty($_COOKIE[$session_name]) && $force == false) return;
		 
		// Use session cookies, not transparent sessions that puts the session id in
  		// the query string.
  		ini_set('session.use_cookies', '1');
  		ini_set('session.use_only_cookies', '1');
  		ini_set('session.use_trans_sid', '0');
  		// Don't send HTTP headers using PHP's session handler.
  		// An empty string is used here to disable the cache limiter.
  		ini_set('session.cache_limiter', '');
  		// Use httponly session cookies.
  		ini_set('session.cookie_httponly', '1');
		// notice   session.cookie_domain is used in account.service.php  inituser() function 
		ini_set('session.gc_maxlifetime',3600);
  		//ini_set('session.cookie_secure', TRUE);
  		define('IS_HTTPS', ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? TRUE : FALSE);
		ini_set('session.cookie_domain', COOKIE_DOMAIN);
		//ini_set('session.save_handler','redis');
		//ini_set('session.save_path', 'tcp://127.0.0.1:6379?weight=2, tcp://127.0.0.1:6380?weight=1, tcp://127.0.0.1:6381?weight=2');
		session_name($session_name);
		session_start();
	}
	
	public static function bootstrap($di)
	{
		if (!self::$booted) {
			self::$booted = true;
			
			define('IS_AJAX', (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['isajax']) || !empty($_GET['isajax']));
			 
			define('IS_JSONP', !empty($_POST['isjsonp']) || !empty($_GET['isjsonp']));

			self::setContainer($di);
			self::initSession();
			self::dispatch();
		}
	}
}
