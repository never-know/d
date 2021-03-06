<?php
$url = empty($_SERVER['HTTP_REFERER'])? null : check_plain($_SERVER['HTTP_REFERER']);
	
$r = (isset($url) && preg_match('!^http[s]?://[a-z]+\.'.str_replace('.', '\.', SITE_DOMAIN).'!', $url)) ? ['url'=> $url, 'title'=> '上一页'] : ['url'=> HOME_PAGE, 'title'=> '首页'];

if ($result['statusCode'] == 500) {
	$result['message'] = '<p>
	<strong>服务器遇到一个问题...</strong>
	</p>
	<p>懵啦。。。麻烦您再来一次</p>
	';
} else {
	$result['message'] = $result['message']  ?: ' <p>
	   <strong>页面找不到了</strong>
	 </p>
	 <p>页面可能已经被移出，或者您请求的链接存在错误</p>
	 '; 
}	
?>


<style>
body {
    margin: 0;
	padding:0;
    color: #222;
    font: 16px/1.7 'Helvetica Neue', Helvetica, Arial, Sans-serif;
    background: #eff2f5;
}
.error {
    margin: 6em auto 0;
    width: 404px;
}
.error .header {
    overflow: hidden;
    font-size: 1.8em;
    line-height: 1.2;
    padding: 0 0 1.6em .33em;
	position:relative;
}
.error p {
    margin: 0 0 1.7em;
    color: #999;
}

.error .statuscode {
  position:absolute;
  bottom:10px;
  right:0;
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
            <img src="/public/images/logo16.png" srcset="/public/images/logo16.png" alt="">
          </a>
          <div class="statuscode">- 404</div>
        </h1>
        <div class="content">
         <?=$result['message'];?>
		 <hr>
         <p>
           <span>即将为您跳转至</span>
		   <a href="<?=$r['url'];?>" id="js-history-back"><?=$r['title'];?></a>
         </p>
       </div>
      </div>
    </div>
</body>
 
