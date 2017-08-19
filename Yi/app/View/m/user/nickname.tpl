
<div>
<form id="form">
	
    <div class="weui_cells weui_cells_form" style="margin-bottom: 8px;">
       
		 
        <div class="weui_cell weui_vcode2">
            
            <div class="weui_cell_bd weui_cell_primary" >
                <input class="weui_input" type="text" required  name="nickname" placeholder="" tips="请输入昵称">
            </div>
          
        </div>
		<input type="hidden" value="<?=get_token()?>" name="csrf_token" />
    </div>
	<div class="weui_cells_tips">输入你喜欢的昵称 </div>
	 
    <div class="weui_btn_area" id="abc">
        <a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary" sindex="">保存</a>
    </div>
</form>
</div>
<style>
body{
background:#fff;
}
.weui_cells_tips{
font-size:13px;
margin-left:12px;
}
.weui_vcode2{
border-bottom:1px solid #04be02;
margin: 0 20px;
padding: 16px 15px 6px 15px;
}
</style>

	<script>
 
	 
	var $form = $("#form");  $form.form();
	$("#formSubmitBtn").on("click", function(){
	 
		if ($(this).attr("sindex") == 1) return;
			$(this).attr("sindex", 1);
			
		$form.validate(function(error){
			if(error){} else {
				$.ajax({
					url:'/user/nickname.html', 
					type:'POST', 
					data: $form.serialize(),
					success: function(data){
						if (data.statusCode == 1 ) {
							//window.history.go(-1);
							window.location.href="/user.html";
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
 
    
	
	
	
 
 
	