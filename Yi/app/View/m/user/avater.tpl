	<div class="weui_return_wrapper  black_back" >
		<div class="weui_cells weui_cells_access weui_return"  >
			<a class="weui_cell black_return" onclick="history.go(-1);" >
				<span class="weui_cell_ft" ></span>返回
			</a>
			<div id ="save_button" style="display:none">
			<a href="javascript:;" class="weui_btn weui_btn_primary" style="  width: 70px;height: 30px; font-size: 13px; line-height: 30px; margin-top: 8px;  margin-right: 20px; padding-top: 1px;">保存</a>
			</div>
		 
		</div>
		
	</div>
	 
	<div id="loading">
	
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
	.black_back{
	 
		display:block;
	}
	.black_back .weui_return{
 
		display:flex;
	}
	.black_back #save_button{
		display:block;
		flex=1;
		-webkit-box-flex: 1;
		-webkit-flex: 1;
	}
	 
	</style>
	<style>
    #crop_container {
      max-width: 100%;
      padding: 20px auto;
	   
    }

    .crop_container img {
      max-width: 100%;
    }
	.blackground{
	 background:#000;
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
	(function(){           var isPageHide = false;           window.addEventListener('pageshow', function(){               if(isPageHide) {                   window.location.reload();               }           });           window.addEventListener('pagehide', function(){               isPageHide = true;           });       })();
	
	$.showLoading();
	var cropper = null;
	wx.config({
			appId: 	"<?=$result['js']['appId']?>",
			timestamp:  <?=$result['js']['timestamp']?>,
			nonceStr: "<?=$result['js']['nonceStr']?>",
			signature: "<?=$result['js']['signature']?>",
			jsApiList: [
				"chooseImage", "uploadImage","getLocalImgData"
			],
			  
		});
	
	
	 
	wx.ready(function(){
		$.hideLoading();
		$('#save_button').show();
		wx.chooseImage({
			count: 1,  
			sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
			sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			success: function (res2) {
				alert('456');
				var localIds = res2.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
				if (window.__wxjs_is_wkwebview) {
					wx.getLocalImgData({
						localId: localIds[0], // 图片的localID
						success: function (res4) {
						var localData = res4.localData; // localData是图片的base64数据，可以用img标签显示
						uploade_avatar(localIds, localData)
						}
					});
				} else {
					var localData =  localIds[0];
					uploade_avatar(localIds, localData);
					
				}
				return;
			
			}		 
		});
    });
	  
	function uploade_avatar(localIds, localData)
	{
			wx.uploadImage({
					localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
					isShowProgressTips: 1, // 默认为1，显示进度提示
					success: function (res3) {
						
						//$('#image').attr("src", 'https://m.anyitime.com/public/images/qrcode.jpg');
						$('#image').attr("src", localData);
						 
						 $('#wrapper').height(document.documentElement.clientHeight);
						 
						//$('.weui_return_wrapper').removeClass('white_back').addClass('black_back');
						$('.weui-popup-modal').addClass('blackground');
						$("#crop_container").popup();
						
						var image = document.querySelector('#image');
						if (cropper && cropper.destroy) {
							cropper.destroy();
						}
				
						cropper = new Cropper(image, {
							dragMode: 'move',
							viewMode:1,
							aspectRatio: 1 / 1,
							autoCropArea: 1,
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
							zoomOnWheel:true,
							minContainerHeight:400
						});

						$('#save_button').on('click', function(){

							var img = cropper.getData(), img2 = cropper.getImageData();
								img.media_id = res3.serverId;
								img.csrf_token = "<?=get_token('m_user_avatar')?>";
								img.naturalWidth = img2.naturalWidth;
							 
							$.ajax({
								url:'/user/avatar.html', 
								type:'POST', 
								data: img,
								success: function(data){
									if (data.statusCode == 1 ) {
										 
										//$('#avatar').attr('src', data.body.headimgurl + '?v=' + new Date().getTime());
										//$.closePopup();
										history.replaceState(null, "用户主页", "https://m.anyitime.com/user.html?v=" + new Date().getTime());
										window.location.href="/user/profile.html";
										//window.location.href="/user.html";
										//history.go(-1);
											 
									} else {
										$.toast(data.message, "cancel");
									}
									$('#save_button').attr("sindex", 0);
								},
								error:function(){
									 $.toast("网络连接失败", "cancel");
									 $('#save_button').attr("sindex", 0);
								}
							});
						});
					}
				});

	}  
	</script>
	 