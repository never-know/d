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
?>

<?php $c ='123";alert(1);</script><script>\\;var b = \'';?>

<script>

 
 
 var b = <?=safe_json_encode($c);?>;
 
 
 

</script>
 