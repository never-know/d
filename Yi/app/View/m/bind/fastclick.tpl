<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache"> 
    <META HTTP-EQUIV="Expires" CONTENT="0"> 
    <title>fastclick 测试</title>
    <style>
        .topSlide{
           position : relative ;
            background-color: blue ;
            margin : auto ;
            margin-top : 30px ;
        }
        .topSlide p{
            text-align: center ;
            font-size : 20px ;
            line-height : 36px ;
            color : white ;
        }
        .result{
           position : relative ;
            background-color: green ;
            margin : auto ;
            margin-top : 30px ;
            color : white ;
        }
    </style>

</head>
<body>
   <div id="t1" class="topSlide">
          <p>click me</p>
    </div>

    <div id="t2" class="topSlide">
          <p>click me(by FastClick)</p>
    </div>

    <div id="result1" class="result">
       <h3>touchStart</h3>
       <p>haha</p>
    </div>

    <div id="result2" class="result">
       <h3>click(ms)</h3>
       <p>haha</p>
    </div>

    <div id="result3" class="result">
       <h3>touchEnd(ms)</h3>
       <p>haha</p>
    </div>

</body>

<script type="text/javascript" src="https://m.anyitime.com/public/m/zepto.min.js"></script>
<script type="text/javascript" src="https://m.anyitime.com/public/m/fastclick.js"></script>
 
<script>
$(function(){ 
    var startTime;
    var log = function (msg) {
        var t = (new Date().getTime() - startTime);
        if(msg == 'touchStart')
           $('#result1 p').text(t);
        else if(msg == 'touchEnd')
             $('#result3 p').text(t);
        else if(msg == 'mouseClick')
             $('#result2 p').text(t);
        console.log(msg);
    };
    var touchStart = function () {
        startTime = new Date().getTime();
        log('touchStart');
    };
    var mouseClick = function () {
        log('mouseClick');
    };
    var touchEnd = function () {
        log('touchEnd');
    };
    var me = $('#t2')[0];
      FastClick.attach(me);
      $('#t1').on('click', mouseClick);
      var dom = $('#t2')[0];
      dom.addEventListener('click', mouseClick);
      document.addEventListener('touchstart', touchStart);
      document.addEventListener('touchend', touchEnd);
}); 
</script>
</html>