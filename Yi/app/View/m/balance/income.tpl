
	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
	
	<div class="page-hd">
        <h1 class="page-hd-title">
            累计 <?=(($result['balance']['team_part']+$result['balance']['share_part'])/100);?>
        </h1>
        <div class="weui-row weui-no-gutter" style="position:relative;    border-top: 1px solid #f1f1f1;">
			<a class="weui-col-50 col-50-first" href="javascript:;">
				<div>
					<p><?=($result['balance']['share_part']/100);?></p>
					<span>分享收益 (元)</span>
				</div>
			</a>
		
			<a class="weui-col-50" href="javascript:;">
				<div>
					<p><?=($result['balance']['team_part']/100);?></p>
					<span>团队收益 (元)</span>
				</div>
			</a>
		</div>
	 
    </div>
	
  <div class="page-bd">  
        <ul>
			<?php if (!empty($result['list'])) : ?>
			<?php foreach ($result['list'] as $value ) : ?>
			<li>
                <a href="/balance/daily/<?=$value['post_day'];?>.html">
                    <p><?=('20' . implode('-', str_split($value['post_day'], 2)))?></p>
					<div class="weui_cell_ft">+<?=($value['money']/100);?></div>
                </a>
            </li>
			<?php endforeach; ?>
			<?php else :?>
				<h3 class="no-data">暂无收益</h3>
			<?php endif;?>
			<!--
			<li>
                 <a href="/balance/detail.html">
                    <p class="weui-flex-item">2017-01-30</p>
					<div class="weui_cell_ft">+30.68</div>
                </a>
            </li>
			<li>
                <a href="/balance/detail.html">
                    <p class="weui-flex-item">2017-01-29</p>
					<div class="weui_cell_ft">+990.57</div>
                </a>
            </li>
			-->
		</ul>
    </div>      
