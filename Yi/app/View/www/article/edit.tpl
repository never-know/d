	
	
	
	<textarea id="content" name="content" style="width:700px;height:200px;visibility:hidden;"></textarea>
	<link rel="stylesheet" href="/public/kindeditor-4.1.10/themes/default/default.css" />
	<script charset="utf-8" src="/public/kindeditor-4.1.10/kindeditor-min.js"></script>
	<script charset="utf-8" src="/public/kindeditor-4.1.10/lang/zh_CN.js"></script>
	<script>
		KindEditor.ready(function(K) {
			K.create('#content', {
				themeType : 'simple'
			});
			 
		});
	</script>