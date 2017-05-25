<?php
	declare(strict_types=1);
	
	error_reporting(E_ALL);
	ini_set('display_error','on');
	date_default_timezone_set('Asia/Shanghai');
	
	define('SITE_DOMAIN', 'yi.com');						// 主域名
	define('SERVER_NAME', $_SERVER['SERVER_NAME']);						// 主域名
	define('HOME_PAGE', 'http://www.'.SITE_DOMAIN);
	
	define('COOKIE_DOMAIN', '.'. );

	define('APP_PATH', __DIR__);

	define('MIN_PATH', APP_PATH.'/../../Min/src');
	
	require MIN_PATH.'/Min/Common.php';
	
	min_init();
	
	$di = new \Min\Di;
 
	// server name as xxx_xxx
 
	$di->setShared('mysql', '\\Min\\Backend\\MysqliPDO');
	$di->setShared('redis', '\\Min\\Cache\\Redis');
	$di->setShared('file_cache', '\\Min\\Cache\\FileCache');
	$di->setShared('logger', '\\Min\\Logger');
	
	try {
		\Min\App::bootstrap($di, true);
		
	} catch (\Throwable $t){
		app_exception($t);
	}