<p>
			content
			content
			content
			content
			content
</p>

<a href="http://www.baidu.com/&lt;?php echo 'abc';?>" >baidu</a>

<?php  $url = 'http://www.yi.com';  echo str_replace('.', '\.', SITE_DOMAIN);  var_dump(preg_match('!^http[s]?://[a-z]+\.'.str_replace('.', '\.', SITE_DOMAIN).'!', $url)); ?>

<?php $c ='123;alert(1);
var b = \'';?>

<script>

 var a = '<?=$c;?>';
 var a = <?=check_plain($c);?>;

</script>
 