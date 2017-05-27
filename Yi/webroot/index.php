<?php

	define('SITE_DOMAIN', 'yi.com');
	define('HOME_PAGE', 'http://m.yi.com');		
	define('ASSETS_URL', '//www.yi.com/public');	// 图片上传后的URL基址
	
	require __DIR__ .'/../app/bootstrap.php';
	
	/*
	echo base_convert(PHP_INT_MAX, 10, 36), "<br>";
	echo base_convert(487410776, 10, 36), "<br>";
	echo base_convert(2487410776, 10, 36), "<br>";
	echo base_convert(8487410776, 10, 36), "<br>";
	echo base_convert(1000000000, 10, 36), "  10<br>";
	echo base_convert('zzzzzz', 36, 10), "    10<br>";
	echo base_convert('100000', 36, 10), "    10<br>";
	
	
 
	
	for($i=0; $i<3; $i++) {
		
		echo "i == {$i} <br>";
		try {
			
			throw new \Exception;
			
		} catch (\Exception $e) {
			
			echo " catch i == {$i} <br>";
			
			throw $e;
			
			
			
		} finally{
			
			echo "finally run {$i}<br>";
			
			
		}
	
	
	}
	
	
	
	
	//echo 922337203685477581111-PHP_INT_MAX*100;
 
	echo base_convert(8487410776, 10, 36), "<br>";
	echo base_convert(8487410776, 10, 36), "<br>";
	echo base_convert(8487410776, 10, 36), "<br>";
	echo base_convert('1000000000000', 36, 10), "<br>";
	echo base_convert('1000000', 36, 10), " --7位<br>";
	echo base_convert('100000', 36, 10), " --7位<br>";
	echo base_convert('3wd6o2g', 36, 10), "<br>";
	
	echo base_convert('olkdi0', 36, 10), "<br>";
	echo base_convert('0olkdi0', 36, 10), "<br>";
	echo base_convert('0olkdi0', 36, 10), "<br>";
	echo base_convert('0olkdi0', 36, 10), "<br>";
	echo '六位36进制对应十进制范围：';
	echo base_convert('100000', 36, 10),'------', base_convert('zzzzzz', 36, 10), "<br>";
	echo '七位36进制对应十进制范围：';
	echo base_convert('1000000', 36, 10),'------', base_convert('zzzzzzz', 36, 10), "<br>";
	
		echo '8位36进制对应十进制范围：';
	echo base_convert('10000000', 36, 10),'------', base_convert('zzzzzzzz', 36, 10), "<br>";
 
	
	*/
	
 
	
	/* 
	echo PHP_INT_MAX, "<br>";
	echo intval(27670116110564327422), "<br>";
	echo intval(9223372046854775807), "<br>";
				9223372026854776832
	echo intval(PHP_INT_MAX+1), "<br>";
	echo intval(PHP_INT_MAX+2), "<br>";
	echo intval(PHP_INT_MAX+3), "<br>";
	echo intval(PHP_INT_MAX+4), "<br>";
	echo intval(PHP_INT_MAX+5), "<br>";
	echo intval(PHP_INT_MAX+5), "<br>";
	echo intval(PHP_INT_MAX+10), "<br>";
	echo intval(PHP_INT_MAX+100000000000000000), "<br>";
	echo intval(PHP_INT_MAX*10), "<br>";
	echo intval(PHP_INT_MAX+PHP_INT_MAX/100000000000), "<br>";
	var_dump(intval('9223372036854775807')); echo "<br>";
	var_dump(intval('9223372036854775808')); echo "<br>";
	var_dump(intval('9223372036854775809')); echo "<br>";
	var_dump(intval('9223372036854775810')); echo "<br>";
	var_dump(intval('9223372036854775811')); echo "<br>";
	var_dump(intval('92233720368547758111')); echo "<br>";
	var_dump(intval('922337203685477581111')); echo "<br>";
	var_dump(922337203685477581111); echo "<br>";
	var_dump(intval(922337203685477581111)); echo "<br>";
	echo '123123<br>';
	
	var_dump( PHP_INT_MAX+1); echo "<br>";
	echo base_convert(PHP_INT_MAX+1, 10, 32), "<br>";
	echo base_convert(PHP_INT_MAX+2, 10, 32), "<br>";
	echo base_convert(PHP_INT_MAX+3, 10, 32), "<br>";
	echo base_convert('07vvvvvvvvvvvv', 32, 10), "<br>";
	echo base_convert('864jeiu6l12', 32, 10), "<br>";
	echo PHP_INT_MAX, "<br>";
	echo  base_convert(PHP_INT_MAX, 10, 32), "<br>";
	var_dump(9223372036854775809);
	var_dump(is_numeric(9223372036854775809));
	var_dump(92233720368547758000 > floatval(PHP_INT_MAX) );
	var_dump(is_int(9223372036854775807));
	var_dump(strnatcmp('7vvv','007vvv'));
 
 
	
 
function query_bulider($params, $separator){
	
	$joined = [];
	foreach($params as $key => $value) {
	   $joined[] = "$key=$value";
	}
	return implode($separator, $joined);
}

function query_bulider2($params, $separator){
	
	$joined = '';
	foreach($params as $key => $value) {
	   $joined .= "$key=$value$separator";
	}
	return rtrim($joined,$separator);

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
  $a = rawurldecode(http_build_query($set, '', ', ', \PHP_QUERY_RFC3986));
}
echo $a,'<br>';
$time2 = microtime(true);
for($i=0;$i<10000;$i++){ 
  $a = query_bulider($set, ', ');
}
echo $a,'<br>';
$time3 = microtime(true);
for($i=0;$i<10000;$i++){ 
  $a = query_bulider2($set, ', ');
}
echo $a,'<br>';
$time4 = microtime(true);


for($i=0;$i<10000;$i++){ 
 $a = strnatcmp('7vvvvvvvvvvvv', '7vvvvvvvvvvva');
}
echo $a,'<br>';
$time5 = microtime(true);

echo $time2-$time1, '<br>';
echo $time3-$time2,'<br>';
echo $time5-$time4,'<br>';




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
