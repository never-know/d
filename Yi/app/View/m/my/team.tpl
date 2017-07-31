	<style>
	 
	.balance-detail .share_clock{
		padding-right:8px;
		font-size: 15px;
	}
	.balance-detail .share_title{
		font-size:14px;
		height:18px;
		overflow:hidden;
		    padding-top: 2px;
	}
	
	.balance-detail .share_detail{
		font-size: 12px;
		margin-top:4px;
		margin-left:4px;
		color: #9c9a9a;
	 
		height:16px; 
		overflow:hidden;
	}
	.balance-detail li img {
		width:50px;
	}
	</style>
	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
	
	<div class="page-hd">
        <h1 class="page-hd-title">
			共计 <?=($result['page']['total_data']+$result['level2'])?> 名
        </h1>
        <div class="weui-row weui-no-gutter" style="position:relative;    border-top: 1px solid #f1f1f1;">
			<a class="weui-col-50 col-50-first" href="javascript:;">
				<div>
					<p><?=($result['page']['total_data']+$result['level2'])?></p>
					<span>直属成员(名)</span>
				</div>
			</a>
		
			<a class="weui-col-50" href="javascript:;">
				<div>
					<p><?=$result['level2']?></p>
					<span>二级成员(名)</span>
				</div>
			</a>
		</div>
    </div>
	
	 
	<div class="weui_panel weui_panel_access weui_panel_team">
			 
			<div class="weui_panel_bd" id="content_load">
			<?php if(!empty($result['list'])) : ?>
		
			<?php foreach($result['list'] as $value)   : ?>
				<a href="/my/subteam/<?=base_convert($value['wx_id'], 10, 36)?>.html" class="weui_media_box weui_media_appmsg">
                   <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="<?=ASSETS_URL?>/avater/<?=(implode('/', str_split(base_convert($value['wx_id'], 10, 36), 2)))?>.jpg"  onerror="imgnotfound()" alt="">
                    </div>
                    <div class="weui_media_bd">
                        <p class="weui_media_desc"><?=$value['phone']?></p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">贡献收益 ￥ <?=$value['benefit_1']?></li>
							<li class="weui_media_info_meta">下级 <?=$value['children'];?>人</li> 
					  </ul>
                    </div>
					<div class="weui_panel_ft">&nbsp;</div>
					 
                </a>
			<?php endforeach;?>
			<?php else : ?>
				<h3 class="no-data">暂无成员</h3>
		<?php endif;?>
			
			<!-- template
				<a href="/my/subteam/2.html" class="weui_media_box weui_media_appmsg">
                   <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avater.png" alt="">
                    </div>
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">183****8890</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">贡献收益 ￥300</li>
							<li class="weui_media_info_meta">下级 123人</li> 
					  </ul>
                    </div>
					<div class="weui_panel_ft">&nbsp;</div>
					 
                </a>
				-->
				 
	
			</div>
	</div>