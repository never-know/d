
<div>
<form id="form">
	
    <div class="weui_cells weui_cells_form" style="margin-bottom: 8px;">
       
		 
        <div class="weui_cell weui_vcode2">
            
            <div class="weui_cell_bd weui_cell_primary" >
                <input class="weui_input" type="text" required  name="nickname" placeholder="" tips="请输入昵称" value="<?=$result['nickname']?>">
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
	(function(){           var isPageHide = false;           window.addEventListener('pageshow', function(){               if(isPageHide) {                   window.location.reload();               }           });           window.addEventListener('pagehide', function(){               isPageHide = true;           });       })();
	 
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
							history.replaceState(null, "用户主页", "/user.html?v=" + new Date().getTime());
							$.toast('修改成功')
							setTimeout(function(){window.location.href="/user/profile.html";}, 200);
							
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
 
    
	
	
	
 
 
	