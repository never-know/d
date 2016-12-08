
	<div class="login-wrapper">
		<div class="login-header">
				<a href="" target="_blank" class="login-logo"><img src="/public/images/logo.jpg"></a>
				<em>欢迎登陆</em>
				<span>没有QI账号？</span>
				<a href="/regist.html" target="_blank" class="regist-link">立即注册</a>
		</div>
		<div class="login-content">
			<div class="login-image">
				<div class="login-image-inner">
					<img src="/public/images/loginPic.jpg" />
				</div>
			</div>
			<div class="login-form">
				<div class="login-box">
					<div class="login-title">账户登录</div>
			 
					<div class="login-msg" id="login-msg">
						<i class="icon-error iconfont">&#xe632;</i>
						<span></span> 
					</div>
				 
					<form id="login-form" method="post" onsubmit="return false;">

						<div class="login-name input-focus" id="login-name">
						
							<input id="loginname" type="text" class="loginname" name="loginname"  autocomplete="off"   placeholder="邮箱/用户名/已验证手机">
							<i class="icon-login iconfont">&#xe63b;</i>
						</div>
						<div class="login-pwd">
					
							<input type="password" id="loginpwd" name="loginpwd" class="loginpwd"  autocomplete="off" placeholder="密码" onkeypress = "Min.util.checkCapslock(event,this)">
								<i class="icon-login iconfont">&#xe63a;</i>
						</div>
						<div class="login-code" id="login-code">
	
						</div>
						<div class="login-li">
							<a href="#" class="f-fl">免费注册</a>
							<a href="#" id="forget-pwd"  class="f-fr" >忘记登陆密码?</a>	
						</div>
						<div class="msg-warn hide" id="msg-warn"> <i class="icon-warn iconfont">&#xe644;</i>公共场所不建议自动登录，以防账号丢失</div>
						<button type = "submit" class="login-btn" id="loginsubmit"  sindex="0">登&nbsp;&nbsp;&nbsp;&nbsp;录</a>
						</button>
						 
					</form>
				 
				</div>
			 </div>
		</div>	 
	</div>