
	
				<em>欢迎注册</em>
				<span>已有QI账号？</span>
				<a href="/login.html" target="_blank" class="regist-link">马上登陆</a>
		</div>
		<div class="nav-2" style="top:78px;"> </div> 
		<div class="login-content" style="padding-top:10px;">
			<div class="reg-image">
				<div class="reg-image-inner">
					<div style="width:250px;text-align:center;margin:0 auto;margin-top: 50px;">
						<p style="color:#e4393c;font-size:14px;">扫一扫，关注公众号完成注册</p>
						<img src="/public/images/qrcode_for_gh_d4b7de5f7463_258.jpg" >
					</div>
				</div>
			</div>
			<div class="reg-form">
			
				<div class="reg-msg" id="reg-msg">
						<i class="icon-error iconfont">&#xe632;</i>
						<span id ="error_message">服务受限,请稍候重试</span> 
						<p style="text-align:center;">遇到问题？<a style="color: #e4393c;display:inline;margin-left:6px;">直接扫描右侧二维码完成注册</a></p> 
				</div>
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
							<i class="icon-reg iconfont icon-white" id = "icon-reg">&#xe619;</i>
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
		
						<input type="hidden" value="<?=get_token();?>" name="csrf_token" id="csrf_token" />
						
						<div class="service-agreement">请阅读<a href="" >《QI用户注册协议》</a></div>
					 
						<button href="javascript:;" class="reg-btn" id="regsubmit" tabindex="6" type="submit"  sindex=0>同意协议并注册</button>
						 
						

					</form>
					 
				 
				</div>
			</div>
			
		</div>	 
	 
	</div>