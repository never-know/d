<?php

	define('HOME_PAGE','http://www.qi.com');
	define('ERROR_PAGE', HOME_PAGE.'/error.html');
	define('SITE_DOMAIN','qi.com');
	define('COOKIE_DOMAIN','.qi.com');
	define('FILE_DOMAIN','cnd.qi.com');

	define('VIEW_EXT','.tpl');
	define('PHP_EXT','.php');

	define('APP_PATH', __DIR__);
	define('LOG_PATH', APP_PATH.'/../log');	
	
	define('CONF_PATH', APP_PATH.'/Conf');	
	define('VIEW_PATH', APP_PATH.'/View');
	define('MODULE_PATH', APP_PATH.'/Module');	
	define('SERVICE_PATH', APP_PATH.'/Service');	
	
	define('MIN_PATH', APP_PATH.'/../../Min/src');
	define('VENDOR_PATH', APP_PATH.'/../../Min/vendor');
	
	date_default_timezone_set('Asia/Shanghai'); 
	
	require MIN_PATH.'/Min/Common.php';	
	spl_autoload_register('autoload');
	
	set_error_handler('app_error');
	set_exception_handler('app_exception');
	register_shutdown_function('app_tails');
			
	$di = new \Min\Di;
	$di->setShared('db','\\Min\\Db');
	$di->setShared('cacheManager','\\Min\\CacheManager');
	$di->setShared('logger','\\Min\\Logger');
	
	\Min\App::bootstrap($di, false);