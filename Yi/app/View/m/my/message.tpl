	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
 
	<div class="weui_cells weui_cells_access">
		<?php if(!empty($result['list'])) : ?>
		
			<?php foreach($result['list'] as $value)   : ?>
			<a class="weui_cell" href="/balance/items/<?=$value['message_id'];?>.html">
			  <div class="weui_cell_hd"><img src="<?=(\message_icon($value['message_type']));?>" alt="" width="24"></div>
			  <div class="weui_cell_bd weui_cell_primary">
				<p><?=$value['title'];?></p>
			  </div>
			  <div class="weui_cell_ft"></div>
			</a>
			<?php endforeach;?>
			
			 
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
			
		<?php else :?>
		<h3 style="text-align:center;margin-top:30px;">暂无消息</h3>
		
		<?php endif;?>
	</div>
	 
 
	 