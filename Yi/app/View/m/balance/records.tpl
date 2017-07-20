	
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
	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="history.go(-1);">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
  
	<div class="weui_msg" style="padding-top:12px;"> 
      <div class="weui_text_area" style="background: white;padding: 20px;">
		<p class="weui_msg_desc">帐户余额</p>
        <h1  style="color:red;padding-top:4px;"><strong >￥</strong><?=session_get('user_balance')['balance']?></h1>
      </div>
     
    </div>

	<div class="weui_cells balance_reocrds">
	<?php if(!empty($result['list'])) : ?>
	<?php foreach ($result['list'] as $value) :  $this_date = date('Y-m', $value['post_time']);
		if (empty($current_date) || $current_date != $this_date) : $current_date = $this_date ;?>			
			<div class="weui_panel_hd"><?=$this_date?></div>
		<?php endif;?>
        <div class="weui_cell"  >
         
          <div class="weui_cell_bd weui_cell_primary" style="font-size:13px;">
            <p><?=(balance_type($value['balance_type']))?></p>
            <p><?=date('m-d H:i:s', $value['post_time'])?></p>
          </div>
		   <div class="weui_cell_ft">
            <p>+ <?=($value['user_money']/100)?></p>
            <p><?=($value['user_current_balance']/100)?></p>
          </div>
          
        </div>
		<?php endforeach; ?>
		<script> var current_date = '<?=$current_date?>'; </script>
	<?php else :?>
			<h3 calss="no-date">暂无记录</h3>
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
	 
 
	 