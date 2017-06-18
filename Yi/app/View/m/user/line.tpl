
	<div class="weui_panel weui_panel_access">
   
      <div class="weui_panel_bd" style="background-color:#01a9da;background-color: #009264;background-color:#33b2c8;background-color:#7ed6a4;background-color:#1ac9f6;background-color:#d839ce;background-color:#489ef1;background-color:#ec151c;background-color:#e62a31;">
        <a href="/user/profile.html" class="weui_media_box weui_media_appmsg" style="padding:12px 15px;margin-left:.5em">
          <div class="weui_media_hd">
            <img class="weui_media_appmsg_thumb" src="/public/images/avater.png" alt="">
          </div>
          <div class="weui_media_bd" style="margin-left:.8em;margin-top:4px;color:white;letter-spacing: 0px;">
            <h4 class="weui_media_title">Noota</h4>
            <p class="weui_media_text" style="margin-top:2px;font-size:15px;">18357193201</p>
          </div>
		   <div class="weui_media_hd" id="qrcode" style="text-align: right;margin-right: 0;">
            <img class="weui_media_appmsg_thumb" src="/public/images/abc.png"  style="width:36px;vertical-align: middle;" alt="">
          </div>
		   <div class="weui_panel_ft" style ="border:none;padding-left:24px;">
            
          </div>
        </a>
     
      </div>
       
    </div>
	<div class="weui-row weui-no-gutter weui_margin_fix" style="position:relative">
      <div class="weui-col-50" onclick="window.location.href='/balance/income.html'" style="border-right:1px solid #e8e8e8;"><p style="font-size:18px;">50.25</p><span>今日收益(元)<span></div>
      <div class="weui-col-50"><p style="font-size:18px;">1000.75</p><span>帐户余额(元)<span></div>
    </div>
	
	<div class="weui-grids weui_margin_fix">
 
		  <a href="javascript:;" class="weui_grid js_grid">
			<div class="weui_grid_icon">
			  <img src="/public/images/credit.png" alt="">
			  <span class="weui-badge" style="position: absolute;top: -.4em;right: -.4em;">2</span>
			</div>
			<p class="weui_grid_label">
			  分享记录
			</p>
		  </a>
		  <a href="javascript:;" class="weui_grid js_grid">
			<div class="weui_grid_icon">
			  <img src="/public/images/order.png" alt="">
			  <span class="weui-badge" style="position: absolute;top: -.4em;right: -.4em;">8</span>
			</div>
			<p class="weui_grid_label">
			  收益记录
			</p>
		  </a>
		  <a href="javascript:;" class="weui_grid js_grid">
			<div class="weui_grid_icon">
			  <img src="/public/images/message.png" alt="">
			</div>
			<p class="weui_grid_label">
			  下线列表
			</p>
		  </a>
		 
    </div>
	

	<div class="weui_cells weui_cells_access">

        <a class="weui_cell" href="javascript:;">
          <div class="weui_cell_hd"><img src="/public/images/avater.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>安逸积分</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix">3000</div>
        </a>
        <a class="weui_cell" href="javascript:;">
          <div class="weui_cell_hd"><img src="/public/images/icon_nav_toast.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的钱包</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix">&nbsp;</div>
        </a>
    </div>
	
	<div class="weui_cells weui_cells_access">
		<a class="weui_cell" href="javascript:;">
		<div class="weui_cell_hd"><img src="/public/images/icon_nav_button.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>帐户设置</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
        <a class="weui_cell" href="javascript:;">
		<div class="weui_cell_hd"><img src="/public/images/icon_nav_button.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>地址设置</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
        
    </div>
	
	<style>
	.weui_dialog, .weui_toast {
		top:10%;
	}
	
	.weui_panel:before, .weui_panel:after {
		border: none;  
	}
	.weui_dialog_bd{
	font-size:0;
	}
 
	</style>	
	 
	 <script>
	 
		 $(document).on("click", "#qrcode", function() {
		  
			$.alert('<img src="http://m.anyitime.com/public/images/avater.png" style="position:relative;width:100%;" />', '扫描二维码<p>关注安逸时光网微信公众号', '');
			return false;
		  });
	 
     
	 </script>
