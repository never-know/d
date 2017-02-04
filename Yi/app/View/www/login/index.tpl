
	 
				<em>欢迎登陆</em>
				<span>没有QI账号？</span>
				<a href="/regist.html" target="_blank" class="regist-link">立即注册</a>
		</div>
		<div style="background:width: 100%;background: #e93854;">
		<div class="login-content" style="background: #e93854;">
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
						<div class="login-pwd" id="login-pwd">
					
							<input type="password" id="loginpwd" name="loginpwd" class="loginpwd"  autocomplete="off" placeholder="密码" onkeypress = "Min.util.checkCapslock(event,this)">
								<i class="icon-login iconfont">&#xe63a;</i>
						</div>
						<?php if (intval(session_get('loginerror')) > 3) : ?>
						<div class="login-code" id="login-code" style="display:block">
							<input id="logincode" type="text" class="logincode" name="logincode" tabindex="1" autocomplete="off"  placeholder="验证码" maxlength="4">  <i class="icon-reg iconfont icon-white" id="icon-reg">&#xe619;</i><img class="login-captcha"  src="/captcha/get.html?type=login_1"><span>换一张</span>
						</div>
						<?php endif; ?>
						<div class="login-li" id="login-li">
							<a href="#" class="f-fl">免费注册</a>
							<a href="#" id="forget-pwd"  class="f-fr" >忘记登陆密码?</a>	
						</div>
						<input type="hidden" value="<?=get_token();?>" name="csrf_token" id="csrf_token" />
						<div class="msg-warn hide" id="msg-warn"> <i class="icon-warn iconfont">&#xe644;</i>公共场所不建议自动登录，以防账号丢失</div>
						<button href="javascript:;" type = "submit" class="login-btn" id="loginsubmit"  sindex=0>登&nbsp;&nbsp;&nbsp;&nbsp;录</a>
						</button>
						 
					</form>
				 
				</div>
			 </div>
		</div>	 
		</div>	 
	</div>
	<style>
	.nav-2{
	display:none;
	}
	</style>