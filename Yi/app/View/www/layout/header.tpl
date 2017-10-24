<div class="nav-1" style="top:78px;"> </div> 
<div class="header">          
	<div class="header-inner">   
			<div class="logo">
				<a href="/" title="">
					<img src="/public/images/logo16.png" />
				</a>
			</div>  
			<div class="summary hide" style="float: left;margin: 6px 20px 10px 130px;font-size: 16px;">
				<span>帐户余额</span> <strong style="color: #e8585a;font-size: 26px; margin-left: 8px;">￥00</strong>
			</div> 
			
			
			
			<div class="account">                       	    
				<a href="" class="avatar"><img src="<?=get_avatar(session_get('www_wx_id'), ASSETS_URL)?>"  onerror="imgnotfound()"/></a>                          
				<a href="" class="nickname"><?=session_get('www_nickname')??''?></a>  
				<a id="logout" href="">退出</a>					 
			</div> 
 
			<div class="main-menu hide">
				<ul>
					<li class="active"><a href="">首页</a></li>
					<li><a href="">其他</a></li>
					<li><a href="">个人中心</a></li>
					<li><a href="">论坛</a></li>
				</ul>
			</div>
			<div class="summary-card hide">
				<ul>
					<li class="dashboard-stat green-haze"> 
					<div class="details">
							<div class="number">
								 ￥ 1349
							</div>
							<div class="desc">
								 帐户余额
							</div>
						</div>
					</li>
					<li class="dashboard-stat blue-madison hide">
					<div class="details">
							<div class="number">
								   1349
							</div>
							<div class="desc">
								 余额新增
							</div>
						</div>
					</li>
					<li class="dashboard-stat red-intense">
					<div class="details">
							<div class="number">
								 1349
							</div>
							<div class="desc">
								 下线成员
							</div>
						</div>
					</li>
					
					<li class="dashboard-stat purple-plum">
					<div class="details">
							<div class="number">
								  ￥ 1349
							</div>
							<div class="desc">
								 今日收益
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="qcode hide" style="    float: right;
    padding-right: 22px;
    padding-top: 3px;
    padding-left: 26px;
    background-color: #ed7b7d;">
				<span style="    float: left;
    margin-top: 26px;
    margin-right: 16px;
    font-size: 14px;">扫描二维码关注公众号 </span>
				 <img src="/public/images/qrcode_for_gh_d4b7de5f7463_258.jpg"  style="height:68px;"/>
			</div>
	</div>
</div>  