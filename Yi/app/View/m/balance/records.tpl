	<div class="weui_msg" style="padding-top:12px;"> 
      <div class="weui_text_area" style="background: white;padding: 20px;">
		<p class="weui_msg_desc">帐户余额</p>
        <h1  style="color:red;padding-top:4px;"><strong >￥</strong><?=session_get('user_balance')['balance']?></h1>
      </div>
     
    </div>
	<style>
		
	.balance_reocrds .weui_panel_hd:before{
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
	 
	.balance_reocrds .weui_panel_hd:first-child:before{display:none}
	.weui_msg .weui_text_area{
		margin-bottom:0;
	}
	.balance_reocrds .weui_panel_hd{
		font-size: 14px;
		font-weight: 600;
	}

	</style>
	<div class="weui_cells balance_reocrds" id="list_loaded">
	<?php if(!empty($result['list'])) : ?>
	<?php foreach ($result['list'] as $value) :  $this_date = date('Y-m', $value['post_time']);
		if (empty($current_date) || $current_date != $this_date) : $current_date = $this_date ;?>			
			<div class="weui_panel_hd"><?=$this_date?></div>
		<?php endif;?>
        <div class="weui_cell"  >
         
          <div class="weui_cell_bd weui_cell_primary" style="font-size:13px;">
            <p><?=($value['balance_type'])?></p>
            <p><?=date('m-d H:i', $value['post_time'])?></p>
          </div>
		   <div class="weui_cell_ft">
            <p>+ <?=($value['user_money'])?></p>
            <p><?=($value['user_current_balance'])?></p>
          </div>
          
        </div>
		<?php endforeach; ?>
		<script> var current_date = '<?=$current_date?>'; </script>
	<?php else :?>
			<h3 class="no-data">暂无记录</h3>
	<?php endif;?>
	
	  <!-- template
		<div class="weui_panel_hd">2017-09</div>
		  <div class="weui_cell"  >
         
          <div class="weui_cell_bd weui_cell_primary" style="font-size:13px;">
            <p>分享收益</p>
            <p>07-06 12:02</p>
          </div>
		   <div class="weui_cell_ft">
            <p>+ 0.2</p>
            <p>100.2</p>
          </div>
          
        </div>
		-->
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
			url:'/balance/records.html', 
			type:'GET', 
			data: {page:current_page+1},
			success: function(data){
				if (data.statusCode == 1 ) {
					 
					if (data.body.list.length > 0) {

						$.each(data.body.list, function(i, value){
							this_date = new Date(value.post_time*1000).Format('yyyy-mm'); 
							if ( current_date !=  this_date) {
								current_date =  this_date ; 
								
								html += ('<div class="weui_panel_hd"  >' + this_date +'</div>');
							}
							
						    html += ('<div class="weui_cell">'+
         
							  '<div class="weui_cell_bd weui_cell_primary" style="font-size:13px;">'+
								'<p>'+value.balance_type+'</p>'+
								'<p>'+ new Date(value.post_time*1000).Format('mm-dd HH:ii') +  '</p>'+
							 ' </div>'+
							   '<div class="weui_cell_ft">'+
								'<p>+ ' + value.user_money +'</p>'+
								'<p>'+ value.user_current_balance+'</p>'+
							 ' </div>'+
							  
							'</div>');
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
	 