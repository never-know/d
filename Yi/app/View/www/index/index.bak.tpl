<p>
			content
			content
			content
			content
			content
</p>

<a href="http://www.baidu.com/&lt;?php echo 'abc';?>" >baidu</a>

<?php  
$name = safe_json_encode('1;ab中;"c');	
var_dump($name);
var_dump(in_array(1, ['phone','email','name']));
/*
$url = 'http://www.yi.com';  echo str_replace('.', '\.', SITE_DOMAIN);  var_dump(preg_match('!^http[s]?://[a-z]+\.'.str_replace('.', '\.', SITE_DOMAIN).'!', $url)); 


preg_match('!^((/[a-zA-Z0-9]+)+)\.html$!','/index/index.html',$match);

var_dump($match);
echo '<br>';
var_dump(null==0);

var_dump(ip2long('255.255.255.254'));
var_dump(ip2long('255.255.255.255'));
var_dump(ip2long('255.255.255.253'));
$ips = array(
    '::192.168.0.2',
    '0:0:0:0:0:0:192.168.0.2',
    '192.168.0.2',
    '::C0A8:2',
    '0:0:0:0:0:0:C0A8:2'
);
$finals = array();
foreach($ips as $ip) {
    $finals[] = ip2long($ip);
}
var_dump($finals);

var_dump(filter_var('a2b\';ced@qq.com',FILTER_VALIDATE_EMAIL));

echo preg_match('!\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*!','a2b";ced@qq.com');
echo preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/','a2b";ced@qq.com');
echo preg_match('/^[a-zA-Z0-9\-_\x{4e00}-\x{9fa5}]{3,31}$/u','a2中hb";ced@qq.com');
//echo preg_match('/^[a-zA-Z0-9\-_\u4e00-\u9fa5]{3,31}$/u','a2中hb";ced@qq.com');

var_dump(parse_url('root:te001@p:127.0.0.1:8080#annyi'));
var_dump(parse_url('//root:adolf@www.baidu.com:127.0.0.1:8080#p_test_5'));
 */ 
  $c ='123";alert(1);</script><script>\\;var b = \'';

?>

<?php 
 /*
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//try{
	$k ='helloworld';
	$connect = new mysqli('127.0.0.1', 'root', '', 'mysql', 3306);
	$connect->set_charset('utf-8');
	
	$query = "SELECT * from user WHERE user=?   LIMIT 1";
	$stmt = $connect->prepare($query);
	$code = 'root';
	$stmt->bind_param('s', $code); 
	$stmt->execute();

	$result_single = $stmt->get_result();
    echo time();	
	echo 'before;';
	sleep(5);
	echo 'after;';
	echo time();
	echo mysqli_errno($connect);
	echo mysqli_stmt_errno($stmt);
	$m = 'eeeeeeeee';
	$result	= $result_single->fetch_assoc();
	$result_single->free_result();
	$stmt->close();
	print_r($result);
	
/*	
} catch (\mysqli_sql_exception $e) {
		echo '<br>';
		echo "mysqli_sql_exception";
	echo $e->getMessage(),$e->getCode();
	if(!empty($m)) echo $m;
 
    
} catch(\Throwable $t){
		echo '<br>';
		echo "Throwable";
		print_r($connect->errno);
		print_r($stmt->errno);
		echo $t->getMessage(),$t->getCode();
		echo 12;
	 
}
*/
 /*

$a = null;

try{
$a->fetch_assoc();
} catch (Throwable $t){
var_dump($t);
}
 

*/

/*
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
		$connect = new mysqli('127.0.0.1', 'root', '123456', 'mysql', 3306);
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
*/

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
	$thread_id = $connect->thread_id;

		 
	echo time();	
		echo 'before;';
		
		
		echo 'after;';
		echo time();
		echo '<br>';	
	echo $stmt->affected_rows;
	//var_dump($stmt);
	//$connect->close();
		//var_dump($stmt);
	$result_single = $stmt->get_result();	
	var_dump($result_single);
	echo '<br>';
	echo  mysqli_errno($connect);
	$result	= $result_single->fetch_assoc();
	$result_single->free_result();
	$stmt->close();
	print_r($result);
	
	
} catch (\mysqli_sql_exception $e) {
	echo 'mysqli_sql_exception';
	echo $e->getMessage(),$e->getCode();
	if(!empty($m)) echo $m;
 
    
} catch(\Throwable $t){
	echo 'Throwable';
	//print_r($connect->errno);
	print_r($stmt->errno);
	echo $t->getMessage(),$t->getCode();
	echo 12;
	
}


 
var_dump(parse_url('//ts001:a123456@host=121.43.182.222;port=3306;dbname=annyi;charset=utf8'));

var_dump(false==[]);
var_dump(is_array([]));
var_dump([] === array());

$arr = array('init'=>2,'phone'=>'1','c'=>'3');
unset($arr['init']);
echo count($arr);
echo current($arr);
echo end($arr);

var_dump()
//print_r($_SERVER);

echo '<br>';
var_dump(ip2long('255.255.255.300'));echo '<br>';
var_dump(ip2long('255.255.255.2558'));echo '<br>';
var_dump(ip2long('255.255.255.253'));echo '<br>';
var_dump(inet_pton('8.8.8.8'));echo '<br>';
$ips = array(
    '::192.168.0.2',
    '0:0:0:0:0:0:192.168.0.2',
    '192.168.0.2/34',
    '::C0A8:2',
    '0:0:0:0:0:0:C0A8:2'
);
$finals = array();
foreach($ips as $ip) {

    $finals[] = ip2long($ip);
}
var_dump($finals);


*/

/*
echo intval($newer);
echo $_GET['a'],'<br>';
var_dump(in_array($a,[]));
//echo $result

// var_dump(empty([[]]));

echo urldecode('%2B'),'<br>';
$a ='id=04c5670c-627e-40b9-a2a7-5942ebae8dc8&city_id=fc9ebab9-7aa1-49d5-8c56-2bddc7d92ded&log_ip=36.23.17.81&app_security=2acabf04914eeaec6b841a81f09711d8&app_key=c2169bcb-e439-11e5-bbcc-a0d3c1ef5680&user-agent=Mozilla%2F5.0+%28iPhone%3B+CPU+iPhone+OS+9_2+like+Mac+OS+X%29+AppleWebKit%2F601.1.46+%28KHTML%2C+like+Gecko%29+Mobile%2F13C75+MicroMessenger%2F6.3.9+NetType%2F3G%2B+Language%2Fzh_CN&method=GroupBuyDetailNew&timestamp=1483255932000';
$b = [];
$param = explode('&',$a);
foreach($param as $value){
 $tmp = explode('=', $value);
 
 $b[$tmp[0]] = urldecode($tmp[1]);
 echo $tmp[0],':', urldecode($tmp[1]),'<br>';

}



function sign($param){

ksort($param);
		$str = array();
        foreach ($param as $k => &$v) {
			if(is_null($v)|| $v ==='') continue;
			 
				$str[] = $k . ':' . $v;
			 
        }    
		echo strtolower(implode('|',$str)),'<br>';
        echo  md5(strtolower(implode('|',$str))),'<br>';

}





sign($b);




$c='app_key:c2169bcb-e439-11e5-bbcc-a0d3c1ef5680|app_security:2acabf04914eeaec6b841a81f09711d8|city_id:fc9ebab9-7aa1-49d5-8c56-2bddc7d92ded|id:04c5670c-627e-40b9-a2a7-5942ebae8dc8|log_ip:36.23.17.81|method:groupbuydetailnew|timestamp:1483255932000|user-agent:mozilla/5.0 (iphone; cpu iphone os 9_2 like mac os x) applewebkit/601.1.46 (khtml, like gecko) mobile/13c75 micromessenger/6.3.9 nettype/3g  language/zh_cn';

echo $c, '<br>';
echo md5($c),'<br>';



echo $kkk ?? '123';

$kkk = '';

var_dump(($kkk ?? '123'));
$kkk = 0;

var_dump(($kkk ?? '123'));

var_dump(0==false);



echo $_SERVER['REQUEST_TIME'] - 1483365439;

var_dump('a' == 1);
var_dump(1 == 'a');


*/





?>



<script>

 
 
 var b = <?=safe_json_encode($c);?>;
 
 
 

</script>
 