 
	<div class="breadcrumb">
		<span style="margin-left:28px;">需求＞</span><label class="subtitle">需求列表</label>
	</div>
 
	<ul id="article_list">
		<li style="background:#eaeaea">	
			<span class="number"> 编号 </span>
		 
			<a href="javascript:;" target="_blank" class="article_list_title">标题</a>
			<span class="status" style="    min-width:  80px;"> 状态</span>
			 
			<a href="javascript:;" class="collect" style="    min-width: 80px;">操作</a>
		</li>  
		<?php if (!empty($result['list'])) { 
			foreach($result['list'] as $key => $value) { ?>
			<li>	
			<span class="number"> <?=$value['needs_id'];?> </span>
			<a href="javascript:;"   class="article_list_title"><?=$value['title'];?></a>
			<span class="status" style=" min-width:  80px;"><?php 
			
			switch ($value['status']) {
				case 0:
					echo '等待报价';
					break;
				case 1:
					echo '300元';
					break;
				case 2:
					echo '2017-09-01交付';
					break;
				case 3:
					echo '已成交';
					break;
			
			}
 
			
			?></span>
			
			
			
			<?php if($value['status'] == 1 ) :?>
			<a href="javascript:;"  class="collect" style="    min-width:  80px;">支付</a>
			 
			<?php elseif($value['status'] == 0) :?>
			<a href="/needs/edit/<?=$value['needs_id'];?>.html"  class="collect" style="    min-width:  80px;">编辑</a> 
			<?php else :?>
			 
			 <?php endif; ?>
		</li> 
		<?php } } else {
			echo '<span>暂无数据</span>';
		
		} ?>
	   
		 			 
	</ul>
