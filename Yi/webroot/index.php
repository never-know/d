<?php
	
	require __DIR__ .'/../app/bootstrap.php';	
/*
function query_bulider($params, $separator){
	
	$joined = [];
	foreach($params as $key => $value) {
	   $joined[] = "$key=$value";
	}
	return implode($separator, $joined);
}

	$set = [
			'tag' 		=> 231,
			'start' 	=> 342, 
			'end' 		=> 2342, 
			'region' 	=> 234234,
			'title' 	=> ':title', 
			'desc' 		=> ':desc',
			'icon' 		=> ':icon'
		];
$time1 = microtime(true);
for($i=0;$i<10000;$i++){ 
  rawurldecode(http_build_query($set, '', ', ', \PHP_QUERY_RFC3986));
}

$time2 = microtime(true);
for($i=0;$i<10000;$i++){ 
  query_bulider($set, ', ');
}
$time3 = microtime(true);

echo $time2-$time1, '<br>';
echo $time3-$time2;



exit;

*/





// pdo
/*
try{
	
	$dns = '//root:123456@mysql:host=192.168.1.105;port=3306;dbname=test;charset=utf8:80';	$selected_db = parse_url($dns);
	//$dns = '//root:@mysql:host=192.168.90.44;port=3306;dbname=test;charset=utf8:80';	$selected_db = parse_url($dns);
	//print_r($selected_db);
	$connect = new \PDO($selected_db['host'], 'root', '', array(
			\PDO::ATTR_EMULATE_PREPARES => false,
			//\PDO::ATTR_PERSISTENT => true,
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
	));	
	
	
	$sql = 'select * from yi_sms where hash = :id';
	$stmt = $connect->prepare($sql); 
	
	 $stmt->bindValue(':id', '4aae293127d148bbe99fa13d81ceb9a0', \PDO::PARAM_STR);
	
	$stmt->execute();
	 sleep(10);
	//sleep(10);
	 $result	= $stmt->fetchAll(\PDO::FETCH_ASSOC);
	print_r($result);
	
} catch (\Throwable $T) {
	var_dump($T);
	echo $T->getCode();
}

*/
/*
$connect = new \mysqli('192.168.1.105', 'root', '123456', 'test', 3306);
$sql = 'select * from yi_sms where id = 4';
	
if ($result_single	= $connect->query($sql, MYSQLI_STORE_RESULT)) {

sleep(10);			
	$result	= $result_single->fetch_all(MYSQLI_ASSOC);
	$result_single->free_result();		
}
  
print_r($result);
print_r($connect->error_list);
print_r($connect->get_warnings());

 

*/
/*
var_dump((string)true=='2');


$connect = new \mysqli('192.168.1.105', 'root', '123456', 'test', 3306);
$sql = 'select * from yi_sms where id = ?';

	if ($stmt = $connect->prepare($sql)) {
	print_r($connect->error_list);
var_dump($connect->get_warnings());
		$id = 4;
		
		$stmt->bind_param('i',$id);			 
		if ($stmt->execute()) {
			$thread_id = $connect->thread_id;

 
	//var_dump($connect->kill($thread_id));
	$connect->close();
			if ($result_single = $stmt->get_result()) {	
				$result = $result_single->fetch_all(MYSQLI_ASSOC);
				$result_single->free_result();
			}  	
		}	
		
		//$stmt->close();
	}
	
var_dump($result);
print_r($connect->error_list);
var_dump($connect->get_warnings());

	echo "<br>";
		print_r($stmt->error_list);	
echo "<br>";
echo "<br>";
echo "<br>";	

*/


/*
 
mysqli_report(MYSQLI_REPORT_ALL);
try{
   $k ='helloworld';

//$connect = new \mysqli('192.168.1.105', 'root', '123456', 'test', 3306);
// ts001:a123456@121.43.182.222:3306#annyi
$connect = new \mysqli('121.43.182.222', 'ts001', 'a123456', 'annyi', 3306);
	
	$query = "SELECT * from yi_sms WHERE id=?   LIMIT 1";
	$stmt = $connect->prepare($query);

	$stmt->bind_param('i', $code); 
	$code = 4;
	sleep(10);
	$stmt->execute();

	//var_dump($stmt->attr_get(MYSQLI_STMT_ATTR_CURSOR_TYPE) == MYSQLI_CURSOR_TYPE_NO_CURSOR );
//	echo $connect->thread_id;
	//$thread_id = $connect->thread_id;

 
	//$connect->kill($thread_id);
	$result_single = $stmt->get_result();	

	$result	= $result_single->fetch_assoc();
	$result_single->free_result();
	$stmt->close();
	print_r($result);
	
} catch (\mysqli_sql_exception $e) {
	echo 'abc';
	echo $e->getMessage(),$e->getCode();
	if(!empty($m)) echo $m;
 
    
} catch(\Throwable $t){

	echo '456';
	print_r($connect->errno);
	print_r($stmt->errno);
	echo $t->getMessage(),$t->getCode();
	echo 12;
	
}

 */
