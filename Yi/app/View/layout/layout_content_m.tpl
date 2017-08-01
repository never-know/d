<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=0">
		<title><?=($result['meta']['title']?:' ');?></title>
		<?php if(!empty($result['meta']['description'])) : ?>
		<meta name="description" content="<?=$result['meta']['description'];?>">
		<?php endif;?>
		<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css"/>
		<link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/0.8.3/css/jquery-weui.min.css"/>
		<link rel="stylesheet" href="/public/css/myi.css?v=9"/>
		<script src="/public/js/m/zepto_fx.min.js"></script>		
	</head>
	  <style>
body p{
 margin-left:auto;
 margin-right:auto;
 margin-top:0px;
 margin:0;
 padding:0;
 width:100%;
 
}
p img{
    width:100%;
	vertical-align: top;
}

<body ontouchstart style="background-color: #f8f8f8;background-color:#f1f1f1">

	<div class="weui_tab">
		<div class="weui_tab_bd">	
		<?=$result['content']?>
		</div>
		 
	</div>	
	
<script>
  $(function() {
    FastClick.attach(document.body);
	});
</script>
	 
</body>
</html>
