<?php
$url = empty($_SERVER['HTTP_REFERER'])? null : check_plain($_SERVER['HTTP_REFERER']);
	
$result = (isset($url) && preg_match('!^http[s]?://[a-z]+\.'.str_replace('.', '\.', SITE_DOMAIN).'!', $url)) ? ['url'=> $url, 'title'=> '上一页'] : ['url'=> HOME_PAGE, 'title'=> '首页'];
if ($code == 500) {
	$result['message'] = '<p>
	<strong>服务器遇到一个问题...</strong>
	</p>
	<p>懵啦。。。麻烦您再来一次</p>
	<hr> ';
} else {
	$result['message'] = $message ?: ' <p>
	   <strong>页面找不到了</strong>
	 </p>
	 <p>页面可能已经被移出，或者您请求的链接存在错误</p>
	 <hr>'; 
}	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="Refresh" content="2; URL=<?=$result['url'];?>">
<link rel="stylesheet" href="/public/font/iconfont.css">
<link rel="stylesheet" href="/public/css/yi.css">
<style>
body {
    margin: 0;
    color: #222;
    font: 16px/1.7 'Helvetica Neue', Helvetica, Arial, Sans-serif;
    background: #eff2f5;
}
.error {
    margin: 169px auto 0;
    width: 404px;
}
.error .header {
    overflow: hidden;
    font-size: 1.8em;
    line-height: 1.2;
    margin: 0 0 .33em .33em;
}
.error p {
    margin: 0 0 1.7em;
    color: #999;
}
.error strong {
    font-size: 1.1em;
    color: #000;
}
.error p:last-child {
    margin-bottom: 0;
}
a {
    text-decoration: none;
    color: #105cb6;
}

</style>
</head>
<body>
    <div class="page">
      <div class="error">
        <h1 class="header">
          <a href="/" class="logo">
            <img src="/public/images/logo16.jpg" srcset="/public/images/logo16.jpg" alt="">
          </a>
          - 404
        </h1>
        <div class="content">
         <?=$result['message'];?>
         <p>
           <span>即将为您跳转至</span>
		   <a href="<?=$result['url'];?>" id="js-history-back"><?=$result['title'];?></a>
         </p>
       </div>
      </div>
    </div>
</body>
<script>

</script>
</html>
