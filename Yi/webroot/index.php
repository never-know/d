<?php
	
	// require __DIR__ .'/../app/bootstrap.php';	
	 
	 
try{
	
	$dns = '//root:@mysql:host=192.168.90.44;port=3306;dbname=test;charset=utf8:80';
	$selected_db = parse_url($dns);
	print_r($selected_db);
	$connect = new \PDO($selected_db['host'], 'root', '', array(
			\PDO::ATTR_EMULATE_PREPARES => false,
			//\PDO::ATTR_PERSISTENT => true,
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
	));	
	
 sleep(10);
	$sql = 'select * from yi_sms where id = 4';
	$stmt	= $connect->query($sql);
	
	$result	= $stmt->fetch(\PDO::FETCH_ASSOC);
	print_r($result);
	
} catch (\Throwable $T) {
	var_dump($T);
	echo $T->getCode();
}