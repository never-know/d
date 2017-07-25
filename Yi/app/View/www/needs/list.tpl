 
	<div class="breadcrumb">
		<span style="margin-left:28px;">需求＞</span><label class="subtitle">需求列表</label>
	</div>
 
	<ul id="needs_list">
		<li style="background:#eaeaea">	
			<span class="number"> 编号 </span>
		 
			<a href="javascript:;" target="_blank" class="article_list_title">标题</a>
			<span class="status"> 状态</span>
			 
			<a href="javascript:;" class="collect">操作</a>
		</li>  
		<?php if (!empty($result['list'])) { 
			foreach($result['list'] as $key => $value) { ?>
		<li>	
	
			<a href="javascript:;"   class="article_list_title"><?=$value['title'];?></a>
			<span class="region">等待报价</span>
			<a href="/needs/edit/<?=$value['needs_id'];?>.html" target="_blank" class="collect">编辑</a>
		</li> 
		<?php } } else {
			echo '<span>暂无数据</span>';
		
		} ?>
	   
		 			 
	</ul>
