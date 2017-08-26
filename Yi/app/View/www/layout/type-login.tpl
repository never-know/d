<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="/public/css/reset.css">
<link rel="stylesheet" href="/public/font/iconfont.css">
<link rel="stylesheet" href="/public/css/yi.css">
<link rel="stylesheet" href="/public/css/easydialog.css">
<script type="text/javascript" src="/public/js/Min.js"></script>
<script type="text/javascript" src="/public/js/event.js"></script>
<script type="text/javascript" src="/public/js/cookie.js"></script>
<script type="text/javascript" src="/public/js/jsonp.js"></script>
<script type="text/javascript" src="/public/js/minajax.js"></script>
<script type="text/javascript" src="/public/js/dialog.js"></script>
<script type="text/javascript" src="/public/js/domready.js"></script>
 

</head>
<body>
<div class="container" id="container" >
	<div class="nav-2" style="top:78px;"> </div> 
	<div class="login-wrapper">
		<div class="login-header">
				<a href="" target="_blank" class="login-logo"><img src="/public/images/logo15.png"></a>
				<?php view(); ?> 			
</div>
<?php include APP_PATH.'/View/layout/footer.tpl'; ?>	
<script type="text/javascript" src="/public/js/<?=\Min\App::getController();?>.js"></script>
</body>
</html>