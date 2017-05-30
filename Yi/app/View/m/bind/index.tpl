 	
	
	<style>
	.weui-vcode-btn {
    border-left: 1px solid #e5e5e5;
    color: #3cc51f;
    display: inline-block;
    font-size: 17px;
    height: 44px;
    line-height: 44px;
    margin-left: 5px;
    padding: 0 0.6em 0 0.7em;
    vertical-align: middle;
}

.weui-agree {
    display: inline-block;
    font-size: 16px;
    padding: 6px 4px 6px 15px;
}
.weui-agree-checkbox {
    background-color: #ffffff;
    border: 1px solid #d1d1d1;
    border-radius: 3px;
    font-size: 0;
    height: 16px;
    outline: 0 none;
    position: relative;
    top: 2px;
    vertical-align: 0;
    width: 16px;
}
.weui-agree-text {
    color: #999999;
}
.weui-agree+a {
    color: #586c94;
}
.weui-agree-checkbox:checked::before {
    color: #09bb07;
    content: "\EA08";
    display: inline-block;
    font-family: "weui";
    font-size: 16px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    left: 50%;
    position: absolute;
    text-align: center;
    text-decoration: inherit;
    text-transform: none;
    top: 50%;
    transform: translate(-50%, -48%) scale(0.73);
    vertical-align: middle;
}
.weui-agree input:checked {
    -webkit-appearance: none;
}
	
	</style>
<form id="form">
	
    <div class="weui_cells weui_cells_form">
       
		<div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">+86</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="tel" required pattern="[0-9]{11}" maxlength="11"  emptytips="请输入手机号" notmatchtips="请输入正确的手机号" placeholder="请输入手机号码">
                </div>
            </div>
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" required="" placeholder="点击验证码更换" tips="请输入验证码">
            </div>
            <div class="weui_cell_ft">
                <i class="weui_icon_warn"></i>
                <a href="javascript:;" class="weui-vcode-btn">获取验证码</a>
            </div>
        </div>
    </div>
	<label for="weuiAgree" class="weui-agree">
			<input id="weuiAgree" class="weui-agree-checkbox" type="checkbox" checked  disabled="disabled">
			<span class="weui-agree-text">
				阅读并同意
			</span>
	</label>
	<a href="#abc"  data-target="#full" class="open-popup">《安逸时光网服务条款》</a>
	
    <div class="weui_btn_area" id="abc">
        <a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary">确认绑定</a>
    </div>
</form>

 
<div id="full" class="weui-popup-container" >
      <div class="weui-popup-overlay"></div>
      <div class="weui-popup-modal">
        <header class="demos-header">
          <h2 class="demos-second-title">关于 jQuery WeUI</h2>
          <p class="demos-sub-title">By 言川 @2016/03/30</p>
        </header>

        <article class="weui_article">
          <h1>大标题</h1>
          <section>
            <h2 class="title">章标题</h2>
            <section>
              <h3>1.1 节标题</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute</p>
            </section>
            <section>
              <h3>1.2 节标题</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </section>
          </section>
          <section>
            <a href="javascript:;" class="weui_btn weui_btn_plain_primary close-popup">关闭</a>
          </section>
        </article>
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
	 
var $form = $("#form");
$form.form();
$("#formSubmitBtn").on("click", function(){
    $form.validate(function(error){
        if(error){
            
        }else{
            
            $.toptips('验证通过提交','ok');
        }
    });
    
});
 
	</script>
 
    
	
	
	
 
 
	