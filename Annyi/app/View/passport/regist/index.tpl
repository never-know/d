	
	<div class="login-header">
		<div class="login-logo">
			<a href="http://www.annqi.com/" target="_blank" class="login-logo-image"></a>
			<em>欢迎注册</em>
			<span class="regist-link">已有安琪账号？<a href="http://passport.annqi.com/login.html?ReturnUrl=http%3A%2F%2Fwww.annqi.com" target="_blank" clstag="pageclick|keycount|20150112ABD|1">立即登陆</a></span>		 
		</div>
	</div>
	<div class="nav-2"> </div> 	
	<div class="reg-wrapper">
		<div class="reg-content">	
			<div class="reg-form">
				<div class="reg-box">
					<form id="reg-form" method="post" onsubmit="return false;">
						 <div class="login-name input-focus hide">
							<label for="regname"><b class="red">*</b>用户名：</label>
							<input id="regname" type="text" class="regname" name="regname" tabindex="1" autocomplete="off"   maxlength="20" />
							<i class="icon-reg iconfont">&#xe63b;</i>
							<span id="regname-error"></span>
						</div>
						 <div class="reg-phone">
							<label for="regphone"><b class="red">*</b>手机号码：</label>
							<input id="regphone" type="text" class="regphone" name="regphone" tabindex="1" autocomplete="off"   maxlength="11" style="IME-MODE: disabled;"/>
							<i class="icon-reg iconfont">&#xe619;</i>
							<span id="regphone-error"></span>
						</div>	
						<div class="reg-code" id="reg-code">
							<label for="regcode"><b class="red">*</b>验证码：</label>
							 
							<input id="regcode" type="text" class="regcode" name="regcode" tabindex="1" autocomplete="off"  maxlength="4" /> <i class="icon-reg iconfont">&nbsp;</i><div class="code-change"><img  class="reg-captcha" src="http://util.annqi.com/captcha/get.html?type=reg" /><em>换一张</em>
							 </div>
							 <span id="regcode-error"></span>
						</div>
						<div class="reg-mcode">
							<label for="regmcode"><b class="red">*</b>短信验证码：</label>
							<input id="regmcode" type="text" class="regmcode" name="regmcode" tabindex="1" autocomplete="off"  maxlength="6" /> 
							<a  href="javascript:void(0)" class="getcode" id="getcode" sindex="0" >获取短信验证码</a>
							<span id="regmcode-error"></span>
						</div>
						<div class="reg-pwd">
							<label for="regpwd"><b class="red">*</b>请设置密码：</label>
							<input type="password" id="regpwd" name="regpwd" class="regpwd" tabindex="2" autocomplete="off"  onpaste="return  false" maxlength="20"   onkeypress = "Min.util.checkCapslock(event,this)"/>
							<i class="icon-reg iconfont">&#xe63a;</i>
							<span id="regpwd-error"></span>
						</div>
						<div class="reg-pwd">
							<label for="regpwd1"><b class="red">*</b>请确认密码：</label>
							<input type="password" id="regpwd1" name="regpwd1" class="regpwd" tabindex="2" autocomplete="off"  onpaste="return  false"  onkeypress = "Min.util.checkCapslock (event,this)" maxlength="20"/>
							<i class="icon-reg iconfont">&#xe63a;</i>
							<span id="regpwd1-error"></span>
						 
						</div>
						
						
						
						<div class="service-agreement">请阅读<a href="" >《安琪用户注册协议》</a></div>
						<div id="reg-error" class="reg-error">注册失败,请重试</div>
						<div class="reg-btn">
							<a href="javascript:void(0)" class="btn-img btn-entry" id="regsubmit" tabindex="6"  sindex=0>同意协议并注册</a>
						</div>
						

					</form>
				 
				</div>
			</div>
			<div class="reg-image"><img src="http://cdn.annqi.com/public/images/signPic.jpg" >
			</div>
		</div>	 
	</div>
<script type="text/javascript" src="http://cdn.annqi.com/public/js/reg.js"></script>	
  