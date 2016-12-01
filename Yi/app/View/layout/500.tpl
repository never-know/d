<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="/public/css/reset.css">
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
            <img src="/public/images/logo.jpg" srcset="/public/images/logo.jpg" alt="知乎">
          </a>
          - 404
        </h1>
        <div class="content">
         <p>
           <strong>服务器遇到一个问题...</strong>
         </p>
         <p>懵啦。。。。</p>
         <hr>
         <p>
           <a href="/">返回首页</a>
           <span>或者</span>
           <a href="javascript:;" id="js-history-back">返回上页</a>
         </p>
       </div>
      </div>
    </div>
	<!--
    <script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script>
	<script src="//zhstatic.zhihu.com/assets/zap/2.1.2/zap.js"></script>
    <script src="//static.zhihu.com/static/js/desktop/404.js"></script>
	-->
</body>
<script>
 var backButton = document.getElementById('js-history-back')
  backButton.onclick = function() {
    history.go(-1);
  }
</script>
</html>
