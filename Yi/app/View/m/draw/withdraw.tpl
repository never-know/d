<div>
	<form id="form">
		<style>.weui_label{width:90px} .weui_btn{padding-top:4px}</style>
		<div class="weui_cells weui_cells_form">
		  <div class="weui_cell">
			<div class="weui_cell_hd"><label class="weui_label">金额</label></div>
			<div class="weui_cell_bd weui_cell_primary">
			  <input class="weui_input" type="tel" placeholder="请输入金额">
			</div>
		  </div>

		  <div class="weui_cell weui_cell_select" id="account_select">
			<div class="weui_cell_hd"><label class="weui_label">转出账户</label></div>
			 <div class="weui_cell_bd weui_cell_primary">
			  <input type="hidden"  id="account" name="account" value="" >
			  <input class="weui_input" type="text" id ="account_name" placeholder="选择账户" disabled="disabled">
			</div>
		  </div>
		</div>
		<div class="weui_btn_area" id="abc" rel="0">
			<a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary" >提交</a>
		</div>
	</form>
</div>
<script> 
	$(document).on("click", "#account_select", function() {
		$.actions({
			title: "选择账号",
			onClose: function() {
				console.log("close");
			},
			actions: [
			<?php if (!empty($result['body'])) : ?>
			<?php foreach ($result['body'] as $value) : ?>
				{
				text: "<?=withdraw_account_info($value);?>",
				className: "color-primary",
				onClick: function() {
					$('#account').value(<?=$value['account_type'];?>);
				}
				},
			<?php endforeach; endif; ?>
				 /*
				{
				  text: "微信",
				  className: "color-warning",
				  onClick: function() {
					$.alert("你选择了“编辑”");
				  }
				},
				{
				  text: "银行卡",
				  className: 'color-danger',
				  onClick: function() {
					$.alert("你选择了“删除”");
				  }
				},
				*/
				{
				  text: "添加新帐号",
				  className: 'color-danger',
				  onClick: function() {
					window.location.href = "https://m.anyitime.com/user/account.html?return=1";
				  }
				}
			]
		});
	});
 
	$(document).on("click", "#abc", function() {
		var rel = $(this).attr("rel");
		if (ref == 1) return;
		$(this).attr("rel", 1);
	}
</script>
  