<?php

	declare(strict_types=1);
	
	error_reporting(0);
	ini_set('display_error','off');
	
	date_default_timezone_set('Asia/Shanghai');

	define('SERVER_NAME', $_SERVER['SERVER_NAME']);	
	define('COOKIE_DOMAIN', SERVER_NAME);

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