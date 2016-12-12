<?php

	//require __DIR__ .'/../app/bootstrap.php';	
	
	
	//	$end	= microtime(true);
	/*
	var_dump(parse_url('//root@p:127.0.0.1:8080#annyi'));
	$connect = new mysqli('127.0.0.1', 'root', '', 'mysql', 3306);
	var_dump($connect);
	get_resource_type($connect);
	exit;
	*/
function test(){	

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$i=1;
while($i>0) {
	$i--;
	echo "<br>round: $i<br>";
	try {
		 
		 
		echo "<br>start<br>";
		//throw new mysqli_sql_exception('<br> mysqli_sql_exception throwed <br>');
		//throw new Exception('<br> new exception throwed <br>');
		//return 'right';
		echo 'helloworld';
		$connect = new mysqli('127.0.0.1', 'root', '', 'mysql', 3306);
		echo 'aaaaaaaa';
		$connect->set_charset('utf-8');
		
		$query = "SELECT * from user WHERE user=?   LIMIT 1";
		$stmt = $connect->prepare($query);
		$code = 'root';
		$stmt->bind_param('s', $code); 

		$stmt->execute();
		echo time();	
		echo 'before;';
		sleep(10);
		echo 'after;';
		echo time();
		echo '<br>';
		echo mysqli_errno($connect);
		echo mysqli_stmt_errno($stmt);
	
		$result_single = $stmt->get_result();

		$m = 'eeeeeeeee';
		$result	= $result_single->fetch_assoc();
		$result_single->free_result();
		echo "<br>";
		echo mysqli_errno($connect);
		echo mysqli_stmt_errno($stmt);
		echo "<br>";
		$stmt->close();
		print_r($result);
		
	} catch (\mysqli_sql_exception $e) {
		echo $query;	
		 echo mysqli_connect_errno();
		
			echo "<br>mysqli_sql_exception<br>";
			//continue;
			echo "<br>mysqli_sql_exception:continue;<br>";
			echo $e->getMessage(),$e->getCode();
		 
		
	} catch(\Throwable $t){
		 
			echo "<br>Throwable<br>";
			throw new Exception('thorw2');
			echo "<br>Throwable:continue;<br>";
			echo $t->getMessage(),$t->getCode();
			echo 12;
		 
	} finally {
		
		echo '<br>finally<br>';
		
	}
	
}
}


try {
echo test();
} catch (Throwable $t){
	echo $t->getMessage();
	
}