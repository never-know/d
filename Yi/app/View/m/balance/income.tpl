	
	<div class="page-hd">
        <h1 class="page-hd-title">
            累计 <?=(($result['balance']['team_part']+$result['balance']['share_part']));?>
        </h1>
        <div class="weui-row weui-no-gutter" style="position:relative;    border-top: 1px solid #f1f1f1;">
			<a class="weui-col-50 col-50-first" href="javascript:;">
				<div>
					<p><?=($result['balance']['share_part']);?></p>
					<span>分享收益 (元)</span>
				</div>
			</a>
		
			<a class="weui-col-50" href="javascript:;">
				<div>
					<p><?=($result['balance']['team_part']);?></p>
					<span>团队收益 (元)</span>
				</div>
			</a>
		</div>
	 
    </div>
	
	<div class="page-bd">  
        <ul id="list_loaded">
			<?php if (!empty($result['list'])) : ?>
			<?php foreach ($result['list'] as $value ) : ?>
			<li>
                <a href="/balance/daily/<?=$value['post_day'];?>.html">
                    <p><?=$value['post_day']?></p>
					<div class="weui_cell_ft">+ <?=($value['money']);?></div>
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


	<!-- page --->
	<?php if (!empty($result['list'])) : ?>	
	<div class="weui-infinite-scroll">

	 <?php if ($result['page']['total_page'] < 2) : ?>
	  - - - - - - - - 加载完成 - - - - - - - -
	</div>
  
	 <?php else : ?>
	 
	   <div class="infinite-preloader"></div>
	  正在加载... 
	</div>
 
	<script>
	    
		var template = function(i, value){
			return ('<li>'+
                '<a href="/balance/daily/' +value.post_day +'.html">'+
                   ' <p>' +value.post_day +' </p>'+
					'<div class="weui_cell_ft">+ '+ value.money + '</div>'+
                '</a>'+
            '</li>');
		}
		
		page_load('/balance/income.html',   template);
	  
    </script>
	<?php endif; ?>
	<?php endif; ?>
	
