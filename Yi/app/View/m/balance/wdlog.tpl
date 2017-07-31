	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
 
	<div class="weui_cells weui_cells_access">
		<?php if (!empty($result['list'])) : ?>
		<?php foreach ($result['list'] as $value) :?>
        <a class="weui_cell" href="/balance/items.html">
          <div class="weui_cell_hd"><img src="/public/images/credit.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>您的提现申请已通过,请注意查收</p>
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
		->
	</div>
	 
 
	 