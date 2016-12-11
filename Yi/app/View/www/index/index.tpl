<p>
			content
			content
			content
			content
			content
</p>

<a href="http://www.baidu.com/&lt;?php echo 'abc';?>" >baidu</a>
<!--
<?php  $url = 'http://www.yi.com';  echo str_replace('.', '\.', SITE_DOMAIN);  var_dump(preg_match('!^http[s]?://[a-z]+\.'.str_replace('.', '\.', SITE_DOMAIN).'!', $url)); 


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

?>

<?php $c ='123";alert(1);</script><script>\\;var b = \'';?>
 -->
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
	echo mysqli_errno($connect);
	echo mysqli_stmt_errno ($stmt);
	$m = 'eeeeeeeee';
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



<script>

 
 
 var b = <?=safe_json_encode($c);?>;
 
 
 

</script>
 