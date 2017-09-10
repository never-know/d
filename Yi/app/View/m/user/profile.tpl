	<div class="weui_return_wrapper  white_back" >
		<div class="weui_cells weui_cells_access weui_return"  >
			<a class="weui_cell black_return" onclick="<?=((get_device_type() == 'ios')? '/user.html' : 'history.go(-1);')?>" >
				<span class="weui_cell_ft" ></span>返回
			</a>
			 
		</div>
		
	</div>
	<div class="weui_panel" style="margin-top: 64px;">
   
      <div class="weui_panel_bd" style="background:#fff;">
        <a id="avater_wrapper"  href="/user/avater.html" class="weui_media_box weui_media_appmsg" style="padding:8px 15px;">
			<h4 class="weui_media_title">头像</h4><div class="weui_media_bd"></div><div class="weui_panel_ft" style="padding:0;"><img id="avater" class="weui_media_appmsg_thumb" src="<?=$result['headimgurl'];?>" alt="" style="width:50px;" onerror="imgnotfound()">
		   </div>
        </a>
     
      </div>
       
    </div>
	 
 
	<div class="weui_cells">

        <div class="weui_cell" onclick="window.location.href='/user/nickname.html'" id="show-nickname">
          <div class="weui_cell_bd weui_cell_primary" style="min-width: 100px;">
            <p>昵称</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix" id="nick" ><?=$result['nickname']?></div>
        </div>
        <div class="weui_cell" href="javascript:;">
          <div class="weui_cell_bd weui_cell_primary">
            <p>手机号码</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix"><?=session_get('user')['phone'];?></div>
        </div>
    </div>
	
	<div class="weui_cells">
		<div class="weui_cell"  id="qrcode" onclick="window.location.hash='#qrcode'">
          <div class="weui_cell_bd weui_cell_primary">
            <p>二维码名片</p>
          </div>
          <div class="weui_cell_ft" style="height: 24px;">
		  <img src="/public/images/abc.png" alt="" width="24">
          </div>
        </div>
    </div>
	
	<div class="weui_cells weui_cells_access">

        
        <div class="weui_cell" onclick="window.location.href='/draw/account.html'">
          <div class="weui_cell_bd weui_cell_primary" id="balance_account">
            <p>提现帐户设置 ( 支付宝 )</p>
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
	#weui-prompt-input{
		    font-size: 16px;
    padding-left: 10px;
	}
	 
	.white_back #save_button{
		display:none;
	}
	.white_back .weui_return, .black_back .black_return {
		flex=1;
		-webkit-box-flex: 1;
		-webkit-flex: 1;
	}
	
	</style>
 
	<div id="crop_container" class="weui-popup-container">
	  <div class="weui-popup-overlay"></div>
      <div class="weui-popup-modal" >
    <div id="wrapper">
      <img id="image" src="https://m.anyitime.com/public/images/1.jpg" alt="Picture">
    </div>
	</div>
	</div>
	
	<script type="text/javascript" src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> 
	<script src="/public/js/m/select.js"></script>	
 
	<script type="text/javascript" src="/public/js/m/cropper.js"></script>	
	<link rel="stylesheet" href="/public/css/cropper.css">	
	<script>
	
	var hash  = window.location.hash || '';
	var cropper = null;
	window.onhashchange = function(){
		var new_hash = window.location.hash || ''; 	//substring(1)用来减去地址栏的地址中的#号
		 
		if (hash == '#qrcode' && new_hash == '') {
				$.closeModal();
		}
		hash = new_hash;
	}
	 
	</script>
	 <script>
		 $(document).on("click", "#qrcode", function() {
			$.modal({
			  title: '扫描二维码<p>关注 <span style="color:red;">安逸时光网</span> 微信公众号',
			  text: '<img src="https://m.anyitime.com/public/images/qrcode.png" style="position:relative;width:100%;" />',
			  buttons: [
				{ text: "关闭", className: "primary", onClick: function(){history.go(-1);} },
			  ],
			 
			});

		return false;
	  });
	 </script>

