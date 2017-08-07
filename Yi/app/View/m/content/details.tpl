<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=0">
		<title><?=($result['meta']['title']?:' ');?></title>
		<?php if(!empty($result['meta']['description'])) : ?>
		<meta name="description" content="<?=$result['meta']['description'];?>">
		<?php endif;?>
		<link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css"/>

	


</head>
<style>
p img{
    width:100%;
	vertical-align: top;
}
</style>

<?php if (!empty($result['js'])) : ?>
<script src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>	
<script>
   function parseJSON(c){if(!c){return null}if(window.JSON&&window.JSON.parse){return window.JSON.parse(c)}var a=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,e=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,b=/(?:^|:|,)(?:\s*\[)+/g,d=/^[\],:{}\s]*$/;if(d.test(c.replace(e,"@").replace(a,"]").replace(b,""))){return(new Function("return "+c))()}}function initXMLhttp(){var a;if(window.XMLHttpRequest){a=new XMLHttpRequest()}else{a=new ActiveXObject("Microsoft.XMLHTTP")}return a}function Ajax(c){if(!c.url){return}if(!c.type){return}if(!c.method){c.method=true}var a=initXMLhttp();a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(c.success){c.success(parseJSON(a.responseText),a.readyState)}}else{if(c.fail){c.fail()}}}};var b=[],l=c.data;if(typeof l==="string"){var f=String.prototype.split.call(l,"&");for(var g=0,e=f.length;g<e;g++){var h=f[g].split("=");b.push(encodeURIComponent(h[0])+"="+encodeURIComponent(h[1]))}}else{if(typeof l==="object"&&!(l instanceof String)){for(var d in l){var h=l[d];if(Object.prototype.toString.call(h)=="[object Array]"){for(var g=0,e=h.length;g<e;g++){b.push(encodeURIComponent(d)+"[]="+encodeURIComponent(h[g]))}}else{b.push(encodeURIComponent(d)+"="+encodeURIComponent(h))}}}}b=b.join("&");if(c.type=="GET"){a.open("GET",c.url+"?"+b,c.method);a.setRequestHeader("X-REQUESTED-WITH","xmlhttprequest");a.send()}if(c.type=="POST"){a.open("POST",c.url,c.method);a.setRequestHeader("Content-type","application/x-www-form-urlencoded");a.setRequestHeader("X-REQUESTED-WITH","xmlhttprequest");a.send(b)}};
  var token = "<?=get_token('m_content_share')?>";
  wx.config({
    appId: 	'<?=$result['js']['appId'];?>',
    timestamp: <?=$result['js']['timestamp']?>,
    nonceStr: '<?=$result['js']['nonceStr']?>',
    signature: '<?=$result['js']['signature']?>',
    jsApiList: [
      'onMenuShareTimeline', 'onMenuShareAppMessage'
    ]
  });
  
  wx.ready(function () {
   
	wx.onMenuShareTimeline({
		title: '<?=$result['content_title']?>', // 分享标题
		link: '<?=$result['share_url']['timeline']?>',
		imgUrl: 'https://api.leduika.com/assets/logo/20170620103512.png', // 分享图标
		success: function () { 
			Ajax({url:'/content/share.html',type:'POST', data:{key: '<?=$result['share_nos']['timeline']?>', csrf_token: token}});
		},
	});
	
	wx.onMenuShareAppMessage({
		title: '<?=$result['content_title']?>', // 分享标题
		desc: '<?=$result['content_description']?>', // 分享标题
		link: '<?=$result['share_url']['friend']?>',
		imgUrl: 'https://api.leduika.com/assets/logo/20170620103512.png', // 分享图标
		success: function () { 
				Ajax({url:'/content/share.html',type:'POST', data:{key: '<?=$result['share_nos']['friend']?>', csrf_token:token}});
		}
	});
  });
 
  
</script>
<script>function qrcodenotfound(){var img=event.srcElement;img.src="/public/images/qrcode.jpg"; img.onerror=null;} </script>

<?php endif;?>

 

<body ontouchstart style="background-color: #f8f8f8;background-color:#f1f1f1;padding:30px;">

	<div class="weui_tab">
		<div class="weui_tab_bd">	
		<img src="https://api.leduika.com/assets/logo/20170620103512.png"  width="90px"/>
		<?=$result['content']?>
		</div>
		<img src="https://m.anyitime.com/public/images/qrcode.png"  width="200px;" onerror="qrcodenotfound()"/>
	</div>	
</body>
</html>
