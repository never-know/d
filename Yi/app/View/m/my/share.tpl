 
	<div class="page-hd">
        <h1 class="page-hd-title">
            累计分享 <?=$result['page']['total_data']?> 次
        </h1>
        <div class="weui-row weui-no-gutter" style="position:relative;    border-top: 1px solid #f1f1f1;">
			<a class="weui-col-50 col-50-first" href="javascript:;">
				<div>
					<p><?=($result['readed']?:0)?></p>
					<span>阅读 (次)</span>
				</div>
			</a>
		
			<a class="weui-col-50" href="javascript:;">
				<div>
					<p><?=(session_get('user_balance')['share_part'])?></p>
					<span>分享收益 (元)</span>
				</div>
			</a>
		</div>
	 
    </div>
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
		margin-right: 10px;
		margin-left: 0;
	}
	
	.balance-detail .weui_panel_hd:before{
		    content: " ";
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 1px;
    border-top: 1px solid #d9d9d9;
    color: #d9d9d9;
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
    -webkit-transform: scaleY(.5);
    transform: scaleY(.5);
    left: 15px;
	}
	 
	.balance-detail .weui_panel_hd:first-child:before{display:none}
	
	.balance-detail .weui_panel_hd{
	font-size: 14px;
    font-weight: 600;
	}
	</style>
  
<div class="weui_cells balance-detail">
	<ul id="list_loaded">
	<?php if(!empty($result['list'])) : ?>
		<?php foreach ($result['list'] as $value) :  
			$this_date = date('Y.m.d', $value['share_time']);
			if (empty($current_date) || $current_date != $this_date) :
				$current_date = $this_date ;?>
		<li class="weui_panel_hd"  ><?=$this_date?></li>
			<?php endif; ?>
        <li class="weui_cell" href="/share/views/<?=$value['share_id']?>.html">
			
			<div class="weui_cell_hd"><img src="<?=$value['content_icon']?>" alt="" ></div>
			<div class="weui_cell_bd weui_cell_primary">
            <p class="share_title"><?=$value['content_title']?></p>
            <p class="share_detail"><?=(date('H:i', $value['share_time']))?> · <?=($value['share_type']?'好友':'朋友圈')?> &nbsp;&nbsp;&nbsp;阅读 <?=$value['view_times']?>&nbsp;&nbsp;&nbsp;收益 ￥<?=$value['total_salary']?></p>
          </div>
           
        </li>
		<?php endforeach; ?>
		<script> var current_date = '<?=$current_date?>'; </script>
	<?php else :?>
			<h3 class="no-data">暂无记录</h3>
	<?php endif;?>
	
	<!-- template
		 <li class="weui_cell" href="javascript:;">
			
			<div class="weui_cell_hd"><img src="/public/images/avatar.png" alt="" ></div>
			<div class="weui_cell_bd weui_cell_primary">
            <p class="share_title">永辉超市转塘店6.1大促</p>
            <p class="share_detail">12:30 · 朋友圈 &nbsp;&nbsp;&nbsp;阅读 300&nbsp;&nbsp;&nbsp;收益 ￥122.20</p>
          </div>
           
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
      var loading = false, current_page = 1, total_page = 2, html = '', this_date = '';
	  
      $('.weui_tab_bd').infinite(250).on("infinite", function() {
        if(loading) return;
        if(total_page <= current_page ) return;
        loading = true;
		$.ajax({
			url:'/my/share.html', 
			type:'GET', 
			data: {page:current_page+1},
			success: function(data){
				if (data.statusCode == 1 ) {
					 
					if (data.body.list.length > 0) {

						$.each(data.body.list, function(i, value){
							this_date = new Date(value.share_time*1000).Format('yyyy.mm.dd'); 
							console.log(current_date);
							console.log(this_date);
							if ( current_date !=  this_date) {
								current_date =  this_date ; 
								
								html += ('<li class="weui_panel_hd"  >' + this_date +'</li>');
							}
							
						    html += (' <li class="weui_cell" href="/share/views/'+value.share_id + '.html">'+
								
								'<div class="weui_cell_hd"><img src="'+ value.content_icon + '" alt="" ></div>' +
								'<div class="weui_cell_bd weui_cell_primary">'+
								'<p class="share_title">'+value.content_title+'</p>'+
								'<p class="share_detail">'+ new Date(value.share_time*1000).Format('HH:ii') +' · ' + (value.share_type==0 ?"好友":"朋友圈") + ' &nbsp;&nbsp;&nbsp;阅读 '+ value.view_times + '&nbsp;&nbsp;&nbsp;收益 ￥' + value.total_salary+ '</p>'+
							  '</div>' +
							'</li>');
						});

						 $("#list_loaded").append(html);
					}
					
					html = '';
					current_page = data.body.page.current_page;
					total_page = data.body.page.total_page;
					 
					if (total_page == current_page)　{
						$(".weui-infinite-scroll").html('- - - - - - - - 加载完成 - - - - - - - -');
						 return;
					}
				} else {
					 $.toast(data.message, "cancel");
				}
				
				loading = false;
			},
			error:function(){
				 $.toast("网络连接失败", "cancel");
				 loading = false;
			}
		});
 
      });	
	 </script>
	<?php endif; ?>
	<?php endif; ?>