<?php

# mysql
 
$conf['mysql'] = array (
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

$conf['filecache'] = array (
	'cache_dir' => CACHE_PATH,
);

# redis

$conf['redis'] = array (
	'default' => array (
		'host' => 'D72',
		'port' => 'localhost',
		'timeout' => 'root',
		'delay' => 'adolf',
		'auth' => '',
		'db' => '', 
	),
);


$conf['drupal_hash_salt'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
