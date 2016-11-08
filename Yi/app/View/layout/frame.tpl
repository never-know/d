<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="/public/css/reset.css">
<link rel="stylesheet" href="/public/font/iconfont.css">
<link rel="stylesheet" href="/public/css/yi.css">
<script type="text/javascript" src="/public/js/Min.js"></script>
<script type="text/javascript" src="/public/js/event.js"></script>
<script type="text/javascript" src="/public/js/cookie.js"></script>

</head>
<body>
<div class="container" id="container" >
	<?php include APP_PATH.'/View/layout/header.tpl'; ?>	

	<div class="content">
		<div class="content-inner">
			<?php include APP_PATH.'/View/layout/menu.tpl'; ?>	
			<div class="main">
				<div class="main-content">
					<?php view(); ?> 
				</div>
			</div>		
		</div>
	</div>	
</div>
<?php include APP_PATH.'/View/layout/footer.tpl'; ?>	
<script type="text/javascript" src="/public/js/domready.js"></script>
</body>
</html>
