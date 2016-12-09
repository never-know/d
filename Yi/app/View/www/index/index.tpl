<p>
			content
			content
			content
			content
			content
</p>

<a href="http://www.baidu.com/&lt;?php echo 'abc';?>" >baidu</a>

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

?>

<?php $c ='123";alert(1);</script><script>\\;var b = \'';?>

<script>

 
 
 var b = <?=safe_json_encode($c);?>;
 
 
 

</script>
 