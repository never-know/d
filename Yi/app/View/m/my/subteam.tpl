	 
	<div class="page-hd">
        <h1 class="page-hd-title">
			用户 <?=$result['phone']?> 下级成员, 共计 <?=($result['page']['total_data']??0)?> 名
        </h1>
        
    </div>
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
	 
	<div class="weui_panel weui_panel_access weui_panel_team">
			 
			<div class="weui_panel_bd" id="list_loaded">
			<?php if(!empty($result['list'])) : ?>
	
			<?php foreach($result['list'] as $value)   : ?>
				<a href="javascript:;" class="weui_media_box weui_media_appmsg">
                   <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="<?=$value['avatar']?>"  onerror="imgnotfound()" alt="">
                    </div>
                    <div class="weui_media_bd">
                        <p class="weui_media_desc"><?=$value['phone']?></p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">贡献收益 ￥<?=($value['benefit_2']?:0)?></li>
							
					  </ul>
                    </div>
					
                </a>
			<?php endforeach;?>
			<?php else : ?>
				<h3 class="no-data">暂无成员</h3>
			<?php endif;?>
			
			<!-- template
				<a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">183****8890</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">贡献收益 ￥3200</li>
							
					  </ul>
                    </div>
					
                </a>
				--> 
			</div>
	</div>
	<!-- page --->
	<?php if(!empty($result['list'])) : ?>
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
		
			return  ('<a href="javascript:;" class="weui_media_box weui_media_appmsg">' +
                   '<div class="weui_media_hd">' +
                        '<img class="weui_media_appmsg_thumb" src="' +  value.avatar + '"  onerror="imgnotfound()" alt="">'+
                   ' </div>'+
                    '<div class="weui_media_bd">' +
                        '<p class="weui_media_desc">'+ value.phone + '</p>' +
						'<ul class="weui_media_info">' +
							'<li class="weui_media_info_meta">  ￥'+value.benefit_2 + '</li>' +	
					 ' </ul>' +
                   ' </div>' +
               ' </a>');
		}
		
		page_load('/balance/message.html',   template);
	  
    </script>
	<?php endif; ?>
	<?php endif; ?>