<?php  ob_start(); ?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=0">
		<title><?=($result['content_title']?:' ');?></title>
		<?php if(!empty($result['content_description'])) : ?>
		<meta name="description" content="<?=$result['content_description'];?>">
		<?php endif;?>
		<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css"/>
		<style>p img{width:100%;vertical-align: top;}</style>
		<script>
		"use strict";var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t};!function(t){"function"==typeof define&&"object"===_typeof(define.amd)&&define.amd?define(t):"undefined"!=typeof module&&module.exports?module.exports=t():window.LazyloadImg=t()}(function(){function t(t){var e=this;this.el="[data-src]",this.top=0,this.right=0,this.bottom=0,this.left=0,this.before=function(){},this.load=function(t){},this.error=function(t){},this.qriginal=!1,this.monitorEvent=["DOMContentLoaded","load","click","touchstart","touchend","haschange","online","pageshow","popstate","resize","storage","mousewheel","scroll"];for(var n in t)this[n]=t[n];this.init=function(){e.createStyle(),e.src=function(){return/\[data-([a-z]+)\]$/.exec(e.el)[1]||"src"}(),e.start()},this.createStyle=function(){var t=document.getElementById("LazyloadImg-style");return t?!1:(t=document.createElement("style"),t.id="LazyloadImg-style",t.type="text/css",t.innerHTML="                .LazyloadImg-qriginal {                    -webkit-transition: none!important;                    -moz-transition: none!important;                    -o-transition: none!important;                    transition: none!important;                    background-size: cover!important;                    background-position: center center!important;                    background-repeat: no-repeat!important;                }            ",void document.querySelector("head").appendChild(t))},this.start=function(){for(var t=e.monitorEvent,n=0;n<t.length;n++)window.addEventListener(t[n],e.eachDOM,!1);e.eachDOM()},this.eachDOM=function(){for(var t=document.querySelectorAll(e.el),n=[],o=0;o<t.length;o++)e.testMeet(t[o])===!0&&n.push(t[o]);for(var r=0;r<n.length;r++)e.loadImg(n[r])},this.testMeet=function(t){var n=t.getBoundingClientRect(),o=t.offsetWidth,r=t.offsetHeight,i=window.innerWidth,a=window.innerHeight,s=!(n.right-e.left<=0&&n.left+o-e.left<=0||n.left+e.right>=i&&n.right+e.right>=o+i),c=!(n.bottom-e.top<=0&&n.top+r-e.top<=0||n.top+e.bottom>=a&&n.bottom+e.bottom>=r+a);return!(0==t.width||0==t.height||!s||!c)},this.loadImg=function(t){var n=t.dataset[e.src],o=new Image;o.src=n,e.before.call(e,t),o.addEventListener("load",function(){return e.qriginal?(t.src=e.getTransparent(t.src,t.width,t.height),t.className+=" LazyloadImg-qriginal",t.style.backgroundImage="url("+o.src+")"):t.src=o.src,delete t.dataset[e.src],e.load.call(e,t)},!1),o.addEventListener("error",function(){return e.error.call(e,t)},!1)},this.getTransparent=function(){var t=document.createElement("canvas");t.getContext("2d").globalAlpha=0;var e={};return function(n,o,r){if(e[n])return e[n];t.width=o,t.height=r;var i=t.toDataURL("image/png");return e[n]=i,i}}(),this.end=function(){for(var t=e.monitorEvent,n=0;n<t.length;n++)window.removeEventListener(t[n],e.eachDOM,!1)},this.init()}return t});
		</script>
	</head>

<body ontouchstart style="background-color: #f8f8f8;background-color:#f1f1f1;padding:30px;">

	<div class="weui_tab">
		<div class="weui_tab_bd">	
			<img src="https://api.leduika.com/assets/logo/20170620103512.png"  width="90px"/>
			<?=$result['content']?>
		</div>
	 
		<img src="https://m.anyitime.com/public/images/qrcode.jpg" data-src="https://m.anyitime.com/public/images/qrcode.png" width="200px;" id="qrcode"/>

	</div>	
</body>
<script>
/* 进入可视区
$(window).on("scroll",function(){
    console.log(( $(".box").offset().top - $(this).scrollTop() ) > $(this).height());
});
*/
var lazyloadImg = new LazyloadImg({el:'#qrcode [data-src]',top: 50,qriginal:false,load:function(el){el.style.cssText += '-webkit-animation: fadeIn 01s ease 0.2s 1 both;animation: fadeIn 1s ease 0.2s 1 both;';},error: function(el) {el.src="/public/images/qrcode.jpg"}});
 
function loadScript(url, callback) {
  var script = document.createElement("script");
  script.type = "text/javascript";
  if(typeof(callback) != "undefined"){
    if (script.readyState) {
      script.onreadystatechange = function () {
        if (script.readyState == "loaded" || script.readyState == "complete") {
          script.onreadystatechange = null;
          callback();
        }
      };
    } else {
      script.onload = function () {
        callback();
      };
    }
  }
  script.src = url;
  document.body.appendChild(script);
}
var content_title = <?=safe_json_encode($result['content_title'])?>, content_description=<?=safe_json_encode($result['content_description'])?>, content_icon=<?=safe_json_encode($result['content_icon'])?>;
var l = window.location.pathname;

if (/^\/content\/[a-zA-Z0-9_\-]+\.html$/.test(l)) {
loadScript("//res.wx.qq.com/open/js/jweixin-1.2.0.js", function(){
loadScript("/share/js.html");
});
}

</script>
</html>
<?php
 $content=  ob_get_contents();//从缓存中获取内容
 ob_end_flush();//关闭缓存并清空
 /***缓存结束***/
 $filename = CACHE_PATH .'/content/' . implode('/', str_split($result['id'], 2)) . '.html';
 $dir = dirname($filename);
 if (!is_dir($dir)) {
	if (!mkdir($dir, 0755, true)) {
		return false;
	}
 }
file_put_contents($filename, $content);
?>