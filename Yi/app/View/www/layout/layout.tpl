<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<title><?=$result['meta']['title'];?></title>
<?php if(!empty($result['meta']['description'])) : ?>
<meta name="description" content="<?=$result['meta']['description'];?>">
<?php endif;?>
<link rel="stylesheet" href="/public/css/reset.css">
<link rel="stylesheet" href="/public/font/iconfont.css">
<link rel="stylesheet" href="/public/css/yi.css">
<link rel="icon" href="//www.yi.com/favicon.ico" mce_href="//www.yi.com/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="/public/js/Min.js"></script>
<script type="text/javascript" src="/public/js/event.js"></script>
<script type="text/javascript" src="/public/js/cookie.js"></script>
<script type="text/javascript" src="/public/js/minajax.js"></script>
<script type="text/javascript" src="/public/js/jsonp.js"></script>
<script type="text/javascript" src="/public/js/domready.js"></script>
</head>
<body>
<div class="container" id="container" >
	<?php include VIEW_PATH.'/www/layout/header.tpl'; ?>	

	<div class="content">
		<div class="content-inner">
			<?php include VIEW_PATH.'/www/layout/menu.tpl'; ?>	
			<div class="main">
				<div class="main-content">
					<?php view($result); ?> 
				</div>
			</div>	
			<div style="clear:both;"></div>
		</div>
	</div>	
</div>
<?php include VIEW_PATH.'/www/layout/footer.tpl'; ?>	

</body>
</html>
