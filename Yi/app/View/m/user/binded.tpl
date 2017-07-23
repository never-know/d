 
	<div class="weui_msg" style="padding-top:12px;">
       
      <div class="weui_text_area" style="background: white;padding: 20px;">
		<p class="weui_msg_desc">已绑定尾号<?=substr(session_get('user')['phone'], 7);?>的手机号码 </p>
		 
      </div>
	  <a class="weui_text_area" style="background: white;padding: 20px;display:block;" href="/bind/change.html" >
		 
		<p class="weui_msg_desc">更换手机号码</p>
        
      </a>
      <div class="weui_extra_area" style="position:relative;">
        <a href="/"><span id="daojishi">7</span>秒后为您跳转首页</a>
      </div>
    </div>
	<script> 
		var t=100;
		var a=setInterval(daojishi,1000);//1000毫秒
		function daojishi(){
			t--;
			document.getElementById('daojishi').innerHTML  = t;
			if(t==0){
				clearInterval(a);
				window.location.href = '/';
			}
		}

	</script>
	