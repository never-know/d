
	<div class="weui_panel weui_panel_access" style="margin-top: 10px;">
   
      <div class="weui_panel_bd">
        <a href="/user/profile.html" class="weui_media_box weui_media_appmsg" style="padding: 12px 15px 12px 22px;">
          <div class="weui_media_hd">
            <img class="weui_media_appmsg_thumb" src="<?=$result['headimgurl'];?>" alt="">
          </div>
          <div class="weui_media_bd" style="margin-left:.5em;margin-top:4px;letter-spacing: 0px;">
            <h4 class="weui_media_title">西湖区转塘街道西湖区转塘街道转塘街道转塘街道</h4>
            <p class="weui_media_desc" style="font-size:15px;"><?=session_get('user_phone')?></p>
          </div>
		   <div class="weui_media_hd" id="qrcode" style="text-align: right;margin-right: 0;">
            <img class="weui_media_appmsg_thumb" src="/public/images/abc.png"  style="width:36px;vertical-align: middle;" alt="">
          </div>
		   <div class="weui_panel_ft" style ="border:none;padding-left:24px;">
            
          </div>
        </a>
     
      </div>
       
    </div>
	<div class="weui-row weui-no-gutter" style="position:relative;background:#fff;">
      <a class="weui-col-50 col-50-first" href="/balance/daily/today.html">
		<div>
			<p><?=$result['today_salary']/100;?></p>
			<span>今日收益(元)</span>
		</div>
		</a>
		
      <a class="weui-col-50" href="/balance.html">
	  <div>
		<p><?=$result['account_balance']/100;?></p>
		<span>帐户余额(元)</span>
	</div>
	  </a>
    </div>
	 
	<div class="weui-grids weui_margin_fix"  style="margin-top: 1.17647059em; display:none;" >
 
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

        <a class="weui_cell" href="/balance/income.html">
          <div class="weui_cell_hd"><img src="/public/images/credit.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的收益</p>
          </div>
          <div class="weui_cell_ft"><?php if($result['today_salary']>0){echo ($result['today_salary']/100);} ?></div>
        </a>
		<a class="weui_cell" href="/balance.html">
          <div class="weui_cell_hd"><img src="/public/images/message.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的钱包</p>
          </div>
          <div class="weui_cell_ft">&nbsp;</div>
        </a>
		 <a class="weui_cell" href="/my/share.html">
          <div class="weui_cell_hd"><img src="/public/images/credit.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的分享</p>
          </div>
          <div class="weui_cell_ft">&nbsp;</div>
        </a>
	</div>
	<div class="weui_cells weui_cells_access">
       
		<a class="weui_cell" href="/my/team.html">
		<div class="weui_cell_hd"><img src="/public/images/order.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的会员</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
		<a class="weui_cell" href="/my/message.html">
		<div class="weui_cell_hd"><img src="/public/images/message.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的消息</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
        <a class="weui_cell" href="/user/profile.html">
		<div class="weui_cell_hd"><img src="/public/images/credit.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>帐户设置</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
        
    </div>
 
	<style>
	 
	.weui_dialog, .weui_toast{
		top:10%;
	}
	.weui_dialog_bd{
		font-size:0;
	}
	a.weui-col-50:active{
		background-color:#e8e8e8;
	}
	.weui_panel:before  {
	 border:none; 
	}
	</style>	
	 
	 <script>
		 $(document).on("click", "#qrcode", function() {
		  
			//$.alert('<img src="http://m.anyitime.com/public/images/avater.png" style="position:relative;width:100%;" />', '扫描二维码<p>关注安逸时光网微信公众号', '关闭');
			
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
