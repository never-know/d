<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="http://cdn.annqi.com/public/css/reset.css">
<link rel="stylesheet" href="http://cdn.annqi.com/public/font/iconfont.css">
<link rel="stylesheet" href="http://cdn.annqi.com/public/css/fucome.css">
<link rel="stylesheet" href="http://cdn.annqi.com/public/css/menu.css">
<link rel="stylesheet" type="text/css" href="http://cdn.annqi.com/public/css/lrtk.css" />
<link rel="stylesheet" type="text/css" href="http://cdn.annqi.com/public/css/product.css" />
<link rel="stylesheet" type="text/css" href="http://cdn.annqi.com/public/css/list.css" />
<script type="text/javascript" src="http://cdn.annqi.com/public/js/Min.js"></script>
<script type="text/javascript" src="http://cdn.annqi.com/public/js/event.js"></script>
 

  
</head>
<body>
<div class="container"  id="container">
	 <div class="login-form">
	 <div class="login-box">
		<div class="login-title">

			<h2  class="static">账户登录</h2>
			<h2  class="quick">扫码登录</h2> 
			 
		</div>
		<div class="login-msg" id="login-msg">
			 <i class="icon-error iconfont">&#xe644;</i><span></span>
			 
		</div>
		 
			<form id="login-form" method="post" onsubmit="return false;">

				<div class="login-name input-focus" id="login-name">
				
					<input id="loginname" type="text" class="loginname" name="loginname" tabindex="1" autocomplete="off"   placeholder="邮箱/用户名/已验证手机">
					<i class="icon-login iconfont">&#xe63b;</i>
				</div>
				<div class="login-pwd">
			
					<input type="password" id="loginpwd" name="loginpwd" class="loginpwd" tabindex="2" autocomplete="off" placeholder="密码" onkeypress = "checkCapslock(event,this)">
						<i class="icon-login iconfont">&#xe63a;</i>
				</div>
				<div class="login-code" id="login-code">
					
					<input id="logincode" type="text" class="logincode" name="logincode" tabindex="1" autocomplete="off"  placeholder="验证码">  <i class="icon-captcha iconfont">&#xe634;</i>
					
					<img class="login-captcha"  src="http://cdn.annqi.com/public/images/image.jpg">
					<span>换一张</span>
				</div>
				<div class="login-li">
					 
						<input id="autoLogin"   type="checkbox" class="rememberme-checkbox">
						 
						 <label for="autoLogin">自动登录</label>
				 
				   
					<span class="forget-pwd">
						<a href="#" id="forget-pwd"  class=""  >忘记密码?</a>
					</span>			
				</div>
				 
		

				 
				<div class="msg-warn hide" id="msg-warn"> <i class="icon-warn iconfont">&#xe644;</i>公共场所不建议自动登录，以防账号丢失</div>
				
				<div class="login-btn" id="loginsubmit"  sindex="0">
					<a href="javascript:;" class="btn-img btn-entry" >登&nbsp;&nbsp;&nbsp;&nbsp;录</a>
				</div>
				<div class="linkBox">
					<p class="f12 mb20 clear linkBoxTitle">
					<span class="mr5 mb5 clear block">合作网站账号登录：</span>	
					<div>				
						<a href="javascript:void(0);" title="新浪微博" onclick="window.open('/login/weibo');" class="iconLinks sina" mars_sead="passport_bottom_weibo_button"></a> 
						<a href="javascript:void(0);" onclick="window.open('/login/qq');" title="QQ" class="iconLinks qq" mars_sead="passport_bottom_qq_button"></a> 
						<a href="javascript:void(0);" onclick="javascript:window.open('/login/alipay');" title="支付宝" class="iconLinks alipay" mars_sead="passport_bottom_alipay_button"></a>
						<a href="javascript:void(0);" title="微信" onclick="window.open('/login/weixin');" class="iconLinks weixin" mars_sead="passport_bottom_weixin_button"></a>
						<a class="plusLink on" href="javascript:void(0);"  >更多<i class="icon-more iconfont">&#xe604;</i></a>
					</div>
					</p>
		 
			</div>
			</form>
		 
		</div>
	 </div>

<script type="text/javascript" src="http://cdn.annqi.com/public/js/login.js"></script>

</div>
 
 
</body>


</html>