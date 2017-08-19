	<div class="weui_return_wrapper  white_back" >
		<div class="weui_cells weui_cells_access weui_return"  >
			<a class="weui_cell black_return" onclick="history.go(-1);" >
				<span class="weui_cell_ft" ></span>返回
			</a>
			<div id ="save_button">
			<a href="javascript:;" class="weui_btn weui_btn_primary" style="  width: 70px;height: 30px; font-size: 13px; line-height: 30px; margin-top: 8px;  margin-right: 20px; padding-top: 1px;">保存</a>
			</div>
		 
		</div>
		
	</div>
	<div class="weui_panel" style="margin-top: 64px;">
   
      <div class="weui_panel_bd" style="background:#fff;">
        <a id="avater_wrapper"  href="#abc" class="weui_media_box weui_media_appmsg" style="padding:8px 15px;">
			
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
		<a class="weui_cell" href="#qrcode" id="qrcode">
          <div class="weui_cell_bd weui_cell_primary">
            <p>二维码名片</p>
          </div>
          <div class="weui_cell_ft" style="height: 24px;">
		  <img src="/public/images/abc.png" alt="" width="24">
          </div>
        </a>
    </div>
	
	<div class="weui_cells weui_cells_access">

        
        <div class="weui_cell" onclick="window.location.href='/draw/account.html'">
          <div class="weui_cell_bd weui_cell_primary" id="balance_account">
            <p>提现帐户设置（支付宝）</p>
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
	.white_back #save_button{
		display:none;
	}
	.white_back .weui_return, .black_back .black_return {
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
	
	var hash  = window.location.hash || '';
	var cropper = null;
	window.onhashchange = function(){
		var new_hash = window.location.hash || ''; 	//substring(1)用来减去地址栏的地址中的#号
		if (hash == '#abc' && new_hash == '') {
			$('.weui-popup-modal').removeClass('blackground');
			$('.weui_return_wrapper').removeClass('black_back').addClass('white_back');$.closePopup();
			 
		}
		if (hash == '#qrcode' && new_hash == '') {
				$.closeModal();
		}
		hash = new_hash;
	}
	
	$('.closemodal').on("click", function(){
		history.go(-1);
	});
 
	wx.config({
			appId: 	"<?=$result['js']['appId']?>",
			timestamp:  <?=$result['js']['timestamp']?>,
			nonceStr: "<?=$result['js']['nonceStr']?>",
			signature: "<?=$result['js']['signature']?>",
			jsApiList: [
				"chooseImage", "uploadImage","getLocalImgData"
			]
		});
	
	
	 $(document).on("click", "#show-nickname2", function() {
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
 
		wx.chooseImage({
			count: 1,  
			sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
			sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			success: function (res2) {
				console.log(res2);
				var localIds = res2.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
				if (window.__wxjs_is_wkwebview) {
					wx.getLocalImgData({
						localId: localIds[0], // 图片的localID
						success: function (res4) {
						var localData = res4.localData; // localData是图片的base64数据，可以用img标签显示
						}
					});
				} else {
					var localData =  localIds[0];
				}
				
				wx.uploadImage({
					localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
					isShowProgressTips: 1, // 默认为1，显示进度提示
					success: function (res3) {
						console.log(res3);
				
						//$('#image').attr("src", 'https://m.anyitime.com/public/images/qrcode.jpg');
						$('#image').attr("src", localData);
						 
						 $('#wrapper').height(document.documentElement.clientHeight);
						 
						$('.weui_return_wrapper').removeClass('white_back').addClass('black_back');
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
								img.csrf_token = "<?=get_token('m_user_avater')?>";
								img.naturalWidth = img2.naturalWidth;
							 
							$.ajax({
								url:'/user/avater.html', 
								type:'POST', 
								data: img,
								success: function(data){
									if (data.statusCode == 1 ) {
										 
										$('#avater').attr('src', data.body.headimgurl + '?v=' + new Date().getTime());
										$.closePopup();
										window.location.href="/user.html";
											 
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
		});
    });
	  
	  
	  
	  
	</script>
	 <script>
		 $(document).on("click", "#qrcode", function() {
			$.modal({
			  title: '扫描二维码<p>关注 <span style="color:red;">安逸时光网</span> 微信公众号',
			  text: '<img src="https://m.anyitime.com/public/images/qrcode.png" style="position:relative;width:100%;" />',
			  buttons: [
				{ text: "关闭", className: "primary closemodal"},
			  ],
			 
			});

		return false;
	  });
	 </script>

