
	<div class="breadcrumb">
		<span style="margin-left:28px;">需求＞</span><label class="subtitle"><?=$result['meta']['title']?></label>
	</div> 
	<style>
	#article_edit .ar_desc{
	 height:200px;
	}
	</style>
	<form name="article_edit" id="article_edit" style="width:700px;overflow:hidden;" onsubmit="return false;"    method="post">
		<dl>
		 
			<dt>标题：</dt> 
			<dd><input type="text" name="title" maxlength = "50" id="title" class="ar_text_input" 
				value="<?=\check_plain($result['detail']['title']);?>"   >
			<label>标题6-50个字符</label></dd>
		</dl>
		<dl>
			<dt>描述：</dt> 
			<dd><textarea  rows="2" name="desc" maxlength="600" id="desc" class="ar_text_input ar_desc" style=""><?= \check_plain($result['detail']['desc']);?></textarea>
			<label>描述10-600个字符</label></dd>
		</dl>
		 
		<dl id="buttons">
			<input type="hidden" value="<?=(int2str($result['detail']['needs_id']));?>" name="id" id="needs_id" />
			<button href="javascript:;" style="width:120px;" type ="submit" class="login-btn" id="needs_submit"  sindex="0" token="<?=get_token('www_needs_edit');?>" tot="edit">提交</a></button>
	 
		</dl>

	</form>
 
<script>
	 
		 
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

		console.log(has_error);
		if(has_error){
			t.setAttribute("sindex", 0);
			return false;
		}
		
		var sindex	= t.getAttribute('sindex');
	
		if(sindex == 0) {

			 console.log(t.id);
			 
			t.setAttribute("sindex", 1);
			minAjax({
				url:'https://www.anyitime.com/needs/'+ t.getAttribute('tot')+'.html', 
				type:'POST', 
				data:{
					title:title,
					desc:desc,
					csrf_token:t.getAttribute('token'),
					id:_$('needs_id').value
				},
				success: function(data){
					if(data.statusCode == 1) {
							alert('添加成功，请等待报价');
							window.location.href = '/needs/list.html';
					}else{
						_$('needs_submit').setAttribute("sindex", 0);
						alert(data.message);
					}
					
				},
				fail: function(){
					_$('needs_submit').setAttribute("sindex", 0);
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
 
	
</script>
	