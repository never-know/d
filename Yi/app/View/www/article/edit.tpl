
	<div class="breadcrumb">
		<span style="margin-left:28px;">文案＞</span><label class="subtitle">添加文案</label>
	</div> 
	<form name="article_edit" id="article_edit" style="width:700px;overflow:hidden;" onsubmit="return false;" target="iframe" action="/article/preview.html" method="post">
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
				value = "<?php 
					$icon =  \check_url($result['detail']['icon']);
					echo $icon ;
				?>"
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

		<dl>
			<dt>开始日期：</dt> 
			<dd>	
			<input type="text" name="date_start" id="date_start" class="tcal" autocomplete="off" readonly="readonly" value="<?=($result['detail']['start']?(date('Y-m-d', strtotime($result['detail']['start']))):'');?>"/>
				<label>选择当日则审核通过后立刻生效</label></dd>
		</dl>
		<dl>
			<dt>结束日期：</dt> 
			<dd>
			<input type="text" name="date_end" id = "date_end" class="tcal" autocomplete="off" readonly="readonly" value="<?=($result['detail']['end']?(date('Y-m-d', strtotime($result['detail']['end']))):'');?>"/>
				<label>至少大于当前日期2天，不填写则长期有效</label>
			</dd>
		</dl>
		
		<dl style="overflow:visible; height:40px;">
			<dt>推广范围：</dt> 
			<dd style="overflow:visible;" id="region">
				<input type="hidden" id="region_selected" class="diy_select_input" name="region" value="<?=end($result['params']['region']);?>"/>
				<?php $width = ['323px','242px','242px','242px;left:-112px;']; foreach ($width as $i => $w) { ?>
				<div class="diy_select" >
					<i class="diy_select_txt"><?=(isset($result['params']['region'][$i+1])?($result['region_list'][$result['params']['region'][$i]][$result['params']['region'][$i+1]]):((0==$i)?'全国':'--不限--'));?></i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:<?=$w;?>">
						<?php if (isset($result['params']['region'][$i])) {
							echo '<li sid="0" rid="', $result['params']['region'][$i] ,'">',((0==$i)?'全国':'--不限--'),'</li>';
							foreach($result['region_list'][$result['params']['region'][$i]] as $key => $value) {
								echo '<li sid="', $key, '">', $value,'</li>';
						} } else {
							echo '<li sid="0">--不限--</li>';
						} ?>
					</ul>
				</div>
				<?php } ?>
			</dd>
		</dl>
		
		<dl>
			<dt>内容：</dt> 
			<dd id="richtext"><textarea id="content" style="width:422px;height:500px;visibility:hidden;" >
			</textarea>
			<label>内容10-60000个字符</label>
			</dd>
		</dl>
		<dl id="buttons">
			<input type="hidden" name="csrf_token" id="csrf_token" />
			<input type="hidden"  name="content" id="content_preview" />
			<input type="hidden" value="<?=(int2str($result['detail']['id']));?>" name="id" id="article_id" />
			<button href="javascript:;" style="width:120px;" type ="submit" class="login-btn" id="article_submit"  sindex="0" token="<?=get_token('www_article_edit');?>">提交</a></button>
			<button href="javascript:;" style="width:120px;" type ="submit" class="login-btn" id="article_preview"  sindex="0" token="<?=get_token('www_article_preview');?>">预览</a></button>
		</dl>

	</form>
	
	
<link rel="stylesheet" href="/public/kindeditor-4.1.10/themes/default/default.css" />
<script charset="utf-8" src="/public/kindeditor-4.1.10/kindeditor-min.js"></script>
<script type="text/javascript" src="/public/js/tcal.js"></script> 
<script type="text/javascript" src="/public/js/region.js"></script> 
<script type="text/javascript" src="/public/js/dialog.js"></script> 
<iframe name="iframe" id="iframe" src="http://www.yi.com" ></iframe>	
<script>
	KindEditor.lang({
	source : 'HTML代码',
	preview : '预览',
	undo : '后退(Ctrl+Z)',
	redo : '前进(Ctrl+Y)',
	cut : '剪切(Ctrl+X)',
	copy : '复制(Ctrl+C)',
	paste : '粘贴(Ctrl+V)',
	plainpaste : '粘贴为无格式文本',
	wordpaste : '从Word粘贴',
	selectall : '全选(Ctrl+A)',
	justifyleft : '左对齐',
	justifycenter : '居中',
	justifyright : '右对齐',
	justifyfull : '两端对齐',
	insertorderedlist : '编号',
	insertunorderedlist : '项目符号',
	indent : '增加缩进',
	outdent : '减少缩进',
	subscript : '下标',
	superscript : '上标',
	formatblock : '段落',
	fontname : '字体',
	fontsize : '文字大小',
	forecolor : '文字颜色',
	hilitecolor : '文字背景',
	bold : '粗体(Ctrl+B)',
	italic : '斜体(Ctrl+I)',
	underline : '下划线(Ctrl+U)',
	strikethrough : '删除线',
	removeformat : '删除格式',
	image : '图片',
	multiimage : '批量图片上传',
	flash : 'Flash',
	media : '视音频',
	table : '表格',
	tablecell : '单元格',
	hr : '插入横线',
	emoticons : '插入表情',
	link : '超级链接',
	unlink : '取消超级链接',
	fullscreen : '全屏显示',
	about : '关于',
	print : '打印(Ctrl+P)',
	filemanager : '文件空间',
	code : '插入程序代码',
	map : 'Google地图',
	baidumap : '百度地图',
	lineheight : '行距',
	clearhtml : '清理HTML代码',
	pagebreak : '插入分页符',
	quickformat : '一键排版',
	insertfile : '插入文件',
	template : '插入模板',
	anchor : '锚点',
	yes : '确定',
	no : '取消',
	close : '关闭',
	editImage : '图片属性',
	deleteImage : '删除图片',
	editFlash : 'Flash属性',
	deleteFlash : '删除Flash',
	editMedia : '视音频属性',
	deleteMedia : '删除视音频',
	editLink : '超级链接属性',
	deleteLink : '取消超级链接',
	editAnchor : '锚点属性',
	deleteAnchor : '删除锚点',
	tableprop : '表格属性',
	tablecellprop : '单元格属性',
	tableinsert : '插入表格',
	tabledelete : '删除表格',
	tablecolinsertleft : '左侧插入列',
	tablecolinsertright : '右侧插入列',
	tablerowinsertabove : '上方插入行',
	tablerowinsertbelow : '下方插入行',
	tablerowmerge : '向下合并单元格',
	tablecolmerge : '向右合并单元格',
	tablerowsplit : '拆分行',
	tablecolsplit : '拆分列',
	tablecoldelete : '删除列',
	tablerowdelete : '删除行',
	noColor : '无颜色',
	pleaseSelectFile : '请选择文件。',
	invalidImg : "请输入有效的URL地址。\n只允许jpg,gif,bmp,png格式。",
	invalidMedia : "请输入有效的URL地址。\n只允许swf,flv,mp3,wav,wma,wmv,mid,avi,mpg,asf,rm,rmvb格式。",
	invalidWidth : "宽度必须为数字。",
	invalidHeight : "高度必须为数字。",
	invalidBorder : "边框必须为数字。",
	invalidUrl : "请输入有效的URL地址。",
	invalidRows : '行数为必选项，只允许输入大于0的数字。',
	invalidCols : '列数为必选项，只允许输入大于0的数字。',
	invalidPadding : '边距必须为数字。',
	invalidSpacing : '间距必须为数字。',
	invalidJson : '服务器发生故障。',
	uploadSuccess : '上传成功。',
	cutError : '您的浏览器安全设置不允许使用剪切操作，请使用快捷键(Ctrl+X)来完成。',
	copyError : '您的浏览器安全设置不允许使用复制操作，请使用快捷键(Ctrl+C)来完成。',
	pasteError : '您的浏览器安全设置不允许使用粘贴操作，请使用快捷键(Ctrl+V)来完成。',
	ajaxLoading : '加载中，请稍候 ...',
	uploadLoading : '上传中，请稍候 ...',
	uploadError : '上传错误',
	'plainpaste.comment' : '请使用快捷键(Ctrl+V)把内容粘贴到下面的方框里。',
	'wordpaste.comment' : '请使用快捷键(Ctrl+V)把内容粘贴到下面的方框里。',
	'code.pleaseInput' : '请输入程序代码。',
	'link.url' : 'URL',
	'link.linkType' : '打开类型',
	'link.newWindow' : '新窗口',
	'link.selfWindow' : '当前窗口',
	'flash.url' : 'URL',
	'flash.width' : '宽度',
	'flash.height' : '高度',
	'flash.upload' : '上传',
	'flash.viewServer' : '文件空间',
	'media.url' : 'URL',
	'media.width' : '宽度',
	'media.height' : '高度',
	'media.autostart' : '自动播放',
	'media.upload' : '上传',
	'media.viewServer' : '文件空间',
	'image.remoteImage' : '网络图片',
	'image.localImage' : '本地上传',
	'image.remoteUrl' : '图片地址',
	'image.localUrl' : '上传文件',
	'image.size' : '图片大小',
	'image.width' : '宽',
	'image.height' : '高',
	'image.resetSize' : '重置大小',
	'image.align' : '对齐方式',
	'image.defaultAlign' : '默认方式',
	'image.leftAlign' : '左对齐',
	'image.rightAlign' : '右对齐',
	'image.imgTitle' : '图片说明',
	'image.upload' : '浏览...',
	'image.viewServer' : '图片空间',
	'multiimage.uploadDesc' : '允许用户同时上传<%=uploadLimit%>张图片，单张图片容量不超过<%=sizeLimit%>',
	'multiimage.startUpload' : '开始上传',
	'multiimage.clearAll' : '全部清空',
	'multiimage.insertAll' : '全部插入',
	'multiimage.queueLimitExceeded' : '文件数量超过限制。',
	'multiimage.fileExceedsSizeLimit' : '文件大小超过限制。',
	'multiimage.zeroByteFile' : '无法上传空文件。',
	'multiimage.invalidFiletype' : '文件类型不正确。',
	'multiimage.unknownError' : '发生异常，无法上传。',
	'multiimage.pending' : '等待上传',
	'multiimage.uploadError' : '上传失败',
	'filemanager.emptyFolder' : '空文件夹',
	'filemanager.moveup' : '移到上一级文件夹',
	'filemanager.viewType' : '显示方式：',
	'filemanager.viewImage' : '缩略图',
	'filemanager.listImage' : '详细信息',
	'filemanager.orderType' : '排序方式：',
	'filemanager.fileName' : '名称',
	'filemanager.fileSize' : '大小',
	'filemanager.fileType' : '类型',
	'insertfile.url' : 'URL',
	'insertfile.title' : '文件说明',
	'insertfile.upload' : '上传',
	'insertfile.viewServer' : '文件空间',
	'table.cells' : '单元格数',
	'table.rows' : '行数',
	'table.cols' : '列数',
	'table.size' : '大小',
	'table.width' : '宽度',
	'table.height' : '高度',
	'table.percent' : '%',
	'table.px' : 'px',
	'table.space' : '边距间距',
	'table.padding' : '边距',
	'table.spacing' : '间距',
	'table.align' : '对齐方式',
	'table.textAlign' : '水平对齐',
	'table.verticalAlign' : '垂直对齐',
	'table.alignDefault' : '默认',
	'table.alignLeft' : '左对齐',
	'table.alignCenter' : '居中',
	'table.alignRight' : '右对齐',
	'table.alignTop' : '顶部',
	'table.alignMiddle' : '中部',
	'table.alignBottom' : '底部',
	'table.alignBaseline' : '基线',
	'table.border' : '边框',
	'table.borderWidth' : '边框',
	'table.borderColor' : '颜色',
	'table.backgroundColor' : '背景颜色',
	'map.address' : '地址: ',
	'map.search' : '搜索',
	'baidumap.address' : '地址: ',
	'baidumap.search' : '搜索',
	'baidumap.insertDynamicMap' : '插入动态地图',
	'anchor.name' : '锚点名称',
	'formatblock.formatBlock' : {
		h1 : '标题 1',
		h2 : '标题 2',
		h3 : '标题 3',
		h4 : '标题 4',
		p : '正 文'
	},
	'fontname.fontName' : {
		'SimSun' : '宋体',
		'NSimSun' : '新宋体',
		'FangSong_GB2312' : '仿宋_GB2312',
		'KaiTi_GB2312' : '楷体_GB2312',
		'SimHei' : '黑体',
		'Microsoft YaHei' : '微软雅黑',
		'Arial' : 'Arial',
		'Arial Black' : 'Arial Black',
		'Times New Roman' : 'Times New Roman',
		'Courier New' : 'Courier New',
		'Tahoma' : 'Tahoma',
		'Verdana' : 'Verdana'
	},
	'lineheight.lineHeight' : [
		{'1' : '单倍行距'},
		{'1.5' : '1.5倍行距'},
		{'2' : '2倍行距'},
		{'2.5' : '2.5倍行距'},
		{'3' : '3倍行距'}
	],
	'template.selectTemplate' : '可选模板',
	'template.replaceContent' : '替换当前内容',
	'template.fileList' : {
		'1.html' : '图片和文字',
		'2.html' : '表格',
		'3.html' : '项目编号'
	}
}, 'zh_CN');

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
		width: 480,
		height:500,
		minWidth:480,
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
			if(!window.editor.fullscreenMode){
				Min.dom.next(tar).removeAttribute('style');				
				Min.dom.pre(tar).style.borderColor = '#cccccc';	
			}
		}
	}); 
	window.editor.html(<?=safe_json_encode($result['detail']['content']??'');?>);
});
		
		 
Min.event.bind('buttons','click',{handler: function(e){
		var has_error = false;
		var t = e.delegateTarget;
		console.log(t);
		var article_edit_error = function(id, message){
			var tar = _$(id), next = Min.dom.next(tar);
			next.style.color = error_bordercolor;
			if(message) next.innerHTML = message;
			if(id == 'content') tar = Min.dom.pre(tar);
			tar.style.borderColor = '#ffb4a8';
			has_error = true;
		}
		
		var title = _$('title').value;

		if(!title || title.length<6 || title.length>32)  {
			article_edit_error('title');
			
		}

		var desc = _$('desc').value;
		if(!desc || desc.length <10 || desc.length>64)  {
			article_edit_error('desc');
		}

	
		var date_end 	= _$('date_end').value;
		var date_start 	= _$('date_start').value;
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
		
		var tag = article_edit.tag.value;
		if (!tag) {
			article_edit_error('tag');
		}
		console.log(has_error);
		if(has_error){
			t.setAttribute("sindex", 0);
			return false;
		}
		
		var sindex	= t.getAttribute('sindex');
	
		if(sindex == 0) {

			 console.log(t.id);
			if (t.id == 'article_preview') {
				_$('content_preview').value = content;
				_$('csrf_token').value = t.getAttribute('token');
				_$('article_edit').submit();
				_$('iframe').width=500 ;
				_$('iframe').height=300
				easyDialog.open({
				  container : 'iframe',
				  overlay:false,
				  fixed : true
				});	
				return;
			}
			t.setAttribute("sindex", 1);
			minAjax({
				url:'http://www.' + site_domain + '/article/'+ t.getAttribute('tot')+'.html', 
				type:'POST', 
				data:{
					title:title,
					desc:desc,
					icon:_$('icon').value,
					date_start:date_start,
					date_end:date_end,
					content:content,
					region:_$('region_selected').value,
					csrf_token:t.getAttribute('token'),
					tag: tag,
					id:_$('article_id').value
				},
				success: function(data){
					if(data.statusCode == 0) {
						window.location.href = '/article/list.html'
					}else{
						_$('article_submit').setAttribute("sindex", 0);
					}
				},
				fail: function(){
					_$('article_submit').setAttribute("sindex", 0);
				}
			});
		}
}, 'selector': 'button'});

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
	