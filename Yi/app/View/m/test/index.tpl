<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		
		<title><?=$result['meta']['title'];?></title>
		<?php if(!empty($result['meta']['description'])) : ?>
		<meta name="description" content="<?=$result['meta']['description'];?>">
		<?php endif;?>
		
		<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css"/>
		<link rel="stylesheet" href="/public/css/jquery-weui.css"/>
		<link rel="stylesheet" href="/public/css/myi.css?v=1"/>
		<script src="/public/js/m/zepto.min.js"></script>
		<script src="/public/js/m/fastclick.js"></script>
		 
		<style>
			body, html {height: 100%;-webkit-tap-highlight-color: transparent;}
		</style>
	</head>

<body ontouchstart style="background-color: #f8f8f8;">

	<div class="weui_tab">
		<div class="weui_tab_bd">	
		
		
		<!---------------------------->

 
		<div class="weui_panel weui_panel_access_new">
			<div class="weui_panel_hd">图文组合列表</div>
			<div class="weui_panel_bd" id="content_load">
				 
				<div class="weui_panel_bd">
				
				<div class="weui_media_box weui_media_text">
				  <h4 class="weui_media_title">标题一</h4>
				  <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
				  <ul class="weui_media_info">
					<li class="weui_media_info_meta">文字来源</li>
					<li class="weui_media_info_meta">时间</li>
					<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
				  </ul>
				</div>
			  </div>
			  
			  <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
				 <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta">文字来源</li>
							<li class="weui_media_info_meta">时间</li>
							<li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="/public/images/avatar.png" alt="">
                    </div>
                </a>
				
			</div>
		</div>
	<div class="weui_navbar">
	<div class="weui_navbar_item" id="d2">
		全部分类
	</div>
	<div class="weui_navbar_item" style="padding-left:10px;white-space:nowrap; text-overflow:ellipsis;max-width:60%;    overflow: hidden;" id="d3">
		西湖区转塘街道西湖区转塘街道转塘街道转塘街道
	</div>
	</div>
 
	
	<div class="weui-infinite-scroll">
	  <div class="infinite-preloader"></div>
	  正在加载... 
	</div>
 
<script> 



 
    
 
$(document).on("click", "#d3", function() {
		  
		 
        $.actions({
          
          onClose: function() {
            console.log("close");
          },
          actions: [
            {
              text: "发布",
              className: "color-primary",
              onClick: function() {
                $.alert("发布成功");
              }
            },
            {
              text: "编辑",
              className: "color-warning",
              onClick: function() {
                $.alert("你选择了“编辑”");
              }
            },
            {
              text: "删除",
              className: 'color-danger',
              onClick: function() {
                $.alert("你选择了“删除”");
              }
            }
          ]
        });
      });
$(document).on("click", "#d2", function() {
		  
		 
        $.actions({
          
          onClose: function() {
            console.log("close");
          },
          actions: [
            {
              text: "发布",
              className: "color-primary",
              onClick: function() {
                $.alert("发布成功");
              }
            },
            {
              text: "编辑",
              className: "color-warning",
              onClick: function() {
                $.alert("你选择了“编辑”");
              }
            },
            {
              text: "删除",
              className: 'color-danger',
              onClick: function() {
                $.alert("你选择了“删除”");
              }
            }
          ]
        });
      }); 	  
	  </script>
	  
	   <script>
      var loading = false;
      $('.weui_tab_bd').infinite(250).on("infinite", function() {
        if(loading) return;
        loading = true;
        setTimeout(function() {
          $("#content_load").append("<p>《世界著名计算机教材精选·人工智能:一种现代的方法(第3版)》英文版有1100多页，教学内容非常丰富，不但涵盖了人工智能基础、问题求解、知识推理与规划等经典内容，而且还包括不确定知识与推理、机器学习、通讯感知与行动等专门知识的介绍。目前我们为本科生开设的学科基础必修课“人工智能导论”主要介绍其中的经典内容，而研究生必修的核心课程“人工智能原理”主要关注其中的专门知识。其实《世界著名计算机教材精选·人工智能:一种现代的方法(第3版)》也适合希望提高自身计算系统设计水平的广大应用计算技术的社会公众，对参加信息学奥林匹克竞赛和ACM程序设计竞赛的选手及其教练员也有一定的参考作用。</p>");
          loading = false;
        }, 1000);
      });
    </script>
  <!---------------------->
  
  
  	</div>
		<div class="weui_tabbar">
			<?php if (!empty($result['meta']['menu_active'])) : ?>
			<a href="javascript:;" class="weui_tabbar_item weui_bar_item_on">
			<?php  else : ?>
			<a href="/" class="weui_tabbar_item" id="index">
			<?php endif;?>
				<div class="weui_tabbar_icon">
					<svg class="icon" style="width: 24px; height: 24px;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="32260"><path d="M827.45856 554.52672h-618.496c-18.9952 0-34.304 13.41952-34.304 30.00832v299.19744c0 16.54272 15.34976 30.00832 34.304 30.00832h618.496c18.9952 0 34.304-13.41952 34.304-30.00832v-299.19744c0.00512-16.5888-15.34464-30.00832-34.304-30.00832z m-274.88768 314.42944c0 8.19712-7.59808 14.848-17.32608 14.848h-34.03776c-9.56416 0-17.32608-6.81984-17.32608-14.848v-269.64992c0-8.19712 7.61344-14.848 17.32608-14.848h34.03776c9.56416 0 17.32608 6.81472 17.32608 14.848v269.64992zM861.68576 344.97536h-235.9296l57.37472-13.98784c21.66784-0.93696 43.14624-7.81824 61.06624-20.89984 43.61216-31.88224 49.28-88.53504 12.71296-126.52032-36.608-37.9904-101.59616-42.94656-145.20832-11.06944-15.93344 11.63264-26.79808 26.58816-32.41472 42.72128l-60.34944 113.84832c-0.33792 0.38912-0.4352 0.85504-0.72704 1.25952-0.28672-0.40448-0.37888-0.8704-0.72704-1.25952L457.13408 215.22432c-5.61152-16.13312-16.47616-31.08864-32.4096-42.72128-43.61728-31.88224-108.60032-26.92608-145.20832 11.06432-36.56704 37.98528-30.89408 94.63808 12.71296 126.52032 17.92 13.0816 39.39328 19.968 61.06624 20.90496l57.37472 13.98784h-235.9296c-18.98496 0-34.44224 13.46048-34.44224 30.06464v119.4752c0 16.53248 15.42144 30.06976 34.44224 30.06976h686.94528c18.98496 0 34.44224-13.4656 34.44224-30.06976V375.04512c-0.00512-16.5632-15.42144-30.06976-34.44224-30.06976zM335.91808 276.7616l0.26624-0.27648a53.44256 53.44256 0 0 1-12.92288-6.92736c-21.81632-15.93344-24.64768-44.25728-6.3488-63.26272 18.304-19.03104 50.82112-21.49888 72.61184-5.53984 5.01248 3.68128 9.05728 8.00768 12.02688 12.72832l0.08192-0.08704 1.2544 2.37056c0.2048 0.37376 0.40448 0.75264 0.62976 1.152L455.5264 315.35104l-119.60832-38.58944z m216.6528 202.96704c0 8.2432-7.59808 14.91968-17.32608 14.91968h-34.03776c-9.56416 0-17.32608-6.77888-17.32608-14.91968V389.8368c0-8.2432 7.61344-14.9248 17.32608-14.9248h34.03776c9.56416 0 17.32608 6.784 17.32608 14.9248v89.89184z m80.35328-262.8096l0.6144-1.152 1.25952-2.37056 0.08192 0.09216a47.16544 47.16544 0 0 1 12.03712-12.73344c21.78048-15.95904 54.2976-13.4912 72.60672 5.53984 18.29888 19.00032 15.4624 47.32928-6.35904 63.2576A53.05856 53.05856 0 0 1 700.24704 276.48l0.27136 0.2816-119.6032 38.58944 52.00896-98.432z" p-id="32261"></path></svg>
				</div>
				<p class="weui_tabbar_label">发现</p>
			</a>
			<?php if (empty($result['meta']['menu_active'])) : ?>
			<a href="javascript:;" class="weui_tabbar_item weui_bar_item_on">
			<?php  else : ?>
			<a href="/user/line.html" class="weui_tabbar_item">
			<?php endif;?>
				<div class="weui_tabbar_icon">
				 
					<svg class="icon" style="width: 24px; height: 24px;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1567"><path d="M936.96 834.048c0-13.824-0.512-28.16-4.096-42.496-9.216-34.304-35.328-57.344-59.904-76.288-33.28-26.112-72.192-48.128-126.464-71.168-26.112-11.264-53.76-22.016-84.992-32.768-13.312-4.608-27.136-18.944-35.328-36.864-6.656-13.824-8.192-28.16-5.12-37.888l1.536-1.536c36.864-44.544 66.56-84.48 80.384-153.6 11.264-55.296 9.728-107.52-4.608-155.648-22.016-73.216-69.632-120.832-137.728-137.728-10.24-2.56-20.48-4.608-30.208-5.632V81.92h-41.472v1.024c-9.728 1.024-19.968 3.072-30.208 5.632C390.656 105.472 343.04 153.088 321.024 226.304c-14.336 47.616-15.872 100.352-4.608 155.648 13.824 69.12 43.52 108.544 80.384 153.6l1.536 1.536c3.072 9.728 1.024 24.064-5.12 37.888-8.192 17.92-22.016 32.256-35.328 36.864-31.232 10.752-58.88 21.504-84.992 32.768-54.272 23.552-93.696 45.568-126.464 71.168-25.088 19.968-50.688 41.984-59.904 76.288-4.096 14.336-4.096 28.672-4.096 42.496v6.656c-0.512 25.6 10.24 48.64 30.208 65.536 12.288 10.24 36.864 24.576 39.936 26.112l4.608 2.56h705.536l4.608-2.56c2.56-1.536 27.648-15.872 39.936-26.112 19.968-16.384 30.72-39.936 30.208-65.536l-0.512-7.168z" p-id="2400"></path></svg>
					 
				</div>
				<p class="weui_tabbar_label">我的</p>
			</a>       
		</div>
		 
	</div>		  
   <script>
 
$('.weui_tabbar').tab({
    defaultIndex: <?=(empty($result['meta']['menu_active'])?1:0);?>,
    activeClass:'weui_bar_item_on',
    onToggle:function(index){
		console.log(index);
    }
});
</script>
	 
</body>
</html>

	
	