	<div class="weui_msg" style="padding-top:0;">
       
      <div class="weui_text_area" style="background: white;padding: 20px;">
		<p class="weui_msg_desc">帐户余额</p>
        <h1  style="color:red;padding-top:4px;"><strong >￥</strong><?=$result['balance']?></h1>
      </div>
      <div class="weui_opr_area">
        <p class="weui_btn_area">
          <a href="/draw/withdraw.html" class="weui_btn weui_btn_primary">提现</a>
          <a href="/balance/records.html"  class="weui_btn weui_btn_default">资金明细</a>
        </p>
      </div>
      <div class="weui_extra_area" style="position:relative;">
        <a href="/draw/wdlog.html">提现记录</a>
      </div>
    </div>
