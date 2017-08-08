

 
<!--
	<input type="text" id="a" readonly="" />
	<header class="demos-header">
		<h1 class="demos-title">Popup</h1>
	</header>
	<div class="demos-content-padded">
		<a href="javascript:;" class="weui_btn weui_btn_primary open-popup" data-target="#full">显示全屏(默认)Popup</a>
		<a href="javascript:;" class="weui_btn weui_btn_primary open-popup" data-target="#half">显示底部的Popup</a>
	</div>

	<p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p>
	<p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p><p>1</p>
	<p>1</p><p>last line</p>
-->		
		<div class="weui_panel weui_panel_access_new">
			<div class="weui_panel_hd">图文组合列表</div>
			<div class="weui_panel_bd" id="list_loaded">
			<?php if (!empty($result['list'])) : ?>	 
			<?php foreach ($result['list'] as $key => $value) : ?>	 
				<?php if ($key%5 ==0) : ?>
			<div class="weui_media_box weui_media_text" onclick="window.location.href='/content/<?=$value['id_name']?>.html'">
			  <h4 class="weui_media_title"><?=$value['content_title']?></h4>
			  <p class="weui_media_desc"><?=$value['content_description']?></p>
			  <ul class="weui_media_info">
				<li class="weui_media_info_meta"><?=$value['region_name']?></li>
				<li class="weui_media_info_meta"><?=date('m-d', $value['create_time'])?></li>
				<li class="weui_media_info_meta weui_media_info_meta_extra"><?=($value['tag_name'])?></li>
			  </ul>
			</div>
			   <?php else : ?>
			  
			  <a href="/content/<?=$value['id_name']?>.html" class="weui_media_box weui_media_appmsg">
                   
                    <div class="weui_media_bd">
                        <p class="weui_media_desc"><?=$value['content_description']?></p>
						<ul class="weui_media_info">
							<li class="weui_media_info_meta"><?=$value['region_name']?></li>
							<li class="weui_media_info_meta"><?=date('m-d', $value['create_time'])?></li>
							<li class="weui_media_info_meta weui_media_info_meta_extra"><?=$value['tag_name']?></li>
					  </ul>
                    </div>
					 <div class="weui_media_hd">
                        <img class="weui_media_appmsg_thumb" src="<?=$value['content_icon']?>" alt="">
                    </div>
                </a>
				<?php 
				endif; 	
				endforeach; 
				else : ?>
					<h3 class="no-data">暂无记录</h3>
				<?php endif; ?>
				
				<!-- template 
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
                        <img class="weui_media_appmsg_thumb" src="/public/images/avater.png" alt="">
                    </div>
                </a>
				-->
				 
			</div>
		</div>
		
	<div class="weui_navbar">
	
	<div class="weui_navbar_item" style="padding-left:10px;white-space:nowrap; text-overflow:ellipsis;overflow: hidden;    -webkit-box-flex: 2;
    -webkit-flex: 2;
    flex: 2;" id="region" data-value="110000,110100,110101">
		西湖区转塘街道西湖区转塘街道转塘街道转塘街道
	</div>
	
	<div class="weui_navbar_item" id="show-actions">
		更多选择
	</div>
	</div>
	
	<div class="weui-infinite-scroll">

	 <?php if ($result['page']['total_page'] < 2) : ?>
	  - - - - - - - - 加载完成 - - - - - - - -
	</div>
	 
	 <?php else : ?>
	   <div class="infinite-preloader"></div>
	  正在加载... 
	</div>
	  
		<form id="list_form" onsubmit="return false" style="visibility:hidden;font-size:0;">
			<input type="hidden" name="region" value=""/>
			<input type="hidden" name="sub_region" value=""/>
			<input type="hidden" name="page" value="2" id="next_page"/>
		</form>
	  
	<script>
	    
		var template = function(i, value){
			var html = '';
			if (i%5 ==0) {
				html  = '<div class="weui_media_box weui_media_text" onclick="window.location.href=\'/content/' +value.id_name + 
				'.html\'">  <h4 class="weui_media_title">' + value.content_title + 
				'</h4> <p class="weui_media_desc">' + value.content_description + 
				'</p> <ul class="weui_media_info">' +
				'<li class="weui_media_info_meta">' + value.region_name + 
				'</li> <li class="weui_media_info_meta">' + new Date(value.create_time*1000).Format("mm-dd") +
				'</li> <li class="weui_media_info_meta weui_media_info_meta_extra">'+value.tag_name+
				'</li> </ul> </div>';
			
			} else {
			
				html  = '<a href="/content/' + value.id_name + 
				'.html" class="weui_media_box weui_media_appmsg">' +
				'<div class="weui_media_bd"> <p class="weui_media_desc">' + value.content_description +
				'</p><ul class="weui_media_info"> ' +
						'<li class="weui_media_info_meta">'+value.region_name + 
						'</li><li class="weui_media_info_meta">'+new Date(value.create_time*1000).Format("mm-dd")+
						'</li> <li class="weui_media_info_meta weui_media_info_meta_extra">'+value.tag_name+
						'</li>  </ul></div> ' +
						'<div class="weui_media_hd"> <img class="weui_media_appmsg_thumb" src="'+value.content_icon +
						'" alt=""></div> </a>';
			
			}
			return html;
		}
		
		page_load('/',   template);
	  
    </script>
	
	<?php endif; ?>
  
	<script src="/public/js/m/picker.js"></script>
	<script src="/public/js/m/citypicker.js"></script>
	<script src="/public/js/m/city-picker.min.js"></script>
	<script>
	   $("#region").cityPicker({
        title: "选择广告投放区域",
        onChange: function (picker, values, displayValues) {
          console.log(values, displayValues);
        }
      });
 
      $(document).on("click", "#show-actions", function() {
        $.actions({
          title: "选择操作",
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