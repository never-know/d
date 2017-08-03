<?php
	declare(strict_types=1);
	
	error_reporting(E_ALL);
	
	ini_set('display_error','on');
	
	date_default_timezone_set('Asia/Shanghai');

	define('SERVER_NAME', $_SERVER['HTTP_HOST']);
	
	define('COOKIE_DOMAIN', SERVER_NAME);
	
	if (empty($_SERVER['HTTPS'])) {
		define('SCHEMA', 'http://');
		define('IS_HTTPS', 	false);
	} else {
		define('SCHEMA', 'https://');
		define('IS_HTTPS', 	true);
	}
	
	define('SITE_DOMAIN', 'anyitime.com');	
	define('ASSETS_URL', 'https://m.anyitime.com/public');	// 图片上传后的URL基址
	
	define('HOME_PAGE', SCHEMA . SERVER_NAME);
	define('CURRENT_URL', SCHEMA . SERVER_NAME . $_SERVER['REQUEST_URI']);

	define('APP_PATH', __DIR__);

	define('MIN_PATH', APP_PATH.'/../../Min/src');
	
	try {
		require MIN_PATH . '/Min/Common.php';
		
		min_init();
		
		require APP_PATH . '/Common/function.php';
		
		$di = new \Min\Di;
 
		// server name as xxx_xxx
 
		$di->setShared('mysql', '\\Min\\Backend\\MysqliPDO');
		$di->setShared('redis', '\\Min\\Cache\\Redis');
		$di->setShared('file_cache', '\\Min\\Cache\\FileCache');
		$di->setShared('logger', '\\Min\\Logger');

		\Min\App::bootstrap($di, true);	
		
	} catch (\Throwable $t){
		app_exception($t);
	}