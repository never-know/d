	<style>
	 
	.balance-detail .share_clock{
		padding-right:8px;
		font-size: 13px;
	}
	.balance-detail .share_title{
		font-size:12px;
		height:16px;
		overflow:hidden;
		 
	}
	
	.balance-detail .share_detail{
		font-size: 12px;
		margin-top:4px;
 
		color: #9c9a9a;
		height:16px; 
		overflow:hidden;
	}
	.balance-detail li img {
	
		width:38px;
		margin-right: 8px;
		margin-left: 0;
	}
	</style>
	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
	
	<div class="page-hd ">
        <h1 class="page-hd-title" style="margin-bottom:0;padding-bottom:0;">
            <strong style="font-size: 24px;padding-top:-2px;">￥</strong><?=($result['summary']['total']/100);?>
        </h1>
         <p class="page-hd-desc"><?=($result['date']);?></p>
		  <div class="weui-row weui-no-gutter" style="position:relative;    border-top: 1px solid #f1f1f1;">
			<a class="weui-col-50 col-50-first" href="javascript:;">
				<div>
					<p><?=($result['summary']['share_part']/100);?></p>
					<span>分享收益 (元)</span>
				</div>
			</a>
		
			<a class="weui-col-50" href="javascript:;">
				<div>
					<p><?=($result['summary']['team_part']/100);?></p>
					<span>团队收益 (元)</span>
				</div>
			</a>
		</div>
    </div>
	
 
<div class="weui_cells balance-detail">
	<ul id="list_loaded">
		<?php if (!empty($result['list'])) : ?>
		<?php foreach ($result['list'] as $value) : ?>
        <li class="weui_cell" href="javascript:;">
			<?php if ($value['balance_type'] == 2) : ?>
			
				<div class="weui_cell_hd"><img src="<?=$value['content_icon']?>" alt="" ></div>
				<div class="weui_cell_bd weui_cell_primary">
				<p class="share_title"><?=$value['content_title']?></p>
				<p class="share_detail"><?=date('m-d H:i', $value['share_time'])?> 分享<?=($value['share_type']?'于朋友圈':'给好友')?></p>
				
			<?php else : ?>
			
				<div class="weui_cell_hd"><img src="/public/images/my.png" alt="" ></div>
				<div class="weui_cell_bd weui_cell_primary">
				<p class="share_title">TEAM SHALARY</p>
				<p class="share_detail">来自用户 <?=$value['phone']?></p>

			<?php endif; ?>
          </div>
          <div class="weui_cell_ft" style="font-size:18px;padding-left: 10px;">
		  <p>+<?=$value['user_money']?></p>
		  <p><?=date('H:i', $value['post_time'])?></p></div>
        </li>
		<?php endforeach; ?>
		<?php else : ?>
			<h3 class="no-data">暂无记录</h3>
		<?php endif; ?>
		<!-- template
		<li class="weui_cell" href="/share/log.html">
			<div class="share_clock"> 12:30 </div>
			<div class="weui_cell_hd"><img src="/public/images/avater.png" alt="" ></div>
			<div class="weui_cell_bd weui_cell_primary">
            <p class="share_title">永辉超市转塘店6.1大促</p>
            <p class="share_detail">分享于12-03 12:30@朋友圈 </p>
          </div>
          <div class="weui_cell_ft" style="font-size:18px;padding-left: 10px;">+ 0.2</div>
        </li>
		 
		-->
	</ul>
</div>

<!-- page --->
	
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
			var html = "";
		   html += '<li class="weui_cell" href="javascript:;">';
			if (value.balance_type == 2) {
				html += ('<div class="weui_cell_hd"><img src="' + value.content_icon +'" alt="" ></div>'+
				'<div class="weui_cell_bd weui_cell_primary">'+
				'<p class="share_title">' + value.content_title + '</p>'+
				'<p class="share_detail">'+ new Date(value.share_time).Format('mm-dd HH:ii') + '分享' + ((value.share_type==1)?'于朋友圈':'给好友') + '</p>');
				
			} else {
			
				html += ('<div class="weui_cell_hd"><img src="/public/images/my.png" alt="" ></div>'+
				'<div class="weui_cell_bd weui_cell_primary">' +
				'<p class="share_title">TEAM SHALARY</p>' + 
				'<p class="share_detail">来自用户 ' +  value.phone  +'</p>');

			}
			html += '</div>';
			html += '<div class="weui_cell_ft" style="font-size:18px;padding-left: 10px;">';
			html += '<p>+'+ value.user_money + '</p>';
			html += '<p> '+ new Date(value.post_time).Format('HH:ii') + '</p></div>';
			html += '</li>';
			return html;
		}
		
		page_load('/balance/daily.html',   template);
	  
    </script>
	<?php endif; ?>
