<style>
.weui-vcode-btn {
    border-left: 1px solid #e5e5e5;
    color: #3cc51f;
    display: inline-block;
    font-size: 16px;
    margin-left: 5px;
    font-size: 16px;
    margin-left: 5px;
      padding: 10px 0 6px 16px; 
}
 
 
.weui_cells_tips a {
    color: #586c94;
	 
}
 
.weui_vcode2 .weui_cell_hd, .weui_vcode2 .weui_cell_bd{
    font-size: 16px;
    margin-left: 5px;
    padding: 8px 0 4px 0; 
  

}
 

.demos-title {
  text-align: center;
  font-size: 34px;
  color: #3cc51f;
  font-weight: 400;
  margin: 0 15%;
}

.demos-sub-title {
  text-align: center;
  color: #888;
  font-size: 14px;
}

.demos-header {
  padding: 35px 0;
}

.demos-content-padded {
  padding: 15px;
}

.demos-second-title {
  text-align: center;
  font-size: 24px;
  color: #3cc51f;
  font-weight: 400;
  margin: 0 15%;
}

footer {
  text-align: center;
  font-size: 14px;
  padding: 20px;
}

footer a {
  color: #999;
  text-decoration: none;
}
.weui_label {
   width: 72px; 
} 
	.weui_btn{
padding-top: 4px;
}
 
	</style>
<form id="form">
	
    <div class="weui_cells weui_cells_form" style="margin-bottom: 8px;">
       
		<div class="weui_cell weui_vcode2">
                <div class="weui_cell_hd"><label class="weui_label">+86</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="tel" required pattern="[0-9]{11}" maxlength="11"  emptytips="请输入手机号" notmatchtips="请输入正确的手机号" placeholder="请输入手机号码" id="phone" name="phone">
                </div>
            </div>
        <div class="weui_cell weui_vcode2">
            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" required  name="code" placeholder="请输入验证码" tips="请输入验证码">
            </div>
            <div class="weui_cell_ft" id="smscode">
                <i class="weui_icon_warn hide"></i>
                <a href="javascript:;" class="weui-vcode-btn" id="weui-vcode-btn" sindex="0">获取验证码</a>
            </div>
        </div>
		<input type="hidden" value="<?=get_token()?>" name="csrf_token" />
    </div>
	<div class="weui_cells_tips">确认绑定代表您同意<a href="#abc"  data-target="#full" class="open-popup">《安逸时光网服务条款》</a></div>
	 
    <div class="weui_btn_area" id="abc">
        <a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary" sindex="">确认绑定</a>
    </div>
</form>

 
<div id="full" class="weui-popup-container" >
      <div class="weui-popup-overlay"></div>
      <div class="weui-popup-modal">
        <header class="demos-header">
          <h2 class="demos-second-title">安逸时光网服务协议</h2>
        </header>

        <article class="weui_article">

         <p>感谢您使用安逸时光网！</p>
         <p class="em_text">为使用安逸时光网服务（以下简称“本服务”），你应当阅读并遵守《安逸时光网服务协议》（以下简称“本协议”）。请你务必审慎阅读、充分理解各条款内容，特别是免除或限制责任的相应条款，以及开通或使用某项服务的单独协议，并选择接受或不接受。限制、免责条款可能以加粗形式提示你注意。</p>
		 <p class="em_text">除非你已阅读并接受本协议所有条款，否则你无权使用安逸时光网服务。你对本服务的登录、查看、分享信息等行为即视为你已阅读并同意本协议的约束。</p>
          <section>
 
            <section>
             <h4 class="em_text">一、【用户个人信息保护】</h4>
              <p class="em_text">1.1 保护用户个人信息是安逸时光网的一项基本原则，安逸时光网将会采取合理的措施保护用户的个人信息。除法律法规规定的情形外，未经用户许可安逸时光网不会向第三方公开、透露用户个人信息。安逸时光网对相关信息采用专业加密存储与传输方式，保障用户个人信息的安全。</p>
            </section>
			
			<section>
				<h4 class="em_text">二、【用户行为规范】</h4>
				<h5 class="em_text">2.1 【信息内容规范】</h5>
				<p class="em_text">2.1.1 本协议所述信息内容是指用户使用本服务过程中所制作、复制、发布、传播的任何内容，包括但不限于安逸时光网帐号头像、名称、用户说明等注册信息及认证资料，或文字、语音、图片、视频、图文等发送、回复或自动回复消息和相关链接页面，以及其他使用安逸时光网公众帐号服务所产生的内容。</p>
				<p class="em_text">2.1.2 你理解并同意，安逸时光网一直致力于为用户提供文明健康、规范有序的网络环境，你不得利用安逸时光网服务制作、复制、发布、传播如下干扰安逸时光网正常运营，以及侵犯其他用户或第三方合法权益的内容：</p>
					<p class="em_text no_extra">2.1.2.1 发布、传送、传播、储存违反国家法律法规禁止的内容：</p>
					<p class="em_text no_extra">（1）违反宪法确定的基本原则的；</p>
					<p class="em_text no_extra">（2）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p>
					<p class="em_text no_extra">（3）损害国家荣誉和利益的；</p>
					<p class="em_text no_extra">（4）煽动民族仇恨、民族歧视，破坏民族团结的；</p>
					<p class="em_text no_extra">（5）破坏国家宗教政策，宣扬邪教和封建迷信的；</p>
					<p class="em_text no_extra">（6）散布谣言，扰乱社会秩序，破坏社会稳定的；</p>
					<p class="em_text no_extra">（7）散布淫秽、色情、赌博、暴力、恐怖或者教唆犯罪的；</p>
					<p class="em_text no_extra">（8）侮辱或者诽谤他人，侵害他人合法权益的；</p>
					<p class="em_text no_extra">（9）煽动非法集会、结社、游行、示威、聚众扰乱社会秩序；</p>
					<p class="em_text no_extra">（10）以非法民间组织名义活动的；</p>
					<p class="em_text no_extra">（11）不符合《即时通信工具公众信息服务发展管理暂行规定》及遵守法律法规、社会主义制度、国家利益、公民合法利益、公共秩序、社会道德风尚和信息真实性等“七条底线”要求的；</p>
				<p class="em_text">（12）含有法律、行政法规禁止的其他内容的。</p>
				<p class="em_text">2.1.2.2 发布、传送、传播、储存侵害他人名誉权、肖像权、知识产权、商业秘密等合法权利的内容；</p>
				<p class="em_text">2.1.2.3 涉及他人隐私、个人信息或资料的内容；</p>
				<p class="em_text">2.1.2.4 发表、传送、传播骚扰信息、广告信息及垃圾信息或含有任何性或性暗示的内容；</p>
				<p class="em_text">2.1.2.5 其他违反法律法规、政策及公序良俗、社会公德或干扰微信公众平台正常运营和侵犯其他用户或第三方合法权益内容的信息。</p>
					
				<h5 class="em_text">3.2 【平台使用规范】</h5>
				<p class="em_text">3.2.1 本条所述平台使用是指用户使用本服务所进行的任何行为，包括但不限于注册登录、申请认证、运营推广以及其他使用安逸时光网服务所进行的行为。</p>
				<p class="em_text">3.2.2 你不得利用安逸时光网服务进行如下行为：</p>
					<p class="em_text">3.2.2.1 提交、发布虚假信息；</p>
					<p class="em_text">3.2.2.2 虚构事实、隐瞒真相以误导、欺骗他人的；</p>
					<p class="em_text">3.2.2.3 侵害他人名誉权、肖像权、知识产权、商业秘密等合法权利的；</p>			
					<p class="em_text">3.2.2.4 任何导致或可能导致安逸时光网与第三方产生纠纷、争议或诉讼的行为。</p>
					<p class="em_text">3.2.2.5 使用插件，利用程序漏洞进行非法操作的，严重损害商户与平台利益的，平台有权利收回非法所得，冻结帐号。</p>
				<h5 class="em_text">3.3【对自己行为负责】</h5>
				<p class="em_text">你理解并同意，安逸时光网仅为用户提供信息分享、传播及获取的平台，你必须为自己注册帐号下的一切行为负责，包括你所发表的任何内容以及由此产生的任何后果。你应对本服务中的内容自行加以判断，并承担因使用内容而引起的所有风险，包括因对内容的正确性、完整性或实用性的依赖而产生的风险。腾讯无法且不会对因前述风险而导致的任何损失或损害承担责任。</p>
				<h4 class="em_text">
			</section>
          </section>
          <section>
            <a href="javascript:;" class="weui_btn weui_btn_plain_primary" onclick="window.history.go(-1);">关闭</a>
          </section>
        </article>
      </div>
    </div>
	
</div>	
	
	<script src="/public/js/m/select.js"></script>	
	<script>
	var hash  = window.location.hash || '';
	window.onhashchange = function(){
		var new_hash = window.location.hash || ''; 	//substring(1)用来减去地址栏的地址中的#号
		if (hash == '#abc' && new_hash == '') {
			$.closePopup();
		}
		hash = new_hash;
	} 
	 
	var $form = $("#form");  $form.form();
	$("#formSubmitBtn").on("click", function(){
	 
		if ($(this).attr("sindex") == 1) return;
			$(this).attr("sindex", 1);
			
		$form.validate(function(error){
			if(error){} else {
				$.ajax({
					url:'/bind.html', 
					type:'POST', 
					data: $form.serialize(),
					success: function(data){
						if (data.statusCode == 1 ) {
							window.location.href = '/';
						} else {
							 $.toast(data.message, "cancel");
							 $('#formSubmitBtn').attr("sindex", 0);
						}
					},
					error:function(){
						 $.toast("网络连接失败", "cancel");
						 $('#formSubmitBtn').attr("sindex", 0);
					}
				});
			}
		});
		
	});
	
	var delayTime = 60, phone ='';
	
	$("#smscode").on("click", function(){
	  
		phone = $('#phone').val();
		
		if ( phone == '') {
			$.toast('手机号码为空', "cancel");
			return;
		}
		
		if (!/^(13|15|18|14|17)[\d]{9}$/.test(phone)) {
			$.toast('手机号码错误', "cancel");
			return;
		}

		if ($('#weui-vcode-btn').attr("sindex") == 1) return;
			$('#weui-vcode-btn').attr("sindex", 1);
		$.showLoading('短信发送中...');
		$.ajax({
			url:'/bind/send.html', 
			type:'POST', 
			data: {csrf_token:"<?=get_token('m_bind_send')?>", phone:phone},
			success: function(data){
				$.hideLoading();
				 
				if (data.statusCode == 1 ) {
					setTimeout(function(){$.toast('发送成功')}, 200);
					setTimeout(countDown, 1000);
				} else {
					 $.toast(data.message, "cancel");
					 $('#weui-vcode-btn').attr("sindex", 0);
				}
			},
			error:function(){
				$.hideLoading();
				 $.toast("网络连接失败", "cancel");
				 $('#weui-vcode-btn').attr("sindex", 0);
			}
		});
	});

	function countDown() {
		 delayTime--;
		 var code = $('#weui-vcode-btn');
		
		code.html('发送成功( ' + delayTime +' )');
		if (delayTime == 1) {
			delayTime = 60;
			code.attr("sindex", 0);
			code.html("获取验证码");
		} else {
			setTimeout(countDown, 1000);
		}
	}
 
	</script>
 
    
	
	
	
 
 
	