<?php 

/*
mysqli_report(MYSQLI_REPORT_ALL);
try{
   $k ='helloworld';
	$connect = new mysqli('127.0.0.1', 'root', '123456', 'yycms', 3306);
	
	$query = "SELECT * from yy_active WHERE id=?   LIMIT 1";
	$stmt = $connect->prepare($query);

	$stmt->bind_param('i', $code); 
	$code = 2;
		
	$stmt->execute();
	sleep(10);
	$result_single = $stmt->get_result();	

	$result	= $result_single->fetch_assoc();
	$result_single->free_result();
	$stmt->close();
	print_r($result);
	
} catch (\mysqli_sql_exception $e) {

	echo $e->getMessage(),$e->getCode();
	if(!empty($m)) echo $m;
 
    
} catch(\Throwable $t){
	print_r($connect->errno);
	print_r($stmt->errno);
	echo $t->getMessage(),$t->getCode();
	echo 12;
	
}
*/




try{
	
	$dns = 'root:@mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8:80';
	$selected_db = parse_url($dns);
	$connect = new \PDO($selected_db['host'], $selected_db['user'], $selected_db['pass'], array(
			\PDO::ATTR_EMULATE_PREPARES => false,
			//\PDO::ATTR_PERSISTENT => true,
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
	));	
	
 
	$sql = 'select * from yi_sms where id = :id';
	
	
	$stmt = $connect->prepare($sql); 
				foreach ($param as $key => $value) {
					$vaule_type = \PDO::PARAM_STR;
                    switch ($key) {
                        case is_int($key):
                            $vaule_type = \PDO::PARAM_INT;
                            break;
                        case is_bool($key):
                            $vaule_type = \PDO::PARAM_BOOL;
                            break;
                        case is_null($key):
                            $vaule_type = \PDO::PARAM_NULL;
                            break;
                    }
					
                    $stmt->bindValue($key, $value, $vaule_type);
				}

				$stmt->execute();
	 
				switch ($action) {
					case 'update' :
					case 'delete' :
						$result	= $stmt->rowCount();
						break;
					case 'insert' :
						$result	= $this->lastInsertId($type);
						break;
					case 'select' :
						if (preg_match('/limit\s+1\s*$/i',$sql)) {
							$result	= $stmt->fetch(\PDO::FETCH_ASSOC);
						} else {
							$result	= $stmt->fetchAll(\PDO::FETCH_ASSOC);
						}
						break;
	
	
	
	$stmt	= $connect->query($sql);
	
	$result	= $stmt->fetch(\PDO::FETCH_ASSOC);
	
} catch (\Throwable $T) {
	var_dump($T);
}

?>