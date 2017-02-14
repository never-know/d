<div class="leftbar">
	<div class="menu_box" id="menuBar">
		<dl class="menu no_extra">    
			<dd class="menu_item" id="menu_homepage"><a data-id="10007" href="/">首页</a></dd>				 
		</dl>
		<dl class="menu ">   
			<dt class="menu_title"><i class="icon_menu"></i>文字</dt> 
			<dd class="menu_item" id="menu_article"><a data-id="10012" href="/article/list.html">文字列表</a></dd>
			<dd class="menu_item" id="menu_article_edit"><a data-id="10014" href="/article/edit.html">添加文字</a></dd>
			<dd class="menu_item" id="menu_article_collect"><a data-id="10014" href="/article/collect.html">文字收藏</a></dd>
		</dl>
		<dl class="menu">    
			<dt class="menu_title"> <i class="icon_menu" ></i>收益管理</dt>
	  
			<dd class="menu_item" id="menu_balance_list"><a data-id="10007" href="">资金日志</a></dd>				 
		</dl>

		<dl class="menu ">   
			<dt class="menu_title"><i class="icon_menu"></i>个人中心</dt> 
			<dd class="menu_item" id="menu_account"><a data-id="10012" href="">帐户管理 </a></dd>
			<dd class="menu_item" id="menu_subline"><a data-id="10014" href="">下线列表</a></dd>
		</dl>
	 
		<dl class="menu ">   
			<dt class="menu_title"><i class="icon_menu"></i>管理</dt> 
			<dd class="menu_item "><a data-id="10012" href="">消息管理</a></dd>
	 
			<dd class="menu_item "><a data-id="10014" href="">投诉建议</a></dd>
		</dl>
	</div>
</div>
<script>
Min.css.addClass('active', _$('menu_' + <?=safe_json_encode($result['menu_active']);?>));
</script>
<div style="position:absolute;width:1px;height:100%;background-color:#e7e7eb;left:190px;"></div>