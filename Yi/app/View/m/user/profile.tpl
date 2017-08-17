
	<div class="weui_panel" style="margin-top: 64px;">
   
      <div class="weui_panel_bd" style="background:#fff;">
        <a id="avater_wrapper" href="javascript:void(0);" class="weui_media_box weui_media_appmsg" style="padding:8px 15px;">
			
			<h4 class="weui_media_title">头像</h4>
        　	 <div class="weui_media_bd">
				&nbsp;
		     </div>
		  　
		   <div class="weui_panel_ft" style="padding:0;">
		    <img id="avater" class="weui_media_appmsg_thumb" src="<?=$result['headimgurl'];?>" alt="" style="width:50px;" onerror="imgnotfound()">
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
	<style>
    #crop_container {
      max-width: 640px;
      padding: 20px auto;
	  background:#000;
    }

    .crop_container img {
      max-width: 100%;
    }
  </style>
	
	<div id="crop_container" class="weui-popup-container">
    <div>
      <img id="image" src="https://m.anyitime.com/public/images/1.jpg" alt="Picture">
    </div>
	</div>
	
	<script type="text/javascript" src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script> 
	<script src="/public/js/m/select.js"></script>	
 
	<script type="text/javascript" src="/public/js/m/cropper.js"></script>	
	<link rel="stylesheet" href="/public/css/cropper.css">	
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
	  
	   $(document).on("click", "#avater_wrapper", function() {
	   alert('click');
	   
	   wx.checkJsApi({
		jsApiList: ["chooseImage", "uploadImage","getLocalImgData"], // 需要检测的JS接口列表，所有JS接口列表见附录2,
		success: function(res) {
			console.log('1111');
			console.log(res);
			 
			if (window.__wxjs_is_wkwebview) {
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
											//alert(res4.localData);
												localData = res4.localData; // localData是图片的base64数据，可以用img标签显示
												//$('avater').attr('src', res4.localData);
											}
										});
									} else {
										
										localData =  localIds[0];
									}
									
									  $('#avater').attr("src", localIds[0]);
									  $('#image').attr("src", localData);
									  
									
									$("#crop_container").popup();
									
									
									 
									 
									     var image = document.querySelector('#image');
										  var cropper = new Cropper(image, {
											dragMode: 'move',
											aspectRatio: 1 / 1,
											autoCropArea: 0.8,
											restore: false,
											guides: false,
											center: false,
											highlight: false,
											cropBoxMovable: false,
											cropBoxResizable: false,
											toggleDragModeOnDblclick: false,
											checkOrientation:false,
											background:false,
											 rotatable:false,
											 zoomOnWheel:false,
											
										  });
									
									
									$.ajax({
										url:'/user/avater.html', 
										type:'POST', 
										data: {serverid: res3.serverId, csrf_token:"<?=get_token('m_user_avater')?>"},
										success: function(data){
											if (data.statusCode == 1 ) {
												$('avater').attr('src', data.body.headimgurl);
											} else {
												// $.toast(data.message, "cancel");
												// $('#formSubmitBtn').attr("sindex", 0);
												if (ios == true) {
													wx.getLocalImgData({
														localId: localIds[0], // 图片的localID
														success: function (res4) {
															//localData = res4.localData; // localData是图片的base64数据，可以用img标签显示
															//$('avater').attr('src', res4.localData);
														}
													});
												} else {
													$('avater').attr('src', localIds[0]);
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

