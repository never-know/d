	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
 
	<div class="weui_cells weui_cells_access" id="list_loaded">
		<?php if (!empty($result['list'])) : ?>
		<?php foreach ($result['list'] as $value) :?>
        <a class="weui_cell" href="/draw/detail/<?=$value['draw_id']?>.html">
          <div class="weui_cell_hd"><img src="/public/images/draw<?=$value['draw_status']?>.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p><?=$value['draw_money']?></p>
          </div>
          <div class="weui_cell_ft"></div>
        </a>
		<?php endforeach; ?>
		
		<?php else :?>
				<h3 class="no-data">暂无记录</h3>
		<?php endif;?>
		
		<!--
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
	
	<!-- page --->
	<?php if (!empty($result['list'])) : ?>	
	<div class="weui-infinite-scroll">

	 <?php if ($result['page']['total_page'] < 2) : ?>
	  -------- 加载完成 --------
	</div>
  
	 <?php else : ?>
	 
	   <div class="infinite-preloader"></div>
	  正在加载... 
	</div>
 
	<script>
	    
		var template = function(i, value){
			return ( '<a class="weui_cell" href="/draw/detail/'+ value.draw_id + '.html">'+
          '<div class="weui_cell_hd"><img src="/public/images/draw'+value.draw_status+'.png" alt="" width="24"></div>'+
         ' <div class="weui_cell_bd weui_cell_primary">'+
            '<p>'+value.draw_money+'</p>'+
         ' </div>'+
          '<div class="weui_cell_ft"></div>'+
       ' </a>');
		}
		
		page_load('/draw/wdlog.html',   template);
	  
    </script>
	<?php endif; ?>
	<?php endif; ?>
	
	 
 
	 