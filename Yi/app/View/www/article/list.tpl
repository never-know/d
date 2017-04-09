 
	<div class="breadcrumb">
		<span style="margin-left:28px;">文案＞</span><label class="subtitle">文案列表</label>
	</div>
	<form id="search_form" method="get" action="/article/list.html">
	<div class="filter">
		
		<dl  class="filter_tag" id="filter_tag">
			<dt>选择标签：</dt>
			<dd>
			<?php foreach(\article_tags() as $key => $value) : ?>
			<span>
				<input type="radio" name="tag" value="<?=$key;?>" id="ele<?=$key;?>" <?php if(in_array($key, $result['params']['tag'])) echo ' checked="true" ';?>>
				<label for="ele<?=$key;?>"><?=$value;?></label>
			</span>
			<?php endforeach ?>
			</dd>
		</dl>
		<dl class="filter_city" style="overflow:visible;">
			<dt>您的区域：</dt>
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
		<dl class="filter_order">
			<dt class="hide">排序</dt>
			<dd id="order_selected">
			<?php foreach(\article_order() as $key => $value) { ?>
				<span <?php if($result['params']['order'] == $key) echo 'class="selected"';?>>
				<input type="radio" name="order" value="<?=$key;?>" <?php if($result['params']['order'] == $key) echo 'checked="true"'; ?> style="display:none;" > 
				<label><?=$value;?></label></span>
			<?php } ?>
			</dd>
		</dl>
	</div>
	</form>
	<ul id="article_list">
		<li style="background:#eaeaea">	
			<span class="number"> 编号 </span>
			<span class="tag"> 标签</span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">标题</a>
			<span class="region"> 推广区域</span>
			<span class="view_number"> 阅读次数</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
		<?php if (!empty($result['list'])) { 
			foreach($result['list'] as $key => $value) { ?>
		<li>	
			<span class="number"> <?=($key+1);?> </span>
			<span class="tag"> <?=$value['tag_name'];?> </span>
			<a href="/article/detail/<?=$value['id_name'];?>.html" target="_blank" class="article_list_title"><?=$value['title'];?></a>
			<span class="region"><?=$value['region_name'];?></span>
			<span class="view_number"> <?=$value['id'];?></span>
			<a href="/article/edit/<?=$value['id_name'];?>.html" target="_blank" class="collect">编辑</a>
		</li> 
		<?php } } else {
			echo '<span>暂无数据</span>';
		
		} ?>
	   
		 			 
	</ul>
	<div class="page">
		<span>1</span>
		<span>2</span>
		<span>3</span>
		<span>4</span>
		<span>5</span>
	</div>

	
<script>
var PARAMS = <?=safe_json_encode($result['params']);?>;
var lis = _$("article_list").getElementsByTagName("li");
for(i=0; i<lis.length; i=i+2){
	//lis[i].style.background = "#fff";
}
Min.event.bind('filter_tag','click',{handler:function(e){
	if( PARAMS.tag.join('') != search_form.tag.value ) _$('search_form').submit();  
},selector:'span'});

 Min.event.bind('order_selected','click',{handler:function(e){

	Min.obj.each(e.currentTarget.getElementsByTagName('SPAN'),function(val,index,arr){
		Min.css.removeClass('selected', val);
	});
	Min.css.addClass('selected', this);
	this.getElementsByTagName('INPUT')[0].checked = true;
	if(_$('search_form')) _$('search_form').submit();
},selector:'span'});
</script>
<script type="text/javascript" src="/public/js/region.js"></script>