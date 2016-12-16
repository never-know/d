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
		'bin' => 'Redis',
		'key' => 'default'
	),
	'user' => array (
		'bin' => 'Redis',
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
		'rw_separate'	=> true,
		'master' 		=> '//ts001:a123456@p:127.0.0.1:8080#annyi',
		'slave'			=> array (
			'//ts001:a123456@p:127.0.0.1:8080#annyi',
			'//ts001:a123456@p:127.0.0.1:8080#annyi',
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
	'default' => array (
		'cache_dir' => CACHE_PATH,
	)
);

# redis

$conf['Redis'] = array (
	'default' => array (
		'host' => '127.0.0.1',
		'port' => '6379',
		'timeout' => '1',
		'delay' => '200',
		'auth' => '',
		'db' => '', 
	)
);


$conf['hash_salt'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
$conf['private_key'] = 'cfDvVLhVONY5ijnnLJ0OusjqUTr_bcPPuHmlYji9F70';
