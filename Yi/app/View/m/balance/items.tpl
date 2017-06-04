<style>
.page-hd {
    padding: 20px;
}
.page-hd-title {
    font-size: 20px;
    font-weight: 400;
    text-align: left;
    margin-bottom: 15px;
}
.page-hd-desc {
    color: #888;
    font-size: 14px;
    margin-top: 5px;
    text-align: left;
}
	
	
.page-bd {
	padding: 0;
}
.page-bd ul {
	list-style: none;
}
	
 
 
.page-bd li {
    background-color: #fff;
    border-radius: 2px;
    cursor: pointer;
    margin: 2px 0;
    overflow: hidden;
    vertical-align: bottom;
	font-size:14px;
	    line-height: 16px;
}

.page-bd a {
    align-items: center;
    padding: 12px 20px 8px 20px;
    transition: all 0.3s ease 0s;
    display: -webkit-flex;
    display: -webkit-box;
    display: flex;
}

  

.page-bd a p {
     -webkit-box-flex: 1;
    -webkit-flex: 1;
    flex: 1;
}

li a:after {
     content: " ";
    display: inline-block;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    height: 6px;
    width: 6px;
    border-width: 2px 2px 0 0;
    border-color: #c8c8cd;
    border-style: solid;
    position: relative;
    margin-left: .3em;
}
 
 

li a:active{
background-color:#f6f6f6;
}
	
	</style>
	
	
	<div class="weui_cells weui_cells_access weui_return">
		<a class="weui_cell" onclick="window.location.href='/user/line.html'">
			<span class="weui_cell_ft" ></span>返回
		</a>
	</div>
	
	<div class="page-hd">
        <h1 class="page-hd-title">
            手风琴
        </h1>
        <p class="page-hd-desc">演示首页用的就是这种效果</p>
    </div>
  <div class="page-bd">  
        <ul>
			<li>
                <a class="">
                    <p>2017-01-31</p>
					 <div class="weui_cell_ft">说明文字</div>
                </a>
            </li>
			<li>
                <a class="weui-flex">
                    <p class="weui-flex-item">2017-01-30</p>
                </a>
            </li>
			<li>
                <a class="weui-flex">
                    <p class="weui-flex-item">2017-01-29</p>
                </a>
            </li>
			
		</ul>
    </div>      
<div class="weui_cells weui_cells_access">

        <a class="weui_cell" href="javascript:;">
          <div class="weui_cell_hd"><img src="/public/images/credit.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的收益</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix">3000</div>
        </a>
		<a class="weui_cell" href="javascript:;">
          <div class="weui_cell_hd"><img src="/public/images/message.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的钱包</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix">&nbsp;</div>
        </a>
		 <a class="weui_cell" href="javascript:;">
          <div class="weui_cell_hd"><img src="/public/images/credit.png" alt="" width="24"></div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的分享</p>
          </div>
          <div class="weui_cell_ft weui_cell_ft_fix">&nbsp;</div>
        </a>
	</div>
	
	<style>
	.weui_cell_bd p, .weui_cell_ft_fix {
    padding:   0;
}

.weui_cell {
padding:12px 15px 8px 15px;
}
	</style>