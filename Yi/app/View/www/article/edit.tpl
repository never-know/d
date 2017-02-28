
	<div class="breadcrumb">
		<span style="margin-left:28px;">文案＞</span><label class="subtitle">添加文案</label>
	</div> 
	<form id="article_edit" style="width:700px;overflow:hidden;" onsubmit="return false;">
		<dl>
			<dt>标题：</dt> 
			<dd><input type="text" name="title" maxlength = "30" id="title" class="ar_text_input"　
				value="<?=\check_plain($result['detail']['title']);?>">
			<label>标题6-30个字符</label></dd>
		</dl>
		<dl>
			<dt>描述：</dt> 
			<dd><textarea  rows="2" name="desc" maxlength = "60" id="desc" class="ar_text_input"><?= \check_plain($result['detail']['desc']);?></textarea>
			<label>描述10-60个字符</label></dd>
		</dl>
		<dl>
			<dt>图片：</dt> 
			<dd> 
				<input class="ke-input-text" type="hidden" id="icon" readonly="readonly"  name="icon"
				<?php 
					$icon =  \check_url($result['detail']['icon']);
					echo $icon ;
				?>
				/> 
				<img width="100px"  id="img1"  
					<?php
						if (empty($icon)) { 
							echo ' style="display:none;" ';
						} else {
							echo ' src="'.$icon.'" ';
						}
					?> 
					/>
				<input type="button" id="uploadButton" value="Upload" class="single_upload" />
				<label>图片尺寸400*400</label>
			</dd>
		</dl>
		<dl class="tag">
			<dt>标签：</dt> 
			<dd>
				<?php foreach(\article_tags() as $key => $value) { if($key == 0) continue;  ?>
				<span>  <input type="radio" name="tag" id="ele<?=$key;?>" value="<?=$key;?>" <?php if($key == $result['detail']['tag']) echo 'checked';?>>
						<label for="ele<?=$key;?>" ><?=$value;?></label>
				</span>
				<?php } ?>
			</dd>
		</dl>
		<dl style="overflow:visible; height:40px;">
			<dt>推广范围：</dt> 
			<dd style="overflow:visible;" id="region">
				<input type="hidden" id="region_selected" class="diy_select_input" name="region" value="<?=end($result['params']['region']);?>"/>
				<?php $width = ['323px','242px','242px','242px;left:-112px;']; foreach ($width as $i => $w) { ?>
				<div class="diy_select" >
					<i class="diy_select_txt"><?=(isset($result['params']['region'][$i+1])?($result['region_list'][$result['params']['region'][$i]][$result['params']['region'][$i+1]]['name']):((0==$i)?'全国':'--不限--'));?></i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:<?=$w;?>">
						<?php if (isset($result['params']['region'][$i])) {
							echo '<li sid="0" rid="', $result['params']['region'][$i] ,'">',((0==$i)?'全国':'--不限--'),'</li>';
							foreach($result['region_list'][$result['params']['region'][$i]] as $key => $value) {
								echo '<li sid="', $value['id'], '">', $value['name'],'</li>';
						} } else {
							echo '<li sid="0">--不限--</li>';
						} ?>
					</ul>
				</div>
				<?php } ?>
			</dd>
		</dl>
		
		<dl>
			<dt>开始日期：</dt> 
			<dd>	
			<input type="text" name="date_start" id="date_start" class="tcal" autocomplete="off" readonly="readonly" value="<?php echo $result['detail']['start'];?>"/>
				<label>选择当日则审核通过后立刻生效</label></dd>
		</dl>
		<dl>
			<dt>结束日期：</dt> 
			<dd>
			<input type="text" name="date_end" id = "date_end" class="tcal" autocomplete="off" readonly="readonly" value="<?php echo $result['detail']['end'];?>"/>
				<label>至少大于当前日期2天，不填写则长期有效</label>
			</dd>
		</dl>
		
		<dl>
			<dt>内容：</dt> 
			<dd id="richtext"><textarea id="content" name="content" style="width:422px;height:500px;visibility:hidden;" >
			<?php
				//echo \check_plain($result['content']);
			?>
			</textarea>
			<label>内容10-60000个字符</label>
			</dd>
		</dl>
		<dl>
			<input type="hidden" value="<?=get_token();?>" name="csrf_token" id="csrf_token" />
			<input type="hidden" value="<?=$result['detail']['id'];?>" name="id" />
			<button href="javascript:;" style="width:120px;" type ="submit" class="login-btn" id="article_submit"  sindex="0"  >提交</a></button>
		</dl>

	</form>
	
	
	<link rel="stylesheet" href="/public/kindeditor-4.1.10/themes/default/default.css" />
	<link rel="stylesheet" href="/public/kindeditor-4.1.10/themes/simple/simple.css" />
	<script charset="utf-8" src="/public/kindeditor-4.1.10/kindeditor-min.js"></script>
	<script charset="utf-8" src="/public/kindeditor-4.1.10/lang/zh_CN.js"></script>
	<link rel="stylesheet" type="text/css" href="/public/css/tcal.css" />
	<script type="text/javascript" src="/public/js/tcal.js"></script> 
	<script type="text/javascript" src="/public/js/region.js"></script> 
	
	<script>
		KindEditor.ready(function(K) {
			var token = "<?=get_token('www_upload_index');?>";
			var uploadbutton = K.uploadbutton({
				button : K('#uploadButton')[0],
				width:80,
				fieldName : 'imgFile',
				url : '/upload.html',
				formatUploadUrl: false,
				extraParams:{"csrf_token":token,"isAjax":1},
				afterUpload : function(data) {
					if (data.error === 0) {
						//var url = K.formatUrl(data.url, 'absolute');
						K('#icon').val(data.url);
						_$('img1').src = data.url;
						_$('img1').style.display = 'block';
					} else {
						alert(data.message);
					}
				},
				afterError : function() {
					alert('网络错误 ');
				}
			});
			uploadbutton.fileBox.change(function(e) {
				uploadbutton.submit();
			});
	
			window.editor = K.create('#content', {
				width: 420,
				height:500,
				minWidth:420,
				resizeType:1,
				themeType : 'simple',
				loadStyleMode:false,
				allowFileManager : false,
				allowFlashUpload: false,
				formatUploadUrl: false,
				uploadJson: '/upload.html',
				imageTabIndex:1,
				imageSizeLimit : '1MB',
				fillDescAfterUploadImage:false,
				imageFileTypes : '*.jpg;*.gif;*.png',
				imageUploadLimit : 20,
				filePostName : 'imgFile',
				flashWidth: 480,
				flashHeight: 400,
				extraFileUploadParams : {"csrf_token":token,"isAjax":1, 'size':1},
				items : [
					'fullscreen','source', 'preview','|', 'undo', 'redo', '|', 'selectall' ,
					'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|','lineheight','indent', 'outdent', 'subscript',
					'superscript', '/', 
					'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
					'italic', 'underline', 'strikethrough', 'justifyleft', 'justifycenter', 'justifyright',
					'justifyfull', 'insertorderedlist', 'insertunorderedlist',   '/',
					'image','multiimage','flash','table', 'hr', 'emoticons', 'baidumap', 
					'anchor', 'link', 'unlink', 'template', 'removeformat'
				],
				afterFocus: function(){
					var tar = _$('content');
					Min.dom.next(tar).removeAttribute('style');				
					Min.dom.pre(tar).removeAttribute('style');
				}
			}); 
			window.editor.html("<?=($result['detail']['content']??'');?>");
		});
		
		 
		Min.event.bind('article_submit','click',function(e){
				var has_error = false;
				var article_edit_error = function(id, message){
					var tar = _$(id), next = Min.dom.next(tar);
					next.style.color = error_bordercolor;
					if(message) next.innerHTML = message;
					if(id == 'content') tar = Min.dom.pre(tar);
					tar.style.borderColor = '#ffb4a8';
					has_error = true;
				}
				
				var title = _$('title').value;
				console.log(title);
				console.log(title.length);
				if(!title || title.length<6 || title.length>32)  {
					article_edit_error('title');
					
				}

				var desc = _$('desc').value;
				if(!desc || desc.length <10 || desc.length>64)  {
					article_edit_error('desc');
				}

			
				var date_end = _$('date_end').value;
				var date_start = _$('date_start').value;
				if(date_end) {
					var today = f_tcalGenerateDate(new Date(),'Ymd');
					var end = date_end.replace(/-/g, '');
					
					var start = date_start.replace(/-/g, '');
					
					var compare = start || today;
					compare = Math.max(compare, today);

					if( end -2 < compare ) {
						article_edit_error('date_end');
						 
					}
				}
				
				var content = editor.html();
				if(!content || content.length<10 || content.length>60000)  {
					article_edit_error('content');
					 
				}
				if(has_error){
					this.setAttribute("sindex", 0);
					return false;
				}
				
				var sindex	= this.getAttribute('sindex');
			
				if(sindex == 0) {
			
					this.setAttribute("sindex", 1);
					
					minAjax({
						url:'http://www.' + site_domain + '/article/edit.html', 
						type:'POST', 
						data:{
							title:title,
							desc:desc,
							icon:_$('icon').value,
							date_start:date_start,
							date_end:date_end,
							content:content,
							region:_$('region_selected').value,
							csrf_token:_$('csrf_token').value
						},
						success: function(data){
							if(data.statusCode == 0) {
								//window.location.href = '/article/list.html'
							}else{
								_$('article_submit').setAttribute("sindex", 0);
							}
						},
						fail: function(){
							_$('article_submit').setAttribute("sindex", 0);
						}
					});
				}
		});
		
		var article_edit_init = function(e){
				var tar = e.currentTarget;
				Min.dom.next(tar).removeAttribute('style');				
				tar.removeAttribute('style');	
		};
		
		Min.event.bind('title','focus',article_edit_init);
		Min.event.bind('desc','focus',article_edit_init);
		Min.event.bind('date_start','focus',article_edit_init);
		Min.event.bind('date_end','focus',article_edit_init);
		
	</script>
	