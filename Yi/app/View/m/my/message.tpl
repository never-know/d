	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
 
	<div class="weui_cells weui_cells_access" id="list_loaded">
		<?php if(!empty($result['list'])) : ?>
		
			<?php foreach($result['list'] as $value)   : ?>
			<a class="weui_cell" href="/message/<?=$value['message_id'];?>.html">
			  <div class="weui_cell_hd"><img src="<?=(\message_icon($value['message_type']));?>" alt="" width="24"></div>
			  <div class="weui_cell_bd weui_cell_primary">
				<p><?=$value['title'];?></p>
			  </div>
			  <div class="weui_cell_ft"></div>
			</a>
			<?php endforeach;?>
		<?php else :?>
			<h3 class="no-data">暂无消息</h3>
		
		<?php endif;?>
			 
			<!-- template
			<a class="weui_cell" href="/balance/income.html">
			  <div class="weui_cell_hd"><img src="/public/images/message.png" alt="" width="24"></div>
			  <div class="weui_cell_bd weui_cell_primary">
				<p>您有新的阅读</p>
			  </div>
			  <div class="weui_cell_ft">&nbsp;</div>
			</a>
			<a class="weui_cell" href="/balance/income.html">
			  <div class="weui_cell_hd"><img src="/public/images/message.png" alt="" width="24"></div>
			  <div class="weui_cell_bd weui_cell_primary">
				<p>您有一笔下线提成收益</p>
			  </div>
			  <div class="weui_cell_ft">&nbsp;</div>
			</a>
			-->
			
	
	</div>
	
	<div class="weui-infinite-scroll">

	 <?php if ($result['page']['total_page'] < 2) : ?>
	  ------ 加载完成 ------
	</div>
	<script>
		if (document.body.clientWidth >=  document.body.scrollHeight) {
			$('.weui-infinite-scroll').hide();
		}
	</script>	 
	 <?php else : ?>
	 
	   <div class="infinite-preloader"></div>
	  正在加载... 
	</div>
 
	
	<script>
	    
		var template = function(i, value){
		
			return  ('<a class="weui_cell" href="javascript:;">' +
			  '<div class="weui_cell_hd"><img src="'+ value.img + ' alt="" width="24"></div>'+
			  '<div class="weui_cell_bd weui_cell_primary">"+
				'<p>'+value.title+'</p>'+
			  '</div>'+
			  '<div class="weui_cell_ft">&nbsp;</div>"+
			'</a>');
		}
		
		page_load('/my/message.html', template);
	  
    </script>
	<?php endif; ?>
	 
 
	 