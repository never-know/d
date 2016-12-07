<?php

# backend

$conf['backend'] = array (
	'default' => array (
		'bin' => 'Mysql',
		'key' => 'default'
	),
	'user' => array (
		'bin' => 'Mysql',
		'key' => 'default'
	)
);

# backend

$conf['cache'] = array (
	'default' => array (
		'bin' => 'RedisCache',
		'key' => 'default'
	),
	'user' => array (
		'bin' => 'RedisCache',
		'key' => 'default'
	),
	'content' => array (
		'bin' => 'FileCache',
		'key' => 'default',
	)
);



# mysql
 
$conf['Mysql'] = array (
	'default' => array (
		'master' => array (
			'database' => 'D72',
			'username' => 'root',
			'password' => 'adolf',
			'host' => '127.0.0.1',
			'port' => '',
			'prefix' => '',
		),
		'slave' => array (
			array (
				'database' => 'D72',
				'username' => 'root',
				'password' => 'adolf',
				'host' => 'localhost',
				'port' => '',
				'prefix' => '',
			),
			array (
				'database' => 'D72',
				'username' => 'root',
				'password' => 'adolf',
				'host' => 'localhost',
				'port' => '',
				'prefix' => '',
			),
		),
	),
);


# logger

$conf['Logger'] = array (
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

$conf['FileCache'] = array (
	'cache_dir' => CACHE_PATH,
);

# redis

$conf['Redis'] = array (
	'default' => array (
		'host' => 'D72',
		'port' => 'localhost',
		'timeout' => 'root',
		'delay' => 'adolf',
		'auth' => '',
		'db' => '', 
	),
);


$conf['hash_salt'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
$conf['private_key'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
