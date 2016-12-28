<?php

/*
 * 全部小写
 */


$conf['backend'] = array (
	'default' => array (
		'bin' => 'mysql', // DI server name
		'key' => 'default'
	),
	'user' => array (
		'bin' => 'mysql',
		'key' => 'default'
	)
);


$conf['cache'] = array (
	'default' => array (
		'bin' => 'redis',
		'key' => 'default'
	),
	'user' => array (
		'bin' => 'redis',
		'key' => 'default'
	),
	'content' => array (
		'bin' => 'file_cache',
		'key' => 'default',
	)
);



# mysql 
 
$conf['mysqli'] = array (
	'default' => array (
		'prefix' => 'yi_',
		'rw_separate'	=> true,
		'master' 		=> '//ts001:a123456@p:121.43.182.222:3306#annyi',
		'slave'			=> array (
			'//ts001:a123456@p:121.43.182.222:3306#annyi',  // remote
			//'//root:@127.0.0.1:3306#test' 				// localhost
		),
	),
);

$conf['mysqlpdo'] = array (
	'default' => array (
		'prefix' => 'yi_',
		'rw_separate'	=> true,
		'master' 		=> '//ts001:a123456@p:121.43.182.222:3306#annyi',
		'slave'			=> array (
			'//ts001:a123456@mysql:host=121.43.182.222;port=3306;dbname=annyi;charset=utf8:80', //remote
			//'//root:@mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8:80'   //localhost
		),
	),
);


# logger

$conf['logger'] = array (
	'DEBUG' => 100,
    'INFO' => 200,
	'NOTICE' => 250,
	'WARNING' => 300,
	'ERROR' => 400,
	'CRITICAL' => 500,
	'ALERT' => 550,
	'EMERGENCY' => array (
		'handler' => '\\Min\\Logger\\Handler\\Sms',
	),	
);


# filecache

$conf['file_cache'] = array (
	'default' => array (
		'cache_dir' => CACHE_PATH,
	)
);

# redis

$conf['redis'] = array (
	'default' => array (
		'host' => '127.0.0.1',
		'port' => '6379',
		'timeout' => '1',
		'delay' => '200',
		'auth' => '',
		'db' => 1, 
	)
);


$conf['hash_salt'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
$conf['private_key'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
