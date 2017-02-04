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