
	
	<div class="weui_cells weui_cells_access weui_return">
	<a class="weui_cell" onclick="window.location.href='/user.html'">
        <span class="weui_cell_ft" ></span>返回
    </a>
	</div>
	<div class="weui_panel weui_panel_access hide" style="margin-top: 14px;">
   
      <div class="weui_panel_bd" style="background:#fff;">
        <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg" style="padding:8px 15px;">
			
			<h4 class="weui_media_title">头像</h4>
        　	 <div class="weui_media_bd">
				&nbsp;
		     </div>
		  　
		   <div class="weui_panel_ft" style="padding:0;">
		    <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="" style="width:50px;">
		   </div>
        </a>
     
      </div>
       
    </div>
	 
 
	<div class="weui_cells">

        <div class="weui_cell" href="javascript:;" id="show-nickname">
          <div class="weui_cell_bd weui_cell_primary">
            <p>昵称</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix" id="nick">Noota</div>
        </div>
        <div class="weui_cell" href="javascript:;">
          <div class="weui_cell_bd weui_cell_primary">
            <p>手机号码</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix">18357193201</div>
        </div>
    </div>
	
	<div class="weui_cells">
		<div class="weui_cell" href="javascript:;">
		
          <div class="weui_cell_bd weui_cell_primary">
            <p>二维码名片</p>
          </div>
          <div class="weui_cell_ft" style="height: 24px;">
		  <img src="/public/images/icon_nav_button.png" alt="" width="24">
          </div>
        </div>
    </div>
	<style>
	.weui_panel:before, .weui_panel:after {
		border: none;  
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
	</script>

