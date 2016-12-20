
	<div class="login-wrapper">
		<div class="login-header">
				<a href="" target="_blank" class="login-logo"><img src="/public/images/logo.jpg"></a>
				<em>欢迎注册</em>
				<span>已有QI账号？</span>
				<a href="/login.html" target="_blank" class="regist-link">马上登陆</a>
		</div>
		<div class="login-content">
			<div class="reg-image">
				<div class="reg-image-inner">
					<img src="/public/images/signPic.jpg" >
				</div>
			</div>
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
							 
							<input id="regcode" type="text" class="regcode" name="regcode" tabindex="1" autocomplete="off"  maxlength="4" /> 
							<i class="icon-reg iconfont icon-white" >&#xe619;</i>
							<div class="code-change">
								<img  class="reg-captcha" src="/captcha/get.html?type=reg_1_2" />
								<em>换一张</em>
							 </div>
							 <span id="regcode-error"></span>
						</div>
						<div class="reg-mcode">
							<label for="regmcode"><b class="red">*</b>短信验证码：</label>
							<input id="regmcode" type="text" class="regmcode" name="regmcode" tabindex="1" autocomplete="off"  maxlength="6" /> 
							<i class="icon-reg iconfont icon-white" >&#xe619;</i>
							<a href="javascript:void(0)" class="getcode" id="getcode" sindex="0" token="<?=get_token('www_regist_send');?>">获取短信验证码</a>
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
		
						<input type="hidden" value="<?=get_token();?>" name="csrf_token" />
						
						<div class="service-agreement">请阅读<a href="" >《QI用户注册协议》</a></div>
						<div id="reg-error" class="reg-error">注册失败,请重试</div> 
						<button href="javascript:;" class="btn-img btn-entry reg-btn" id="regsubmit" tabindex="6" type="submit"  sindex=0>同意协议并注册</button>
						 
						

					</form>
					 
				 
				</div>
			</div>
			
		</div>	 
	 
	</div>