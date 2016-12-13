<?php 
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


?>