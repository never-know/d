
	
	<div class="weui_cells weui_cells_access weui_return">
	<a class="weui_cell" onclick="window.location.href='/user.html'">
        <span class="weui_cell_ft" ></span>返回
    </a>
	</div>
	<div class="weui_panel weui_panel_access" style="margin-top: 14px;">
   
      <div class="weui_panel_bd" style="background:#fff;">
        <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg" style="padding:8px 15px;">
			
			<h4 class="weui_media_title">头像</h4>
        　	 <div class="weui_media_bd">
				&nbsp;
		     </div>
		  　
		   <div class="weui_panel_ft" style="padding:0;">
		    <img class="weui_media_appmsg_thumb" src="<?=$result['headimgurl'];?>" alt="" style="width:50px;">
		   </div>
        </a>
     
      </div>
       
    </div>
	 
 
	<div class="weui_cells">

        <div class="weui_cell" href="javascript:;" id="show-nickname">
          <div class="weui_cell_bd weui_cell_primary" style="min-width: 100px;">
            <p>昵称</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix" id="nick" >西湖区转塘街道西湖区转塘街道转塘街道转塘街道</div>
        </div>
        <div class="weui_cell" href="javascript:;">
          <div class="weui_cell_bd weui_cell_primary">
            <p>手机号码</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix"><?=session_get('user')['phone'];?></div>
        </div>
    </div>
	
	<div class="weui_cells">
		<div class="weui_cell" href="javascript:;" id="qrcode">
		
          <div class="weui_cell_bd weui_cell_primary">
            <p>二维码名片</p>
          </div>
          <div class="weui_cell_ft" style="height: 24px;">
		  <img src="/public/images/abc.png" alt="" width="24">
          </div>
        </div>
    </div>
	
	<div class="weui_cells weui_cells_access">

        <div class="weui_cell hide" href="javascript:;" >
          <div class="weui_cell_bd weui_cell_primary">
            <p>常用搜索地址</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix"></div>
        </div>
        <div class="weui_cell" href="javascript:;">
          <div class="weui_cell_bd weui_cell_primary" id="balance_account">
            <p>提现帐户设置</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix"></div>
        </div>
    </div>
	
	
	<style>
	.weui_panel:before, .weui_panel:after {
		border: none;  
	}
	.weui_dialog, .weui_toast{
		top:10%;
	}
	.weui_dialog_bd{
		font-size:0;
	}
	</style>
	 	
	<script>
	 $(document).on("click", "#show-nickname", function() {
        $.prompt("", "设置昵称", function(text) {
          //$.alert("您的昵称是:"+text, "哦");
		  $('#nick').html(text);
        }, function() {
          //取消操作
        },  $('#nick').html());
      });
	  
	   $(document).on("click", "#balance_account", function() {
        $.prompt("", "设置昵称", function(text) {
         
		  $('#nick').html(text);
        }, function() {
         
        },  $('#nick').html());
      });
	</script>
	 <script>
		 $(document).on("click", "#qrcode", function() {
			$.modal({
			  title: '扫描二维码<p>关注 <span style="color:red;">安逸时光网</span> 微信公众号',
			  text: '<img src="https://m.anyitime.com/public/images/qrcode.png" style="position:relative;width:100%;" />',
			  buttons: [
				{ text: "关闭", className: "primary"},
			  ]
			});
		
		
		return false;
	  });
	 </script>

