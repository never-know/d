<?php
	
	error_reporting(E_ALL);
	ini_set('display_error','on');
	date_default_timezone_set('Asia/Shanghai');
	
	define('SITE_DOMAIN', 'yi.com');
	define('HOME_PAGE', 'http://www.'.SITE_DOMAIN );
	define('ERROR_PAGE', HOME_PAGE.'/error.html');
	define('OFFLINE_PAGE', HOME_PAGE.'/error/offline.html');
	define('NOT_FOUND_PAGE', HOME_PAGE.'/error/not_found.html');
	define('ACCESS_DENY_PAGE', HOME_PAGE.'/error/access_deny.html');

	define('COOKIE_DOMAIN', '.'.SITE_DOMAIN);
	define('CDN_DOMAIN', 'cnd.'.SITE_DOMAIN);

	define('VIEW_EXT','.tpl');
	define('PHP_EXT','.php');
	define('DEFAULT_ACTION','index');

	define('APP_PATH', __DIR__);
	define('LOG_PATH', APP_PATH.'/../log');	
	define('CACHE_PATH', APP_PATH.'/../cache');	
	
	define('CONF_PATH', APP_PATH.'/Conf');	
	define('VIEW_PATH', APP_PATH.'/View');
	define('MODULE_PATH', APP_PATH.'/Module');	
	define('SERVICE_PATH', APP_PATH.'/Service');	
	
	define('MIN_PATH', APP_PATH.'/../../Min/src');
	define('VENDOR_PATH', MIN_PATH.'/../vendor');
	
	define('IS_JSONP', !empty($_REQUEST['isJsonp']));
	define('IS_GET', 'GET' == strtoupper($_SERVER['REQUEST_METHOD']));
	define('IS_POST', 'POST' == strtoupper($_SERVER['REQUEST_METHOD']));
	define('IS_AJAX', (!empty($_REQUEST['isAjax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')));
			
	require MIN_PATH.'/Min/Common.php';	
	
	spl_autoload_register('autoload');
	
	set_error_handler('app_error');
	set_exception_handler('app_exception');
	register_shutdown_function('app_tails');
	
	$di = new \Min\Di;
	$di->setShared('mysql', '\\Min\\Backend\\MysqliPDO');
	$di->setShared('redis', '\\Min\\Cache\\Redis');
	$di->setShared('file_cache', '\\Min\\Cache\\FileCache');
	$di->setShared('logger', '\\Min\\Logger');
	
	\Min\App::bootstrap($di, true);