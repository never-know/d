

 
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
    -webkit-flex: 2; flex: 2;" id="region" data-value="<?=$result['params']['selected_region']?:'110000,110100,110101'?>">
		<?=$result['params']['region_name']?:'全国'?>
	</div>
	
	<div class="weui_navbar_item open-popup" id="show-actions"   data-target="#half">
		<?=$result['params']['sub_region_name']?:'全国'?>
	</div>
	</div>
	<style>
	.weui-picker-overlay, .weui-picker-container { z-index: 100;}
	.weui_navbar_item {
		 
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
		margin-left: 6px;
		-webkit-box-flex: 2;
		-webkit-flex: 2;
		flex: 2;
	}
	
	</style>
	
	<div class="weui-infinite-scroll">

	 <?php if ($result['page']['total_page'] < 2) : ?>
	  - - - - - - - - 加载完成 - - - - - - - -
	</div>
	 
	 <?php else : ?>
	   <div class="infinite-preloader"></div>
	  正在加载... 
	</div>
	  
		<form id="list_form" onsubmit="return false" style="visibility:hidden;font-size:0;">
			<input type="hidden" name="region" id ="selected_region" value="<?=$result['params']['region']?>"/>
			<input type="hidden" name="sub_region" id ="selected_subregion" value="<?=$result['params']['sub_region']?>"/>
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
		
		var template2 = function(i, value){

			return ('<label class="weui_cell weui_check_label" for="weui-select-id-' + value  + '">'+        '<div class="weui_cell_bd weui_cell_primary">' +           
							'<p>'+ i + '</p>'+          
						'</div>'+          
						'<div class="weui_cell_ft">'+            
							'<input type="checkbox" class="weui_check" name="weui-select[]" id="weui-select-id-' + value  + '" value="' + value  + '"  data-title="'+ i + '" >'+           
							'<span class="weui_icon_checked"></span> '+   
						'</div> '+     
					'</label>');
		}
		
		page_load('/',   template);
	  
    </script>
	
	<?php endif; ?>
  
  
	 <div id="half" class="weui-popup-container popup-bottom" style="z-index:200">
      <div class="weui-popup-overlay"></div>
      <div class="weui-popup-modal" style="background:#fff;">
        <div class="toolbar" style="z-index:300">
          <div class="toolbar-inner">
            <a href="javascript:;" class="picker-button close-popup">确定</a>
            <h1 class="title">乡镇街道</h1>
          </div>
        </div>
        <div class="modal-content" style="min-height:200px;max-height:380px;overflow:scroll;background-">
          <div class="weui_grids" style="z-index:200">
				<div class="weui_cells weui_cells_checkbox" id="sub_region_checkbox"> 
					 <div class="weui-infinite-scroll">
						<div class="infinite-preloader"></div>  正在加载... 
					</div>
					 
			</div>
          </div>
        </div>
      </div>
    </div>
  
  
	<script src="/public/js/m/picker.js"></script>
	<script src="/public/js/m/select.js"></script>
	<script src="/public/js/m/citypicker.js"></script>
	<script src="/public/js/m/city-picker.min.js"></script>
	<script>
	
 
	
	var binded = false;
	var sub_regions = {};
	var current_sub_regions = {};
	var current_sub_region = '';
		
	$("#region").cityPicker({
		title: "选择广告投放区域",
		onChange: function (picker, values, displayValues) {
			console.log(values, displayValues);	 
		},
		onClose: function(obj){
			if (current_sub_region == obj.value[2] ) return;
			current_sub_region = obj.value[2];
			$('#selected_subregion').val(obj.value[2]);
			$('#selected_region').val(obj.value[2]);
			$('#region').data('value', obj.value.join(','));
			document.cookie = "selected_region=" + obj.value.join(',') +';path=/;'; 
			if (obj.displayValue[0] == '北京' || obj.displayValue[0] == '上海' || obj.displayValue[0] == '重庆' || obj.displayValue[0] == '天津' ) {
				obj.displayValue[0] = '';
			}
			$('#region').html(obj.displayValue.join(''));
			document.cookie = "region_title=" + obj.displayValue.join('')+';path=/;'; 
			if (sub_regions[obj.value[2]]) {
				current_sub_regions	 = sub_regions[obj.value[2]];
				$("#sub_region_checkbox").html($.map(current_sub_regions,template2).join(' '));
				
				$('#half').popup();
				return;
			} else {
				$('#sub_region_checkbox').html(' <div class="weui-infinite-scroll"><div class="infinite-preloader"></div>  正在加载...</div>');
				$.ajax({
					url:'/region/id/' + obj.value[2] +'.html', 
					type:'GET', 
					success: function(data){
						if (data.statusCode == 1 ) {
							current_sub_regions = sub_regions[obj.value[2]] = data.body[obj.value[2]];
							$("#sub_region_checkbox").html($.map(current_sub_regions,template2).join(' '));
							$('#half').popup();
							 //$("#show-actions").click();
							
						}  
					} 
				});
			}
 
		}
	});
 
	
  
	$("#show-actions222").select({
        title: "乡镇街道",
        multi: true,
        split:',',
        closeText:'完成',
        items: current_sub_regions,
        onChange: function(d) {
          //$.alert("你选择了"+d.values+d.titles);
        }
    });
	
	$(".close-picker").select({
        title: "乡镇街道",
        multi: true,
        split:',',
        closeText:'完成',
        items: current_sub_regions,
        onChange: function(d) {
          $.alert("你选择了"+d.values+d.titles);
        }
    });
	 
	
	  </script>
	  
	  
	  <script>
		$(document).on("open", ".weui-popup-modal", function() {
			console.log("open popup");
		}).on("close", ".weui-popup-modal", function() {
			console.log("close popup");
		
			// b.on("change",function(){var f=b.find("input:checked"),g=f.map(function(){return a(this).val()}),h=f.map(function(){return a(this).data("title")});d.updateInputValue(g,h),c.autoClose&&!c.multi&&a.closePicker()})
			var current_subregion = $('#selected_subregion').val();
			
			var f_checked = $('#half').find("input:checked");
			if (f_checked.length > 0) {
				var subregion_ids=f_checked.map(function(){return $(this).val()});
				var subregion_title=f_checked.map(function(){return $(this).data("title")});

				
			} else {
				var subregion_ids = ['0'];
				var subregion_title = ['未选择'];
			}
			
			if (subregion_ids.join(',') != current_subregion) {
				$('#selected_subregion').val(subregion_ids.join(','));
				$('#show-actions').html(subregion_title.join(','));
				document.cookie = "subregion_title=" + subregion_title.join(',')+';path=/;'; 
				document.cookie = "selected_subregion=" + subregion_ids.join(',') +';path=/;'; 
				window.location.href = "/?region=" + $('#selected_region').val() +"&sub_reigon=" +$('#selected_subregion').val();
				/*
				$.ajax({
					url:'/', 
					type:'GET', 
					data: {region:$('#selected_region').val(),sub_reigon:$('#selected_subregion').val()}
					success: function(data){
						if (data.statusCode == 1 ) {
							
							
						}  
					} 
				});
				*/
				
			}
      });
	  
    </script>