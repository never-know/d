<style>

	#article_edit .tag span {
	float:left;
	display:block;
	margin-right:20px;
	
	}
#article_edit .tag span input, #article_edit .tag span label{
    float: left;
    display: block;
    line-height: 32px;
    height: 32px;
    margin-left: 4px;
    *margin-left: 2px;
}

	
.diy_select{height:32px;line-height:32px;width:130px;position:relative;font-size:12px;margin-left: -1px;;background:#fff;color:#000;float:left;}
.diy_select_btn,.diy_select_txt{float:left;height:100%;}
.diy_select,.diy_select_list{border:1px solid #CFCFCF;}
.diy_select_txt{width:100px;}
.diy_select_txt,.diy_select_list li{text-indent:10px;overflow:hidden;cursor:pointer;}
.diy_select_txt,.diy_select_list {white-space: nowrap;zoom: 1;color: #005aa0;}
.diy_select_btn{width:28px;background:url('/public/images/rec.gif') no-repeat center}
.diy_select_list{position:absolute;top:34px;left:-1px;z-index:88888;border-top:none;width:100%;display:none;_top:35px;background:white;}
.diy_select_list li{list-style:none;height:28px;line-height:28px;;_background:#fff;float:left;width:130px;background:white}
.diy_select_list li.focus{background:#3399FF;color:#fff}
.diy_select_list_loading {
background-image:url('/public/images/loading.gif');
background-color:#fff;
background-repeat:no-repeat;
background-position:center;
height:80px;
}
	</style>
	
	<div class="breadcrumb">
		<span style="margin-left:28px;">文案＞</span><label class="subtitle">添加文案</label>
	</div> 
	<form id="article_edit" style="width:700px;overflow:hidden;" onsubmit="return false;">
		<dl>
			<dt>标题：</dt> 
			<dd><input type="text" name="title" maxlength = "30" id="title" class="ar_text_input" 
				value="<?php 
					echo (empty($result['t'])? '' : \check_plain($result['t']));
				?>">
			<label>标题6-30个字符</label></dd>
		</dl>
		<dl>
			<dt>描述：</dt> 
			<dd><textarea  rows="2" name="desc" maxlength = "60" id="desc" class="ar_text_input"><?php
				echo (empty($result['desc']) ? '' : \check_plain($result['desc']));
			?></textarea>
			<label>描述10-60个字符</label></dd>
		</dl>
		<dl>
			<dt>图片：</dt> 
			<dd> 
				<input class="ke-input-text" type="hidden" id="icon" readonly="readonly"  name="icon"
				<?php 
					$icon = \check_url($result['icon']);
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
				<?php foreach(article_tags() as $key => $value) : ?>
				<span>  <input type="radio" name="tag" id="ele<?=$key;?>" value="<?=$key;?>" <?php if($key == $result['tag']) echo 'checked="true"';?>>
						<label for="ele<?=$key;?>" ><?=$value;?></label>
				</span>
				<?php endforeach ?>
			</dd>
		</dl>
		<dl style="overflow:visible; height:40px;">
			<dt>推广范围：</dt> 
			<dd style="overflow:visible;" id="region">
				<input type="hidden" id="region_selected" class="diy_select_input" value="0"/>
				<div class="diy_select" >
					<!--<input type="hidden" name="province" class="diy_select_input"/>-->
					<i class="diy_select_txt">全国</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:392px;">
						<li sid="0" rid="0">全国</li>
					</ul>
				</div>

				<div class="diy_select" >
					<!--<input type="hidden" name="city" class="diy_select_input"/>-->
					<i class="diy_select_txt">--不限--</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:261px;">	
						<li sid="0">--不限--</li>
					</ul>
				</div>
			
				<div class="diy_select">
					<!--<input type="hidden" name="" class="diy_select_input"/>-->
					<i class="diy_select_txt">--不限--</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:261px;">	 
					<li sid="0">--不限--</li>
					</ul>
				</div>
				<div class="diy_select">
					<!--<input type="hidden" name="" class="diy_select_input"/>-->
					<i class="diy_select_txt">--不限--</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" >
					<li sid="0">--不限--</li>
					</ul>
				</div>
			</dd>
		</dl>
		
		<dl>
			<dt>开始日期：</dt> 
			<dd>	
			<input type="text" name="date_start" id="date_start" class="tcal" autocomplete="off" readonly="readonly" value="<?php echo $result['start'];?>"/>
				<label>选择当日则审核通过后立刻生效</label></dd>
		</dl>
		<dl>
			<dt>结束日期：</dt> 
			<dd>
			<input type="text" name="date_end" id = "date_end" class="tcal" autocomplete="off" readonly="readonly" value="<?php echo $result['end'];?>"/>
				<label>至少大于当前日期2天，不填写则长期有效</label>
			</dd>
		</dl>
		
		<dl>
			<dt>内容：</dt> 
			<dd id="richtext"><textarea id="content" name="content" style="width:422px;height:500px;visibility:hidden;" >
			<?php
				echo \check_plain($result['content']);
			?>
			</textarea>
			<label>内容10-60000个字符</label>
			</dd>
		</dl>
		<dl>
			<input type="hidden" value="<?=get_token();?>" name="csrf_token" id="csrf_token" />
			<button href="javascript:;" style="width:120px;" type ="submit" class="login-btn" id="article_submit"  sindex="0"  >提交</a></button>
		</dl>

	</form>
	
	
	<link rel="stylesheet" href="/public/kindeditor-4.1.10/themes/default/default.css" />
	<link rel="stylesheet" href="/public/kindeditor-4.1.10/themes/simple/simple.css" />
	<script charset="utf-8" src="/public/kindeditor-4.1.10/kindeditor.js"></script>
	<script charset="utf-8" src="/public/kindeditor-4.1.10/lang/zh_CN.js"></script>
	<link rel="stylesheet" type="text/css" href="/public/css/tcal.css" />
	<script type="text/javascript" src="/public/js/tcal.js"></script> 
	
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
				formatUploadUrl: false,
				uploadJson: '/upload.html',
				imageTabIndex:1,
				imageSizeLimit : '1MB',
				fillDescAfterUploadImage:false,
				imageFileTypes : '*.jpg;*.gif;*.png',
				imageUploadLimit : 20,
				filePostName : 'imgFile',
				extraFileUploadParams : {"csrf_token":token,"isAjax":1, 'size':1},
				items : [
					'fullscreen','source', 'preview','|', 'undo', 'redo', '|', 'selectall' ,
					'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|','lineheight','indent', 'outdent', 'subscript',
					'superscript', '/', 
					'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
					'italic', 'underline', 'strikethrough', 'justifyleft', 'justifycenter', 'justifyright',
					'justifyfull', 'insertorderedlist', 'insertunorderedlist',   '/',
					'image','multiimage','flash','media','table', 'hr', 'emoticons', 'baidumap', 
					'anchor', 'link', 'unlink', 'template', 'removeformat'
				],
				afterFocus: function(){
					var tar = _$('content');
					Min.dom.next(tar).removeAttribute('style');				
					Min.dom.pre(tar).removeAttribute('style');
				}
			}); 
			window.editor.html("<?php echo $result['content'];?>");
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
							
							if(data.statusCode == 1) {
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
	
	
	
<script type="text/javascript">
var province = [];
var region = [];

function diy_select(){
	this.init.apply(this,arguments)
};
diy_select.prototype={

	 init:function(opt) {
	 
		this.l=document.getElementById(opt.TTid).getElementsByTagName('ul');//容器
		this.lengths=this.l.length;
		var THAT = this;
		// 省初始化
		Min.css.addClass('diy_select_list_loading', THAT.l[0]);
		JSONP.get( 'http://www.' + site_domain + '/region/id/0.html', {}, function(data){
			Min.css.removeClass('diy_select_list_loading', THAT.l[0]);
			if(data.statusCode == 1 ){
				for(var i=0; i<data.body[0].length; i++) {
				 var li = document.createElement("li");
	   		 li.setAttribute("sid", data.body[0][i].id);
			   li.innerHTML = data.body[0][i].name;
			   THAT.l[0].appendChild(li);
				}
			}
		 }); 

		for (var i=0; i<this.lengths; i++) {
			this.l[i].index=i;
			this.l[i].style.display ='none';
		}
		Min.event.bind(opt.TTid,'mouseover',{handler:function(e){
			Min.css.addClass(THAT.TTFcous, e.delegateTarget);
		},selector:'li'});
		
		Min.event.bind(opt.TTid,'mouseout',{handler:function(e){
			Min.css.removeClass(THAT.TTFcous, e.delegateTarget);
		},selector:'li'});
		
		Min.event.bind(opt.TTid,'click',{handler:function(e){
		
			var index=this.parentNode.index;//获得列表
			var key = this.getAttribute("sid");
			var p = this.parentNode.parentNode;
			//var origin = p.getElementsByTagName('input')[0].value;
			var origin = _$('region_selected').value;
			if (origin == key) return;
			
			if (key == 0) {
				 rkey = this.getAttribute("rid");
				if(rkey === null || rkey === undefined) return;
				_$('region_selected').value = rkey;
			} else {
				_$('region_selected').value = key;
			}
			
			
			
			p.getElementsByTagName('i')[0].innerHTML =this.innerHTML.replace(/^\s+/,'').replace(/\s+&/,'');
			this.parentNode.style.display='none';
			
			Min.obj.each(THAT.l,function(a,k){
				if(k > index){
					a.innerHTML = '';
					var li = document.createElement("li");
	   			 li.setAttribute("sid", 0);
					  var i = Min.dom.pre(a,'I');
					  //ipt = Min.dom.pre(i);
					  //ipt.value = 0;
					  li.innerHTML = i.innerHTML = '--不限--';
					  a.appendChild(li);  
				}
			});
			
			THAT.l[index].style.display ='none';
			
			if(key > 0 && index < THAT.lengths-1 ){
				if(!region[key]){
					Min.css.addClass('diy_select_list_loading', THAT.l[index+1]);
					JSONP.get( 'http://www.' + site_domain + '/region/id/'+key+'.html', {}, function(data){
						if(data.statusCode == 1 ){
							region[key]= data.body[key];
							Min.css.removeClass('diy_select_list_loading', THAT.l[index+1]);
							THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key)
							if(key > 0 &&  index < THAT.lengths-1 ){
				
								for(var i=0; i < region[key].length; i++){
								 var li = document.createElement("li");
					   		 li.setAttribute("sid", region[key][i].id);
							   li.innerHTML = region[key][i].name;
								  THAT.l[index+1].appendChild(li);
								}	
							}
						}
					}); 
					
				} else {
					THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key);
				for(var i=0; i < region[key].length; i++){
				 var li = document.createElement("li");
	   		 li.setAttribute("sid", region[key][i].id);
			   li.innerHTML = region[key][i].name;
				  THAT.l[index+1].appendChild(li);
				
				}
				}
				console.log('aaa');
				console.log(THAT.l[index+1]);
				THAT.l[index+1].style.display ='block';
				
			} else {
				if(_$('search_form')) _$('search_form').submit(); 
			}
			
			
			Min.event.stopPropagation(e); 
			return;
			if(key > 0 && index < THAT.lengths-1 ){
				THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key);
				for(var i=0; i < region[key].length; i++){
				 var li = document.createElement("li");
	   		 li.setAttribute("sid", region[key][i].id);
			   li.innerHTML = region[key][i].name;
				  THAT.l[index+1].appendChild(li);
				}	
			}
			 
		},selector:'li'});
		
		Min.event.bind(document,'click',function() {
			Min.obj.each(THAT.l,function(a){
				a.style.display='none';
			});
		});
		Min.event.bind(opt.TTid,'click',{handler:function(e){
			 
			var next = Min.dom.next(this);
			if(next.tagName.toUpperCase() != 'UL'){
				next = Min.dom.next(next);
			}
			next.style.display =  next.style.display == 'none'? 'block':'none';
			var index = next.index;
			
			Min.obj.each(THAT.l,function(a,key){
				if(key != index) a.style.display = 'none';
			});

			Min.event.stopPropagation(e); 	
			
		},selector:'i,span'});
	 }

}
new diy_select({ 
	TTid :'region',
	TTFcous:'focus'
});
</script>

<p>
	<span style="font-family:SimHei;">琐琐碎碎</span> 
</p>
<p>
	<span style="font-family:SimHei;">啊啊啊啊</span> 
</p>
<p>
	<span style="font-family:SimHei;"><br />
</span> 
</p>
<p>
	<span style="font-family:SimHei;"><em>啊啊</em>啊啊<u>啊啊</u>啊啊<span style="font-size:14px;">啊啊啊啊<span style="background-color:#E53333;">啊啊啊啊</span>啊啊啊<s>啊啊啊啊</s>啊啊</span></span> 
</p>
<p>
	<span style="font-family:SimHei;"><br />
</span> 
</p>
<p>
	<span style="font-family:SimHei;"><br />
</span> 
</p>
<h1 style="text-align:center;">
	<span style="font-family:SimHei;font-size:18px;color:#CC33E5;"><strong>啊啊啊啊啊啊<a href="http:/www.baidu.com" target="_blank">啊啊啊</a>啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊</strong></span> 
</h1>
<p>
	<br />
</p>
<ul>
	<li>
		<strong>1</strong> 
	</li>
	<li>
		<strong>2</strong> 
	</li>
	<li>
		<strong>3</strong> 
	</li>
	<li>
		<strong>3</strong> 
	</li>
</ul>
<p>
	<span><span style="font-size:18px;">1</span></span> 
</p>
<p style="text-align:right;">
	<span><span style="font-size:18px;">1</span></span> 
</p>
<p>
	<br />
</p>
<ul>
	<li>
		1
	</li>
	<li>
		2
	</li>
</ul>
<p>
	<span><span style="font-size:18px;"><br />
</span></span> 
</p>
<p>
	<br />
</p>
<ol>
	<li>
		2
	</li>
	<li>
		2
	</li>
	<li>
		2
	</li>
	<li>
		2
	</li>
	<li>
		<br />
	</li>
</ol>
<p>
	<embed src="https://imgcache.qq.com/tencentvideo_v1/playerv3/TPout.swf?max_age=86400&v=20161117&vid=m0022q1m3w4&auto=0" type="application/x-shockwave-flash" width="350" height="400" autostart="true" loop="true" allowScriptAccess="never"/>
</p>
<p>
	<br />
</p>
<p>
	<br />
</p>
  <iframe src="http://player.video.qiyi.com/2ccbec5d7bf118758b24ba6afb458ec3/0/0/v_19rrnyh988.swf-albumId=338538900-tvId=338538900-isPurchase=0-cnId=5" frameborder="0" allowfullscreen="true" width="100%" height="300"></iframe>
  
  <iframe src="http://open.iqiyi.com/developer/player_js/coopPlayerIndex.html?vid=120f39f0c17ddae3e29aefd1e4a11935&tvId=624890600&accessToken=2.f22860a2479ad60d8da7697274de9346&appKey=3955c3425820435e86d0f4cdfe56f5e7&appId=1368&height=100%&width=100%" frameborder="0" allowfullscreen="true" width="100%" height="100%"></iframe>
  
  <embed src="http://player.video.qiyi.com/2ccbec5d7bf118758b24ba6afb458ec3/0/0/v_19rrnyh988.swf-albumId=338538900-tvId=338538900-isPurchase=0-cnId=5" type="video/x-ms-asf-plugin" width="550" height="400" autostart="false" loop="true" />
  
  <embed src="https://imgcache.qq.com/tencentvideo_v1/playerv3/TPout.swf?max_age=86400&v=20161117&vid=c0022rn90ex&auto=0" allowFullScreen="true" quality="high" width="480" height="400" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>
  
  <?php echo urlencode('(ABC)');  
  
  echo str_replace(['(', ')', '%28', '%29'], '', 'http://abc(abc)3434%28%29adkf');
  
  
  $a = 10;
  
  $a *= 10;
  echo $a;
  ?>