
	<div class="weui_panel" style="margin-top: 64px;">
   
      <div class="weui_panel_bd" style="background:#fff;">
        <a id="avatar_wrapper" href="javascript:void(0);" class="weui_media_box weui_media_appmsg" style="padding:8px 15px;">
			
			<h4 class="weui_media_title">头像</h4>
        　	 <div class="weui_media_bd">
				&nbsp;
		     </div>
		  　
		   <div class="weui_panel_ft" style="padding:0;">
		    <img id="avatar" class="weui_media_appmsg_thumb" src="<?=$result['headimgurl'];?>" alt="" style="width:50px;" onerror="imgnotfound()">
		   </div>
        </a>
     
      </div>
       
    </div>
	 
 
	<div class="weui_cells">

        <div class="weui_cell" href="javascript:;" id="show-nickname">
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
	#weui-prompt-input{
		    font-size: 16px;
    padding-left: 10px;
	}
	</style>
	
	
	<style type="text/css">
		#rRightDown,#rLeftDown,#rLeftUp,#rRightUp,#rRight,#rLeft,#rUp,#rDown{
			position:absolute;
			background:#FFF;
			border: 1px solid #333;
			width: 6px;
			height: 6px;
			z-index:500;
			font-size:0;
			opacity: 0.5;
			filter:alpha(opacity=50);
		}

		#rLeftDown,#rRightUp{cursor:ne-resize;}
		#rRightDown,#rLeftUp{cursor:nw-resize;}
		#rRight,#rLeft{cursor:e-resize;}
		#rUp,#rDown{cursor:n-resize;}

		#rLeftDown{left:-4px;bottom:-4px;}
		#rRightUp{right:-4px;top:-4px;}
		#rRightDown{right:-4px;bottom:-4px;background-color:#00F;}
		#rLeftUp{left:-4px;top:-4px;}
		#rRight{right:-4px;top:50%;margin-top:-4px;}
		#rLeft{left:-4px;top:50%;margin-top:-4px;}
		#rUp{top:-4px;left:50%;margin-left:-4px;}
		#rDown{bottom:-4px;left:50%;margin-left:-4px;}

		#bgDiv{width:150px; height:200px; border:1px solid #666666; position:relative;}
		#dragDiv{border:1px dashed #fff; width:80%; height:60px; top:0; left:0; cursor:move; }
		#crop{ top:62px;}
	</style>
	
	<div id="crop" class="weui-popup-container">
		<div class="weui-popup-overlay"></div>
		<div class="weui-popup-modal">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
				<div style="position:relative;width:100%;height:100%;background:#000">
				<div id="bgDiv">
					<div id="dragDiv">
					  <div id="rRightDown"> </div>
					  <div id="rLeftDown"> </div>
					  <div id="rRightUp"> </div>
					  <div id="rLeftUp"> </div>
					  <div id="rRight"> </div>
					  <div id="rLeft"> </div>
					  <div id="rUp"> </div>
					  <div id="rDown"></div>
					</div>
				  </div>
				 </div>
				 </td>
				 
			  </tr>
			</table>
		</div>
    </div>
	
	
	
	
	
	<script type="text/javascript" src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> 
	<script src="/public/js/m/select.js"></script>	
	<script type="text/javascript" src="/public/js/m/crop.js"></script>
	<script type="text/javascript" src="/public/js/m/Drag.js"></script>
	<script type="text/javascript" src="/public/js/m/Resize.js"></script>	
	 	
	<script>
	
	var ios = false;
	var localIds = '', localData = '';
	 
	wx.config({
			appId: 	"<?=$result['js']['appId']?>",
			timestamp:  <?=$result['js']['timestamp']?>,
			nonceStr: "<?=$result['js']['nonceStr']?>",
			signature: "<?=$result['js']['signature']?>",
			jsApiList: [
				"chooseImage", "uploadImage","getLocalImgData"
			]
		});
	
	
	 $(document).on("click", "#show-nickname", function() {
        $.prompt("", "设置昵称", function(text) { 
			$('#nick').html(text);
			$.ajax({
				url:'/user/nickname.html', 
				type:'POST', 
				data: {nickname: text, csrf_token:"<?=get_token('m_user_nickname')?>"},
				success: function(data){},
				error:function(){
					$.toast("网络连接失败", "cancel");
					$('#formSubmitBtn').attr("sindex", 0);
				}
			});
		  
        }, function() {
          //取消操作
        },  $('#nick').html());
      });
	  
	   $(document).on("click", "#avatar_wrapper", function() {
	   
	   
	   wx.checkJsApi({
		jsApiList: ["chooseImage", "uploadImage","getLocalImgData"], // 需要检测的JS接口列表，所有JS接口列表见附录2,
		success: function(res) {
			console.log('1111');
			console.log(res);
			
			if (res.checkResult.getLocalImgData == true) {
				ios = true;
			}
			
			if (res.checkResult.chooseImage == true) {
				wx.chooseImage({
					count: 1,  
					sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
					success: function (res2) {
						console.log('localIds');
						console.log(res2);
						localIds = res2.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
						if (res.checkResult.uploadImage == true) {
							wx.uploadImage({
								localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
								isShowProgressTips: 1, // 默认为1，显示进度提示
								success: function (res3) {
									console.log(res3);
									//var serverId = res3.serverId; // 返回图片的服务器端ID
									
									if (ios == true) {
										wx.getLocalImgData({
											localId: localIds[0], // 图片的localID
											success: function (res4) {
												localData = res4.localData; // localData是图片的base64数据，可以用img标签显示
												//$('avatar').attr('src', res4.localData);
											}
										});
									} else {
										localData =  localIds[0];
									}
									
									$("#crop").popup();
									
									var wid = $('#crop').width();
										var left = wid*0.1;
										var total = wid*0.8;
									$('#dragDiv').width(wid*0.8);
									$('#dragDiv').height(wid*0.8);
									//$('#dragDiv').css('top', '120px');
									//$('#dragDiv').css('left', left + 'px');
									$('#bgDiv').css('left', left + 'px');
									//$('#bgDiv').css('top', '62px');
									$('#bgDiv').width(wid*0.8);
									$('#bgDiv').height(($(window).height()-63));
									
								
									var ic = new ImgCropper("bgDiv", "dragDiv", "https://m.anyitime.com/public/images/1.jpg", {
										Width: total , Height: total, MarginTop:120, MarginLeft:left, Color: "#000",
										Resize: true,
										Right: "rRight", Left: "rLeft", Up:	"rUp", Down: "rDown",
										RightDown: "rRightDown", LeftDown: "rLeftDown", RightUp: "rRightUp", LeftUp: "rLeftUp",
										Preview: "viewDiv2", viewWidth: 300, viewHeight: 300, Scale:		true, 
											Ratio:1
									})
									
									
									
									$.ajax({
										url:'/user/avatar.html', 
										type:'POST', 
										data: {serverid: res3.serverId, csrf_token:"<?=get_token('m_user_avatar')?>"},
										success: function(data){
											if (data.statusCode == 1 ) {
												$('avatar').attr('src', data.body.headimgurl);
											} else {
												// $.toast(data.message, "cancel");
												// $('#formSubmitBtn').attr("sindex", 0);
												if (ios == true) {
													wx.getLocalImgData({
														localId: localIds[0], // 图片的localID
														success: function (res4) {
															//localData = res4.localData; // localData是图片的base64数据，可以用img标签显示
															//$('avatar').attr('src', res4.localData);
														}
													});
												} else {
													$('avatar').attr('src', localIds[0]);
												}
											}
										},
										error:function(){
											 $.toast("网络连接失败", "cancel");
											 $('#formSubmitBtn').attr("sindex", 0);
										}
									});
									
									
								}
							});	
						}
					}
				});
			
			}
			
			 
			
			// 以键值对的形式返回，可用的api值true，不可用为false
			// 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
		}
	});
	
	
       
      });
	  
	  
	  
	  
	</script>
	 <script>
		 $(document).on("click", "#qrcode", function() {
			$.modal({
			  title: '扫描二维码<p>关注 <span style="color:red;">安逸时光网</span> 微信公众号',
			  text: '<img src="https://m.anyitime.com/public/images/qrcode.png" style="position:relative;width:100%;" />',
			  buttons: [
				{ text: "关闭", className: "primary"},
			  ],
			 
			});
		
		
		return false;
	  });
	 </script>

