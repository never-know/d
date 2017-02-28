<textarea id="content" name="content" style="width:700px;height:200px;visibility:hidden;" ></textarea>
	<script charset="utf-8" src="/public/kindeditor-4.1.10/kindeditor-min.js"></script>
	<script charset="utf-8" src="/public/kindeditor-4.1.10/lang/zh_CN.js"></script>
	
	<script>
		KindEditor.ready(function(K) {
			K.create('#content', {
				themeType : 'simple',
				allowFileManager : false,
				formatUploadUrl: false,
				uploadJson: '/upload.html',
				imageSizeLimit : '1MB',
				imageFileTypes : '*.jpg;*.gif;*.png',
				imageUploadLimit : 20,
				filePostName : 'imgFile',
				extraFileUploadParams : {"csrf_token":"<?=get_token('www_upload_index');?>", "isAjax":1}
			}); 
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
	<embed src="http://player.youku.com/player.php/sid/XMjUzMTk4NTk2MA==/v.swf" type="application/x-shockwave-flash" width="350" height="400" autostart="true" loop="true" />
</p>
<p>
	<br />
</p>
<p>
	<br />
</p>