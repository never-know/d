 	
	
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
    display: block;
    font-size: 16px;
    padding: 0.5em 15px;
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
.weui-agree a {
    color: #586c94;
}
	
	</style>
<form id="form">
	
    <div class="weui_cells weui_cells_form">
       
		<div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">+86</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number"   pattern="[0-9]{11}" maxlength="11" placeholder="请输入手机号码">
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
            <input id="weuiAgree" class="weui-agree-checkbox" type="checkbox" checked>
            <span class="weui-agree-text">
                阅读并同意<a href="javascript:void(0);">《相关条款》</a>
            </span>
        </label>
    <div class="weui_btn_area">
        <a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary">提交</a>
    </div>
</form>

 
    
	
	
	
 
 
	