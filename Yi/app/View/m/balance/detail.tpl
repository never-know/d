<style>
.page-hd {
    margin: 14px 0;
	background-color:#fff;
	padding-top:16px  ;
	text-align:center;
	
}
.page-hd-title {
    font-size: 20px;
    font-weight: 400;
    margin-bottom: 6px;
	color:red;
}
.page-hd-desc {
    color: #888;
    font-size: 14px;
	padding-bottom: 12px;
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
  padding: 13px 20px 9px 20px;
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
	
	<div class="page-hd" style="box-shadow: rgba(0,0,0,0.1) 0 0px 8px 0;">
        <h1 class="page-hd-title">
            ￥ 1999.62
        </h1>
         <p class="page-hd-desc">2017-3-6</p>
    </div>
	
  <div class="page-bd">  
        <ul>
			<li>
                <a>
                    <p>2017-01-31</p>
					<div class="weui_cell_ft">+100.12</div>
                </a>
            </li>
			<li>
                <a class="weui-flex">
                    <p class="weui-flex-item">2017-01-30</p>
					<div class="weui_cell_ft">+30.68</div>
                </a>
            </li>
			<li>
                <a class="weui-flex">
                    <p class="weui-flex-item">2017-01-29</p>
					<div class="weui_cell_ft">+990.57</div>
                </a>
            </li>
			
		</ul>
    </div>      
