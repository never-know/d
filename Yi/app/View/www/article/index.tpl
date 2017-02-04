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


.diy_select{height:28px;width:150px;position:relative;font-size:12px;margin-left: -1px;;background:#fff;color:#000;float:left;}
.diy_select_btn,.diy_select_txt{float:left;height:100%;line-height:28px}
.diy_select,.diy_select_list{border:1px solid #e3e3e3;}
.diy_select_txt{width:120px;}
.diy_select_txt,.diy_select_list li{text-indent:10px;overflow:hidden}
.diy_select_txt,.diy_select_list {white-space: nowrap;zoom: 1;color: #005aa0;}
.diy_select_btn{width:28px;background:url(rec.gif) no-repeat center}
.diy_select_list{position:absolute;top:28px;left:-1px;z-index:88888;border-top:none;width:100%;display:none;_top:29px;background:white;}
.diy_select_list li{list-style:none;height:25px;line-height:25px;cursor:default;_background:#fff}
.diy_select_list li.focus{background:#3399FF;color:#fff}
 
 </style>


	<div class="breadcrumb">
		<span style="margin-left:28px;">文案＞</span><label class="subtitle">文案列表</label>
	</div>
	<div class="filter">
		<dl  class="filter_tag">
			<dt>标签：</dt>
			<dd>
			<span><input type="checkbox" name="tag[]" id="ele"><i></i><label for="ele">电子产品</label></span>
			<span><input type="checkbox" name="tag[]" id="ele1"><i></i><label for="ele1">吃喝玩乐</label></span>
			<span><input type="checkbox" name="tag[]" id="ele2"><i></i><label for="ele2">休闲</label></span>
			<span><input type="checkbox" name="tag[]" id="ele3"><i></i><label for="ele3">转塘家园</label></span>
			</dd>
		</dl>
		<dl class="filter_city">
			<dt>区域：</dt>
			<dd>
				<div class="diy_select">
					<input type="hidden" name="" class="diy_select_input"/>
					<div class="diy_select_txt">--请选择--</div>
					<div class="diy_select_btn"></div>
					<ul class="diy_select_list">
						<li>Javascript</li>
						<li>Html</li>
						<li>Css</li>
						<li>Php</li>
						<li>Jquery</li>
					</ul>
				</div>

				<div class="diy_select">
					<input type="hidden" name="" class="diy_select_input"/>
					<div class="diy_select_txt">--请选择--</div>
					<div class="diy_select_btn"></div>
					<ul class="diy_select_list">
						<li>Javascript</li>
						<li>Html</li>
						<li>Css</li>
						<li>Php</li>
						<li>Jquery</li>
					</ul>
				</div>
			
				<div class="diy_select">
					<input type="hidden" name="" class="diy_select_input"/>
					<div class="diy_select_txt">--请选择--</div>
					<div class="diy_select_btn"></div>
					<ul class="diy_select_list">
						<li>Javascript</li>
						<li>Html</li>
						<li>Css</li>
						<li>Php</li>
						<li>Jquery</li>
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
function diy_select(){this.init.apply(this,arguments)};
diy_select.prototype={
	 init:function(opt)
	 {
		this.setOpts(opt);
		this.o=this.getByClass(this.opt.TTContainer,document,'div');//容器
		this.b=this.getByClass(this.opt.TTDiy_select_btn);//按钮
		this.t=this.getByClass(this.opt.TTDiy_select_txt);//显示
		this.l=this.getByClass(this.opt.TTDiv_select_list);//列表容器
		this.ipt=this.getByClass(this.opt.TTDiy_select_input);//列表容器
		this.lengths=this.o.length;
		this.showSelect();
	 },
	 addClass:function(o,s)//添加class
	 {
		o.className = o.className ? o.className+' '+s:s;
	 },
	 removeClass:function(o,st)//删除class
	 {
		var reg=new RegExp('\\b'+st+'\\b');
		o.className=o.className ? o.className.replace(reg,''):'';
	 },
	 addEvent:function(o,t,fn)//注册事件
	 {
		return o.addEventListener ? o.addEventListener(t,fn,false):o.attachEvent('on'+t,fn);
	 },
	 showSelect:function()//显示下拉框列表
	 {
		var This=this;
		var iNow=0;
		this.addEvent(document,'click',function(){
			 for(var i=0;i<This.lengths;i++)
			 {
				This.l[i].style.display='none';
			 }
		})
		for(var i=0;i<this.lengths;i++)
		{
			this.l[i].index=this.b[i].index=this.t[i].index=i;
			this.t[i].onclick=this.b[i].onclick=function(ev)  
			{
				var e=window.event || ev;
				var index=this.index;
				This.item=This.l[index].getElementsByTagName('li');

				This.l[index].style.display= This.l[index].style.display=='block' ? 'none' :'block';
				for(var j=0;j<This.lengths;j++)
				{
					if(j!=index)
					{
						This.l[j].style.display='none';
					}
				}
				This.addClick(This.item);
				e.stopPropagation ? e.stopPropagation() : (e.cancelBubble=true); //阻止冒泡
			}
		}
	 },
	 addClick:function(o)//点击回调函数
	 {

		if(o.length>0)
		{
			var This=this;
			for(var i=0;i<o.length;i++)
			{
				o[i].onmouseover=function()
				{
					This.addClass(this,This.opt.TTFcous);
				}
				o[i].onmouseout=function()
				{
					This.removeClass(this,This.opt.TTFcous);
				}
				o[i].onclick=function()
				{
					var index=this.parentNode.index;//获得列表
					This.t[index].innerHTML=This.ipt[index].value=this.innerHTML.replace(/^\s+/,'').replace(/\s+&/,'');
					This.l[index].style.display='none';
				}
			}
		}
	 },
	 getByClass:function(s,p,t)//使用class获取元素
	 {
		var reg=new RegExp('\\b'+s+'\\b');
		var aResult=[];
		var aElement=(p||document).getElementsByTagName(t || '*');

		for(var i=0;i<aElement.length;i++)
		{
			if(reg.test(aElement[i].className))
			{
				aResult.push(aElement[i])
			}
		}
		return aResult;
	 },

	 setOpts:function(opt) //以下参数可以不设置  //设置参数
	 { 
		this.opt={
			 TTContainer:'diy_select',//控件的class
			 TTDiy_select_input:'diy_select_input',//用于提交表单的class
			 TTDiy_select_txt:'diy_select_txt',//diy_select用于显示当前选中内容的容器class
			 TTDiy_select_btn:'diy_select_btn',//diy_select的打开按钮
			 TTDiv_select_list:'diy_select_list',//要显示的下拉框内容列表class
			 TTFcous:'focus'//得到焦点时的class
		}
		for(var a in opt)  //赋值 ,请保持正确,没有准确判断的
		{
			this.opt[a]=opt[a] ? opt[a]:this.opt[a];
		}
	 }
}


var TTDiy_select=new diy_select({  //参数可选
	TTContainer:'diy_select',//控件的class
	TTDiy_select_input:'diy_select_input',//用于提交表单的class
	TTDiy_select_txt:'diy_select_txt',//diy_select用于显示当前选中内容的容器class
	TTDiy_select_btn:'diy_select_btn',//diy_select的打开按钮
	TTDiv_select_list:'diy_select_list',//要显示的下拉框内容列表class
	TTFcous:'focus'//得到焦点时的class
});//如同时使用多个时请保持各class一致.
</script>
  