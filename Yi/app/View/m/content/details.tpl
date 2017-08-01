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
 
		<script src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>	
 	
	</head>
	  <style>
p img{
    width:100%;
	vertical-align: top;
}
</style>
<script>
   
  wx.config({
    debug: true,
    appId: 	'<?=$result['js']['appId'];?>',
    timestamp: <?=$result['js']['timestamp']?>,
    nonceStr: '<?=$result['js']['nonceStr']?>',
    signature: '<?=$result['js']['signature']?>',
    jsApiList: [
      'onMenuShareTimeline', 'onMenuShareAppMessage'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
	
	wx.onMenuShareTimeline({
		title: '<?=($result['content_title'])?>', // 分享标题
		link: '<?=($result['share_url'])?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		imgUrl: '<?=($result['content_icon'])?>', // 分享图标
		success: function () { 
			// 用户确认分享后执行的回调函数
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
});
	
	
	
	
  });
</script>

<body ontouchstart style="background-color: #f8f8f8;background-color:#f1f1f1;padding:30px;">

	<div class="weui_tab">
		<div class="weui_tab_bd">	
		<?=$result['content']?>
		</div>
		 
	</div>	
	
 
	 
</body>
</html>
