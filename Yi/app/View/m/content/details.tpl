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
</head>

<body ontouchstart style="background-color: #f8f8f8;background-color:#f1f1f1;padding:30px;">

	<div class="weui_tab">
		<div class="weui_tab_bd">	
		<img src="https://api.leduika.com/assets/logo/20170620103512.png"  width="90px"/>
		<?=$result['content']?>
		</div>
		<img src="https://m.anyitime.com/public/images/qrcode.png"  width="200px;" onerror="qrcodenotfound()"/>
	</div>	
</body>
<script>

function qrcodenotfound(){var img=event.srcElement;img.src="/public/images/qrcode.jpg"; img.onerror=null;} 
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
var location = window.location.pathname;
if (/^/[a-zA-Z0-9_\-]+\.html$/.test(location)) {
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
file_put_contents($filename, $content);
?>