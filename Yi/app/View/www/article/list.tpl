 <style>
.filter{
margin: 24px 0 6px;
height: 102px;
}
.filter dl{
border-bottom:1px solid #e3e3e3;
padding:2px 12px;
}
.filter dt{
width: 100px;
font-size: 16px;
padding:2px 22px;
float:left;
}
.filter dt, .filter dl, .filter dd{
height: 30px;
line-height: 30px;
}
.filter dd span{
float:left;
white-space: nowrap;
zoom: 1;
color: #005aa0;
}
.filter .filter_tag span{
font-size:12px;
height: 20px;
line-height: 20px;
margin-top:6px;
}
.filter .filter_tag span input {
position:relative;
margin:0 4px 0 0;
float: left;
margin-top: 4px;
*margin:0;
*top:1px;
}
.filter .filter_tag span i {
display: block;
display:none;
float: left;
width: 10px;
height: 10px;
border: 1px solid silver;
margin-right: 6px;
margin-top: 4px;
}
.filter_tag span, .filter_order1 span{
margin:0 10px;
}
.filter .filter_tag{
border-top:1px solid #e3e3e3;
}
.filter .filter_order{
float: right;
overflow:hidden;
border-bottom:none;
padding:2px 12px;
}
.filter_order span{
border-left:1px solid #e3e3e3;
padding: 2px 14px;
}

.filter_order .selected{
background: #e43c3f;
color: white;
}

.page{
width:300px;
margin:0 auto;
padding:20px;
}

.page span{
border: 1px solid #e3e3e3;
padding: 6px 14px;
margin-left: -4px;
}


 
.diy_select{height:28px;line-height:28px;width:130px;position:relative;font-size:12px;margin-left: -1px;;background:#fff;color:#000;float:left;}
.diy_select_btn,.diy_select_txt{float:left;height:100%;}
.diy_select,.diy_select_list{border:1px solid #e3e3e3;}
.diy_select_txt{width:100px;}
.diy_select_txt,.diy_select_list li{text-indent:10px;overflow:hidden}
.diy_select_txt,.diy_select_list {white-space: nowrap;zoom: 1;color: #005aa0;}
.diy_select_btn{width:28px;background:url(rec.gif) no-repeat center}
.diy_select_list{position:absolute;top:34px;left:-1px;z-index:88888;border-top:none;width:100%;display:none;_top:35px;background:white;}
.diy_select_list li{list-style:none;height:28px;line-height:28px;cursor:default;_background:#fff;float:left;width:130px;}
.diy_select_list li.focus{background:#3399FF;color:#fff}
 
 </style>


	<div class="breadcrumb">
		<span style="margin-left:28px;">文案＞</span><label class="subtitle">文案列表</label>
	</div>
	<div class="filter">
		<dl  class="filter_tag">
			<dt>标签：</dt>
			<dd>
			<?php foreach(\article_tags() as $key => $value) : ?>
			<span>
				<input type="radio" name="tag" id="ele<?=$key;?>" <?php if(in_array($key, $result['params']['tag'])) echo ' checked="true" ';?>>
				<label for="ele<?=$key;?>"><?=$value;?></label>
			</span>
			<?php endforeach ?>
			</dd>
		</dl>
		<dl class="filter_city" style="overflow:visible;">
			<dt>区域：</dt>
			<dd style="overflow:visible;" id="region">
				<input type="hidden" id="region_selected" class="diy_select_input" value="0"/>
				<div class="diy_select" >
					<!--<input type="hidden" name="province" class="diy_select_input"/>-->
					<i class="diy_select_txt">全国</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:392px;">
						<li sid="0" rid="0">全国</li>
					</ul>
				</div>

				<div class="diy_select" >
					<!--<input type="hidden" name="city" class="diy_select_input"/>-->
					<i class="diy_select_txt">--不限--</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:261px;">	
						<li sid="0">--不限--</li>
					</ul>
				</div>
			
				<div class="diy_select">
					<!--<input type="hidden" name="" class="diy_select_input"/>-->
					<i class="diy_select_txt">--不限--</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" style="width:261px;">	 
					<li sid="0">--不限--</li>
					</ul>
				</div>
				<div class="diy_select">
					<!--<input type="hidden" name="" class="diy_select_input"/>-->
					<i class="diy_select_txt">--不限--</i>
					<span class="diy_select_btn"></span>
					<ul class="diy_select_list" >
					<li sid="0">--不限--</li>
					</ul>
				</div>
			</dd>
		</dl>
		<dl class="filter_order">
			<dt class="hide">排序</dt>
			<dd>
			 <span class="selected">综合排序</span><span>时间升序</span><span>时间降序</span><span>浏览量</span>　
			 </dd>
		</dl>
		
	</div>
	<ul id="article_list">
		<li>	
			<span class="number"> 编号 </span>
			<span class="tag"> 标签</span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">标题</a>
			<span class="view_number"> 阅读次数</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
		<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 1 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 10 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 19 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
			<li>	
			<span class="number"> 18 </span>
			<span class="tag"> 本地商圈 </span>
			<a href="/article/123456123456.html" target="_blank" class="article_list_title">美国西北车厘子樱桃1kg（果径约26mm)进口新鲜水果</a>
			<span class="view_number"> 1777</span>
			<a href="javascript:;" class="collect">收藏</a>
		</li>  
		 			 
	</ul>
	<div class="page">
		<span>1</span>
		<span>2</span>
		<span>3</span>
		<span>4</span>
		<span>5</span>
	</div>
	
 <script>
 
 var lis = _$("article_list").getElementsByTagName("li");
 
 for(i=0; i<lis.length; i=i+2){
	lis[i].style.background = "#f2f2f2";
 }
 
 </script>
 
 
	
<script type="text/javascript">
var province = [];
var region = [];

function diy_select(){
	this.init.apply(this,arguments)
};
diy_select.prototype={

	 init:function(opt) {
	 
		this.l=document.getElementById(opt.TTid).getElementsByTagName('ul');//容器
		this.lengths=this.l.length;
		var THAT = this;
		// 省初始化
		JSONP.get( 'http://www.' + site_domain + '/region/id/0.html', {}, function(data){
			console.log(data.statusCode);
			if(data.statusCode == 0 ){
				for(var i=0; i<data.body[0].length; i++) {
				　var li = document.createElement("li");
	　　　		　li.setAttribute("sid", data.body[0][i].id);
			　　　li.innerHTML = data.body[0][i].name;
			　　　THAT.l[0].appendChild(li);
				}
			}
		 }); 

		for (var i=0; i<this.lengths; i++) {
			this.l[i].index=i;
			this.l[i].style.display ='none';
		}
		Min.event.bind(opt.TTid,'mouseover',{handler:function(e){
			Min.css.addClass(THAT.TTFcous, e.delegateTarget);
		},selector:'li'});
		
		Min.event.bind(opt.TTid,'mouseout',{handler:function(e){
			Min.css.removeClass(THAT.TTFcous, e.delegateTarget);
		},selector:'li'});
		
		Min.event.bind(opt.TTid,'click',{handler:function(e){
		
			var index=this.parentNode.index;//获得列表
			var key = this.getAttribute("sid");
			var p = this.parentNode.parentNode;
			//var origin = p.getElementsByTagName('input')[0].value;
			var origin = _$('region_selected').value;
			if (origin == key) return;
			
			if (key == 0) {
				 rkey = this.getAttribute("rid");
				if(rkey === null || rkey === undefined) return;
				_$('region_selected').value = rkey;
			} else {
				_$('region_selected').value = key;
			}
			
			
			
			p.getElementsByTagName('i')[0].innerHTML =this.innerHTML.replace(/^\s+/,'').replace(/\s+&/,'');
			this.parentNode.style.display='none';
			
			Min.obj.each(THAT.l,function(a,k){
				if(k > index){
					a.innerHTML = '';
					var li = document.createElement("li");
	　　　			　li.setAttribute("sid", 0);
					  var i = Min.dom.pre(a,'I');
					  //ipt = Min.dom.pre(i);
					  //ipt.value = 0;
					  li.innerHTML = i.innerHTML = '--不限--';
					  a.appendChild(li);  
				}
			});
			
			if(key > 0){
				if(!region[key]){
					JSONP.get( 'http://www.' + site_domain + '/region/id/'+key+'.html', {}, function(data){
						if(data.statusCode == 0 ){
							region[key]= data.body[key];	
							THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key)
							if(key > 0 &&  index < THAT.lengths-1 ){
				
								for(var i=0; i < region[key].length; i++){
								　var li = document.createElement("li");
					　　　		　li.setAttribute("sid", region[key][i].id);
							　　　li.innerHTML = region[key][i].name;
								  THAT.l[index+1].appendChild(li);
								}	
							}
						}
					}); 
					return;
				} 
			} else {
				return;
			}
			
			if(key > 0 && index < THAT.lengths-1 ){
				THAT.l[index+1].getElementsByTagName('li')[0].setAttribute('rid', key);
				for(var i=0; i < region[key].length; i++){
				　var li = document.createElement("li");
	　　　		　li.setAttribute("sid", region[key][i].id);
			　　　li.innerHTML = region[key][i].name;
				  THAT.l[index+1].appendChild(li);
				}	
			}
			 
		},selector:'li'});
		
		Min.event.bind(document,'click',function() {
			Min.obj.each(THAT.l,function(a){
				a.style.display='none';
			});
		});
		Min.event.bind(opt.TTid,'click',{handler:function(e){
			 
			var next = Min.dom.next(this);
			if(next.tagName.toUpperCase() != 'UL'){
				next = Min.dom.next(next);
			}
			next.style.display =  next.style.display == 'none'? 'block':'none';
			var index = next.index;
			
			Min.obj.each(THAT.l,function(a,key){
				if(key != index) a.style.display = 'none';
			});

			Min.event.stopPropagation(e); 	
			
		},selector:'i,span'});
	 }

}
new diy_select({ 
	TTid :'region',
	TTFcous:'focus'
});
</script>