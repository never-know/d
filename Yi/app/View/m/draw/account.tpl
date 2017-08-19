<div>
<?php if (!empty($result['list'])) : ?>
<form id="form">
    <div class="weui_cells weui_cells_form" style="margin-bottom: 8px;">
		<div class="weui_cell weui_vcode2">
                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" readonly="readonly" value="<?=($result['list'][0]['real_name']??'')?>" >
                </div>
            </div>
        <div class="weui_cell weui_vcode2">
            <div class="weui_cell_hd"><label class="weui_label">帐号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input"   readonly="readonly" value="<?=($result['list'][0]['account_name']??'')?>">
            </div>
            
        </div>
    </div>
	<div class="weui_cells_tips">暂时只支持支付宝帐号提现</div>
</form>

<?php else : ?>

<form id="form">
    <div class="weui_cells weui_cells_form" style="margin-bottom: 8px;">
		
        <div class="weui_cell weui_vcode2">
           
            <div class="weui_cell_bd weui_cell_primary">
				 
                <input class="weui_input" type="text" required  name="realname" emptytips="请输入您的姓名" placeholder="请输入您的姓名" tips="请输入您的姓名"  >
            </div>
        </div>
		
		<div class="weui_cell weui_vcode2">
            <div class="weui_cell_bd weui_cell_primary">
				 
                <input class="weui_input" type="text" required  emptytips="请输入您的支付宝帐号"   placeholder="请输入您的支付宝帐号"   name="account"  >
            </div>
        </div>
	 
		<input type="hidden" value="<?=get_token()?>" name="csrf_token" />
		 
    </div>
	<div class="weui_cells_tips"> 暂时只支持支付宝帐号提现</div>
	 
    <div class="weui_btn_area" id="abc">
        <a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary" sindex="">保存</a>
    </div>

</form>
<script>
 
	var $form = $("#form");  $form.form();
	$("#formSubmitBtn").on("click", function(){
	 
	
			
		$form.validate(function(error){
			if(error){	$(this).attr("sindex", 0);} else {
			
				if ($(this).attr("sindex") == 1) return;
				$(this).attr("sindex", 1);
				$.ajax({
					url:'/draw/account.html', 
					type:'POST', 
					data: $form.serialize(),
					success: function(data){
						if (data.statusCode == 1 ) {
							window.location.href = '/user/profile.html';
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
	
	 
</script>
<?php endif;?>
</div>
 
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
.weui_cells_tips{
font-size:13px;
	 
}
 
.weui_vcode2 .weui_cell_hd, .weui_vcode2 .weui_cell_bd{
    font-size: 14px;
    margin-left:6px;
    padding: 6px 0 3px 0; 
}
.weui_label {
   width: 60px; 
} 
.weui_btn{
padding-top: 4px;
}
 
	</style>
<style>
 
 
  
</style>
 
	
    
	
	
	
 
 
	