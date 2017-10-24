
	<div class="weui_panel weui_panel_access" style="margin-top: 10px;">
   
      <div class="weui_panel_bd">
        <a href="/user/profile.html" class="weui_media_box weui_media_appmsg" style="padding: 12px 15px 12px 22px;">
          <div class="weui_media_hd">
            <img class="weui_media_appmsg_thumb" src="<?=$result['headimgurl'];?>"  onerror="imgnotfound()" alt="">
          </div>
          <div class="weui_media_bd" style="margin-left:.5em;margin-top:4px;letter-spacing: 0px;">
            <h4 class="weui_media_title"><?=$result['nickname']?></h4>
            <p class="weui_media_desc long_dot" style="font-size:13px;">手机号码 : <?=session_get('user_phone')?></p>
          </div>
		   <div class="weui_media_hd" id="qrcode" onclick="window.location.hash='#qrcode'" style="text-align: right;margin-right: 0;" >
            <img class="weui_media_appmsg_thumb" src="/public/images/abc.png"  style="width:36px;vertical-align: middle;" alt="">
          </div>
		   <div class="weui_panel_ft" style ="border:none;padding-left:24px;">
            
          </div>
        </a>
     
      </div>
       
    </div>
	<div class="weui-row weui-no-gutter" style="position:relative;background:#fff;">
      <a class="weui-col-50 col-50-first" href="/balance/daily/today.html">
		<div>
			<p><?=$result['today_salary']?></p>
			<span>今日收益(元)</span>
		</div>
		</a>
		
      <a class="weui-col-50" href="/balance.html">
	  <div>
		<p><?=$result['account_balance']?></p>
		<span>帐户余额(元)</span>
	</div>
	  </a>
    </div>
 
	<div class="weui_cells weui_cells_access">
        <a class="weui_cell" href="/balance/income.html">
          <div class="weui_cell_hd">
			<div class="weui_tabbar_icon"><!--5fb6d8  82d1ef 0DACFD 28aa8e-->
					<svg class="icon" style="width: 2.02em; height: 1.7em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="15100"><path d="M192 224a320 128 0 1 0 640 0 320 128 0 1 0-640 0ZM211.2 352c-12.8 16-19.2 32-19.2 48C192 480 336 544 512 544s320-64 320-144c0-16-6.4-32-19.2-48-44.8 54.4-163.2 96-300.8 96s-259.2-41.6-300.8-96zM211.2 544c-12.8 16-19.2 32-19.2 48C192 672 336 736 512 736s320-64 320-144c0-16-6.4-32-19.2-48-44.8 54.4-163.2 96-300.8 96s-259.2-41.6-300.8-96zM211.2 736c-12.8 16-19.2 32-19.2 48C192 864 336 928 512 928s320-64 320-144c0-16-6.4-32-19.2-48-44.8 54.4-163.2 96-300.8 96s-259.2-41.6-300.8-96z" fill="#fb992a" p-id="15101"></path></svg>
				</div>
		  </div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的收益</p>
          </div>
          <div class="weui_cell_ft"><?=($result['today_salary']?:'')?></div>
        </a>
		<a class="weui_cell" href="/balance.html">
			<div class="weui_cell_hd">
				<div class="weui_tabbar_icon"><!-- ff9500  fd4b71  924bfd-->
					<svg class="icon" style="width: 2em; height: 1.6em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5761"><path d="M512 341.333333v341.333334h426.666667V341.333333H512z m170.666667 234.666667c-34.133333 0-64-29.866667-64-64s29.866667-64 64-64 64 29.866667 64 64-29.866667 64-64 64z" fill="#008ede" p-id="5762"></path><path d="M426.666667 682.666667V341.333333c0-46.933333 38.4-85.333333 85.333333-85.333333h384V213.333333c0-46.933333-38.4-85.333333-85.333333-85.333333H213.333333c-46.933333 0-85.333333 38.4-85.333333 85.333333v597.333334c0 46.933333 38.4 85.333333 85.333333 85.333333h597.333334c46.933333 0 85.333333-38.4 85.333333-85.333333v-42.666667h-384c-46.933333 0-85.333333-38.4-85.333333-85.333333z" fill="#008ede" p-id="5763"></path></svg>
				</div>
			</div>
			<div class="weui_cell_bd weui_cell_primary">
				<p>我的钱包</p> 
			</div>
			<div class="weui_cell_ft">&nbsp;</div>
        </a>
		
	</div>
	
	<div class="weui_cells weui_cells_access">
       
	    <a class="weui_cell" href="/my/share.html">
          <div class="weui_cell_hd">
			<div class="weui_tabbar_icon"> 
					<svg class="icon" style="width: 2.2em; height: 1.26em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1047 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3181"><path d="M484.175373 148.456408 181.88129 148.456408c-42.232584 0-79.964232-2.966559-79.964232 39.778457l0 605.150235c0 42.742096 34.235138 77.403531 76.467722 77.403531l611.597244 0c42.203385 0 76.447283-34.659975 76.447283-77.403531L866.429308 638.605776l101.943337 0 0 258.003011c0 42.737716-34.238058 77.372873-76.472102 77.372873L76.441443 973.98166C34.239518 973.9802 0 939.346503 0 896.607327L0 122.66253C0 79.913135 34.239518 45.257539 76.441443 45.257539l407.73247 0 0 103.194489L484.173913 148.456408zM484.175373 148.456408M195.277522 653.997719c0 0 30.726949-409.605548 522.015055-406.06378L717.292577 45.257539l327.520051 305.444591L717.292577 656.151102 717.292577 454.882066c0-0.00584-310.471098-53.163009-522.015055 199.109813L195.277522 653.997719zM195.277522 653.997719" fill="#ff9500" p-id="3182"></path></svg>
				</div>
		  </div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的分享</p>
          </div>
          <div class="weui_cell_ft">&nbsp;</div>
        </a>
		
		<a class="weui_cell" href="/my/team.html">
		<div class="weui_cell_hd">
			<div  class="weui_tabbar_icon" > <!--6389D6  -->
				<svg class="icon" style="width: 2.2171875em; height: 1.36em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1200 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="22138"><path d="M1170.679172 787.279448c9.533793 23.728552 4.766897 52.18869 0 80.648828-33.438897 23.693241-157.519448 42.690207-157.519448 42.690207s-4.766897-33.191724-14.336-80.648828c-9.533793-61.651862-57.273379-113.840552-219.559724-189.722483 28.63669 14.230069-152.752552-33.191724-100.246069-113.840551 138.416552-109.108966 105.012966-279.869793 76.376276-369.981793 19.067586-9.498483 42.97269-14.230069 71.609379-14.230069h9.533793c128.882759 0 167.053241 118.572138 171.855449 184.990896 4.766897 156.530759-95.479172 218.217931-95.479173 218.217931s-4.766897 9.463172-4.766896 33.191724c0 9.463172 4.766897 23.693241 9.533793 28.424828 57.273379 42.725517 238.662621 85.380414 252.99862 180.25931z m-587.105103-180.25931c66.807172 52.18869 315.003586 109.108966 324.57269 227.681103 14.30069 61.687172 128.882759 170.760828-429.585656 170.760828-119.348966 0-210.025931-4.731586-276.833103-14.230069-243.464828-28.460138-171.855448-109.108966-157.519448-156.530759 19.067586-113.840552 262.497103-170.760828 329.339586-222.914207 9.533793-9.498483 19.067586-23.728552 19.067586-28.460137 0-28.460138-14.30069-47.457103-14.30069-47.457104s-119.348966-75.881931-119.348965-270.37131C263.768276 180.118069 306.740966 33.085793 473.829517 33.085793h9.533793c162.286345 0 210.025931 151.799172 214.792828 232.41269 0 203.987862-119.348966 270.37131-119.348966 270.37131s-9.533793 9.533793-9.533793 37.958621c0 4.731586 4.766897 28.460138 14.336 33.191724z" fill="#2d78f4" p-id="22139"></path></svg>
			</div>
			
			<div class="weui_tabbar_icon hide">
				<svg class="icon" style="width: 2.1em; height: 1.47em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="27652"><path d="M204.020138 517.106298c0-14.501272 10.034541-26.287713 22.310122-26.287713l242.652506 0 0 116.916826 61.327764 0L530.310529 490.818585l243.260349 0c12.088316 0 22.356171 12.05557 22.356171 26.287713l0 90.629113 61.32981 0 0-90.629113c0-46.74872-37.525662-84.73487-83.684958-84.73487l-243.260349 0 0-105.502869c54.355979-15.07023 94.373391-66.412572 94.373391-127.438461 0-72.770374-57.033971-131.838677-127.416971-131.838677s-127.416971 59.069327-127.416971 131.838677c0 62.794162 42.367947 115.340935 99.132788 128.660288l0 104.281041L226.329237 432.371428c-46.113247 0-83.637886 37.986149-83.637886 84.73487l0 90.629113 61.327764 0L204.019115 517.106298zM497.265926 665.097864c-70.381977 0-127.416971 59.025325-127.416971 131.883703 0 72.857355 57.033971 131.884726 127.416971 131.884726s127.416971-59.027371 127.416971-131.884726C624.682897 724.123188 567.648926 665.097864 497.265926 665.097864zM189.224154 668.701948c-70.335928 0-127.416971 58.981323-127.416971 131.837654 0 72.859401 57.081043 131.928728 127.416971 131.928728 70.430072 0 127.510092-59.069327 127.510092-131.928728C316.734246 727.683271 259.654226 668.701948 189.224154 668.701948zM826.59042 669.235091c-70.428026 0-127.510092 59.071374-127.510092 131.838677 0 72.859401 57.082066 131.928728 127.510092 131.928728 70.336952 0 127.417995-59.069327 127.417995-131.928728C954.007391 728.306464 896.927371 669.235091 826.59042 669.235091z"  fill="#e65757" p-id="27653"></path></svg>
			</div>
			
			<div class="weui_tabbar_icon hide">
				<svg class="icon" style="width: 2.2em; height: 1.6em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="29587"><path d="M512.281 158.32c91.882 0 166.375 69.994 166.375 156.345 0 25.917-6.752 50.347-18.64 71.88l134.655 98.195c16.669-11.023 36.97-17.52 58.901-17.52 56.601 0 102.508 43.124 102.508 96.337 0 53.198-45.906 96.322-102.508 96.322-56.616 0-102.523-43.124-102.523-96.322 0-12.002 2.356-23.479 6.625-34.074l-134.64-98.181c-22.287 18.697-49.99 31.82-80.605 37.081v150.031c59.865 12.866 104.523 63.185 104.523 123.333 0 69.882-60.306 126.554-134.668 126.554-74.378 0-134.668-56.673-134.668-126.554 0-60.148 44.658-110.466 104.523-123.333v-150.031c-30.628-5.263-58.32-18.386-80.605-37.081l-134.64 98.18c4.255 10.596 6.611 22.073 6.611 34.074 0 53.198-45.892 96.322-102.508 96.322-56.601 0-102.509-43.124-102.509-96.322 0-53.212 45.906-96.337 102.509-96.337 21.933 0 42.233 6.497 58.901 17.52l134.64-98.195c-11.874-21.533-18.626-45.962-18.626-71.88-0.002-86.35 74.491-156.345 166.375-156.345v0z" fill="#e65757" p-id="29588"></path></svg>
			</div>
			
			<div class="weui_tabbar_icon hide">
			<svg class="icon" style="width: 2.2em; height: 1.56em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="29637"><path d="M848.749092 688.690188c0.361227-1.059122 0.566912-2.191922 0.566912-3.373841 0-94.343715-76.748988-171.092703-171.09168-171.092703L522.460748 514.223644l0-66.48011c0-0.099261-0.01228-0.195451-0.014326-0.294712 21.691021-1.187035 43.212174-6.054903 63.290465-14.363126 23.311939-9.674337 44.682665-23.941272 62.522985-41.781592 17.818831-17.818831 32.128745-39.190581 41.762149-62.499449 9.636474-23.289426 14.636349-48.521087 14.636349-73.749678 0-25.207102-4.999874-50.417273-14.636349-73.727165-9.632381-23.289426-23.942295-44.659129-41.762149-62.499449-17.84032-17.819854-39.210024-32.127721-62.522985-41.763173-23.290449-9.674337-48.51904-14.676257-73.705676-14.676257-25.229614 0-50.457182 5.001921-73.768097 14.676257-23.309892 9.634428-44.661176 23.942295-62.501496 41.763173-17.839297 17.84032-32.127721 39.210024-41.763173 62.499449-9.674337 23.309892-14.654768 48.520063-14.654768 73.727165 0 25.228591 4.980431 50.460252 14.654768 73.749678 9.635451 23.308869 23.922852 44.680619 41.763173 62.499449 17.841344 17.84032 39.191604 32.107255 62.501496 41.781592 20.087501 8.30413 41.60149 13.171997 63.311954 14.361079-0.00307 0.100284-0.01535 0.196475-0.01535 0.296759l0 66.48011L345.796142 514.223644c-94.344738 0-171.111123 76.747965-171.111123 171.092703 0 1.180895 0.204661 2.314719 0.564865 3.373841-37.473473 4.849448-66.414619 36.862559-66.414619 75.661214 0 42.130539 34.150798 76.299757 76.299757 76.299757 42.149982 0 76.3192-34.170241 76.3192-76.299757 0-38.797631-28.956495-70.810742-66.433038-75.661214 0.360204-1.059122 0.564865-2.192945 0.564865-3.373841 0-82.811054 67.378573-150.189627 150.210094-150.189627l155.762553 0L501.558695 764.351402c0 0.185218 0.01842 0.364297 0.026606 0.545422-49.584302 5.197372-88.205924 47.120181-88.205924 98.084922 0 54.458309 44.131103 98.629321 98.609879 98.629321 54.458309 0 98.628298-44.171012 98.628298-98.629321 0-50.950416-38.633902-92.862991-88.185458-98.079806 0.00921-0.183172 0.026606-0.363274 0.026606-0.550539L522.458701 535.127743l155.763576 0c82.810031 0 150.188604 67.378573 150.188604 150.189627 0 1.180895 0.205685 2.313695 0.565888 3.372817-37.488823 4.848425-66.412572 36.862559-66.412572 75.661214 0 42.130539 34.128285 76.299757 76.299757 76.299757 42.130539 0 76.299757-34.170241 76.299757-76.299757C915.162688 725.555817 886.208239 693.542706 848.749092 688.690188z" p-id="29638"></path></svg>
			
			</div>
		</div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的会员</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
		
		
		
	</div>
	
	<div class="weui_cells weui_cells_access">
		
		
		<a class="weui_cell" href="/my/message.html">
		<div class="weui_cell_hd">
			<div  class="weui_tabbar_icon hide"> <!--fb992a  fdcc4a-->
				<svg class="icon" style="width: 2.2em; height: 1.6em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="30204"><path d="M860.52992 102.59456H165.25184c-50.52416 0-91.48288 41.89184-91.48288 93.57184v430.43456c0 51.68 40.95872 93.57184 91.48288 93.57184h122.11328c12.15616 0 20.93056 11.90272 17.59104 23.85792l-35.744 127.9744c-7.2768 26.05952 12.992 47.87456 35.3664 47.87456 7.51232 0 15.26528-2.46144 22.2848-8.04864l191.36128-152.23424c32.1152-25.5488 71.64416-39.42272 112.33024-39.42272H860.5312c50.52544 0 91.48416-41.89184 91.48416-93.57184V196.1664c-0.00128-51.68-40.96-93.57184-91.48544-93.57184z m36.59392 524.00512c0 20.63872-16.41472 37.43104-36.59392 37.43104H630.55488c-52.6272 0-104.48896 18.19776-146.03136 51.24608l-147.14752 117.06368 20.3584-72.89088c6.272-22.45888 1.80992-46.99776-11.94368-65.64352-13.74976-18.64704-35.59168-29.77664-58.4256-29.77664h-122.11328c-20.17536 0-36.59264-16.79232-36.59264-37.43104V196.1664c0-20.63872 16.41728-37.42976 36.59264-37.42976h695.27808c20.1792 0 36.59392 16.79104 36.59392 37.42976v430.43328zM759.89888 271.02464H375.66464c-15.15648 0-27.44448 12.56704-27.44448 28.07168 0 15.50464 12.28672 28.07168 27.44448 28.07168h384.23424c15.15648 0 27.44448-12.56704 27.44448-28.07168 0-15.50464-12.28672-28.07168-27.44448-28.07168z m0 167.2128H265.88672c-15.15904 0-27.44576 12.56448-27.44576 28.06912v2.44096c0 15.50464 12.28672 28.07296 27.44576 28.07296h494.01344c15.15648 0 27.44448-12.5696 27.44448-28.07296v-2.44096c-0.00128-15.50464-12.288-28.06912-27.44576-28.06912z" fill="#fdcc4a" p-id="30205"></path></svg>
			</div>
			<div class="weui_tabbar_icon hide">
				<svg class="icon" style="width: 2.1em; height: 1.46em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="31766"><path d="M877.30701 804.175409l-56.199976 0c-46.360887 0-84.300988 46.732347-84.300988 95.04468 0 29.55513-10.035564 51.14996-25.352411 51.14996-9.699919 0-21.524223-8.03193-34.253128-21.291932l-49.392944-54.765301c-32.781614-34.166147-97.544617-70.137407-143.907551-70.137407L146.691967 804.175409c-46.361911 0-84.303035-24.892947-84.303035-73.203232L62.388932 161.460322c0-48.312332 37.940101-87.830371 84.303035-87.830371l730.615043 0c46.38033 0 84.303035 39.518039 84.303035 87.830371l0 569.510832C961.610044 779.282462 923.68734 804.175409 877.30701 804.175409L877.30701 804.175409zM905.409045 161.460322c0-15.868409-12.872169-29.28907-28.102035-29.28907L146.691967 132.171252c-15.230889 0-28.101012 13.420661-28.101012 29.28907l0 569.510832c0 15.868409 12.870122 16.080234 28.101012 16.080234l337.207022 0c61.343113 0 140.290163 37.373189 183.638437 82.565461l20.211321 24.305569c18.563798-58.169841 71.341838-106.871029 133.358287-106.871029l56.199976 0c15.230889 0 28.102035-0.211824 28.102035-16.080234L905.409045 161.460322 905.409045 161.460322zM540.101012 337.137437 259.092943 337.137437c-15.513322 0-28.099988-13.102413-28.099988-29.271674 0-16.186657 12.586666-29.28907 28.099988-29.28907l281.007045 0c15.529695 0 28.101012 13.102413 28.101012 29.28907C568.202023 324.035024 555.630706 337.137437 540.101012 337.137437L540.101012 337.137437zM736.806046 483.543901 259.092943 483.543901c-15.513322 0-28.099988-13.119809-28.099988-29.28907 0-16.149818 12.586666-29.269627 28.099988-29.269627l477.712079 0c15.513322 0 28.099988 13.119809 28.099988 29.269627C764.90501 470.423069 752.318344 483.543901 736.806046 483.543901L736.806046 483.543901zM736.806046 600.663342 259.092943 600.663342c-15.513322 0-28.099988-13.119809-28.099988-29.28907 0-16.169261 12.586666-29.28907 28.099988-29.28907l477.712079 0c15.513322 0 28.099988 13.119809 28.099988 29.28907C764.90501 587.54251 752.318344 600.663342 736.806046 600.663342L736.806046 600.663342z" p-id="31767"></path></svg>
			</div>
			<div class="weui_tabbar_icon hide">
				 <svg class="icon" style="width:2.2em; height: 1.58em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="35571"><path d="M47.793 226.897v394.758c0 76.758 62.138 138.896 138.896 138.896h208.346l7.311 175.449 204.69-175.449h233.931c76.758 0 138.896-62.138 138.896-138.896v-394.758c0-76.758-62.138-138.896-138.896-138.896h-654.274c-76.758 0-138.896 62.138-138.896 138.896zM647.241 446.207c0-36.553 29.241-65.793 65.793-65.793s65.793 29.241 65.793 65.793c0 36.553-29.241 65.793-65.793 65.793-36.553 0-65.793-29.241-65.793-65.793zM446.207 446.207c0-36.553 29.241-65.793 65.793-65.793s65.793 29.241 65.793 65.793c0 36.553-29.241 65.793-65.793 65.793-36.553 0-65.793-29.241-65.793-65.793zM248.829 446.207c0-36.553 29.241-65.793 65.793-65.793s65.793 29.241 65.793 65.793c0 36.553-29.241 65.793-65.793 65.793-36.553 0-65.793-29.241-65.793-65.793z" fill="#fdcc4a" p-id="35572"></path></svg>
			</div>
			<div class="weui_tabbar_icon">
				<svg class="icon" style="width: 2.18em; height: 1.55em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="42192"><path d="M228.352 822.144h328.832c30.976 0 30.976 0.256 45.824 11.264 14.848 11.008 184.576 126.592 184.576 126.592V822.144h7.936c90.88 0 164.352-68.48 164.352-153.728V218.368C960 133.12 886.272 64 795.648 64H228.352C137.728 64 64 133.12 64 218.368v449.92c0 85.376 73.728 153.856 164.352 153.856z m490.624-448c38.144 0 68.864 30.848 68.864 68.864 0 38.144-30.848 68.864-68.864 68.864-38.144 0-68.992-30.848-68.992-68.864 0.128-38.016 30.848-68.864 68.992-68.864z m-206.72 0c38.144 0 68.864 30.848 68.864 68.864 0 38.144-30.848 68.864-68.864 68.864-38.144 0-68.864-30.848-68.864-68.864-0.128-38.016 30.72-68.864 68.864-68.864z m-206.848 0c38.144 0 68.864 30.848 68.864 68.864 0 38.144-30.848 68.864-68.864 68.864-38.144 0-68.864-30.848-68.864-68.864 0-38.016 30.72-68.864 68.864-68.864z" fill="#fdcc4a" p-id="42193"></path></svg>
			
			</div>
		</div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>我的消息</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
		
		
		
        <a class="weui_cell" href="/user/profile.html">
		<div class="weui_cell_hd">
			<div class="weui_tabbar_icon hide">
				<svg class="icon" style="width: 2em; height: 1.4em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="43184"><path d="M841.170486 420.624896c-7.571443-27.363208-18.563798-53.281507-32.328289-77.432557 15.673981-21.11183 69.687152-99.93813 30.766724-138.891304l-19.974937-21.052478c-33.257451-33.291221-118.28601 18.679431-138.940422 32.144094-24.424272-13.872962-50.65775-24.864294-78.352509-32.327266-4.450359-26.424836-23.843034-118.758778-78.603219-118.758778l-21.933545 0c-47.021943 0-74.003458 95.487771-80.048127 119.40653-27.329439 7.628748-53.182246 18.653849-77.240175 32.460296-19.028379-14.121626-100.452853-70.400397-140.203182-30.67565l-21.086248 17.74106c-34.635845 34.627658 22.913872 125.997646 33.440623 141.978618-13.316284 23.77652-23.992437 49.197492-31.347962 76.06235-22.979364 3.645017-121.009028 22.547529-121.009028 78.984912l0 21.925359c0 48.882314 102.826923 76.028581 121.606639 80.586386 7.439436 26.608008 18.197454 51.878554 31.513738 75.431993-11.057847 17.325597-66.381873 108.281145-32.044834 142.626371l19.958564 16.61133c45.162596 45.162596 139.538033-29.031197 139.538033-29.031197l-4.367471-4.682649c25.021883 14.853289 52.120055 26.533306 80.777745 34.586726 4.682649 19.243273 31.813566 121.373325 80.512708 121.373325l21.933545 0c63.874773 0 79.71453-119.248941 79.71453-119.248941l-5.861498-0.12382c28.956495-7.371898 56.419987-18.555611 81.856309-32.810267 19.957541 13.257955 103.542214 65.60109 136.915299 32.245402l22.215978-22.233374c44.547589-44.523029-27.944446-135.660726-29.986965-138.193409 13.780865-24.109094 24.838711-49.977251 32.411177-77.307713 24.905226-6.333242 118.683053-33.224706 118.683053-79.831186l0-21.925359C959.687764 438.390515 848.143295 421.654342 841.170486 420.624896zM512.116145 715.998137c-112.590288 0-203.862038-91.26254-203.862038-203.861014 0-112.624057 91.270726-203.886597 203.862038-203.886597 112.606661 0 203.89376 91.263563 203.89376 203.886597C716.009905 624.735597 624.722806 715.998137 512.116145 715.998137zM512.116145 596.09428c-46.358841 0-83.948971-37.608549-83.948971-83.957157 0-46.416146 37.59013-83.957157 83.948971-83.957157 46.390563 0 83.947947 37.541011 83.947947 83.957157C596.064093 558.485731 558.506708 596.09428 512.116145 596.09428z" p-id="43185"></path></svg>
			</div>
			
			<div class="weui_tabbar_icon">
				<svg class="icon" style="width: 2.3em; height: 1.36em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="43093"><path d="M941.87 617.158h-54.195c-8.79 31.88-21.489 62.152-37.652 90.182l50.09 50.159c32.073 32.071 32.073 84.09 0 116.158l-29.052 29.057c-32.073 32.067-84.09 32.067-116.158 0l-50.418-50.418c-27.962 15.91-58.111 28.354-89.86 37.01v52.595c0 45.349-36.75 82.099-82.1 82.099h-41.05c-45.344 0-82.1-36.75-82.1-82.099v-52.595c-31.749-8.656-61.892-21.037-89.86-37.01l-50.413 50.418c-32.071 32.067-84.09 32.067-116.158 0l-29.057-29.12c-32.072-32.074-32.072-84.086 0-116.159l50.095-50.159c-16.163-28.03-28.798-58.301-37.65-90.182H82.13c-45.348 0-82.098-36.75-82.098-82.099v-41.047c0-45.348 36.75-82.104 82.098-82.104h53.56c8.53-31.621 20.65-61.702 36.369-89.537l-48.172-48.236c-32.072-32.071-32.072-84.085 0-116.156l28.99-29.06c32.071-32.066 84.09-32.066 116.162 0l47.272 47.273c28.862-16.804 60.034-29.89 93.001-38.87v-55.16C409.312 36.75 446.062 0 491.411 0h41.052c45.348 0 82.098 36.75 82.098 82.099v55.16c32.968 8.979 64.204 22.128 93.07 38.933l47.208-47.272c32.067-32.068 84.086-32.068 116.158 0l29.053 29.057c32.072 32.073 32.072 84.09 0 116.158l-48.104 48.172c15.714 27.899 27.835 57.983 36.369 89.537h53.555c45.349 0 82.098 36.756 82.098 82.104v41.047c0 45.412-36.75 82.163-82.098 82.163zM512.003 254.57c-144.252 0-261.183 116.931-261.183 261.182 0 144.252 116.931 261.182 261.183 261.182 144.251 0 261.241-116.93 261.241-261.182 0-144.25-116.99-261.182-261.24-261.182z m0 361.304c-56.702 0-102.691-45.99-102.691-102.686 0-56.7 45.99-102.627 102.691-102.627 56.697 0 102.622 45.99 102.622 102.627 0 56.697-45.925 102.686-102.622 102.686z m0 0" fill="#2d78f4" p-id="43094"></path></svg>
			</div>
		</div>
          <div class="weui_cell_bd weui_cell_primary">
            <p>帐户设置</p>
          </div>
          <div class="weui_cell_ft">
          </div>
        </a>
        
    </div>
 
	<style>
	 
	.weui_dialog, .weui_toast{
		top:10%;
	}
	.weui_dialog_bd{
		font-size:0;
	}
	a.weui-col-50:active{
		background-color:#e8e8e8;
	}
	.weui_panel:before  {
	 border:none; 
	}
	.long_dot{
	white-space: nowrap;text-overflow: ellipsis; overflow: hidden;
	}
	#qrcode{
	width:50px;
	}
	.weui_cell .weui_tabbar_icon{width:45px;margin-top:-2px}
	
	
	</style>	
	 
	 <script>
	 
	 	var hash  = window.location.hash || '';
		var cropper = null;
		window.onhashchange = function(){
			var new_hash = window.location.hash || ''; 	//substring(1)用来减去地址栏的地址中的#号
			 
			if (hash == '#qrcode' && new_hash == '') {
					$.closeModal();
			}
			hash = new_hash;
		}
		 $(document).on("click", "#qrcode", function() {
		  
			//$.alert('<img src="http://m.anyitime.com/public/images/avatar.png" style="position:relative;width:100%;" />', '扫描二维码<p>关注安逸时光网微信公众号', '关闭');
			
			    $.modal({
				  title: '扫描二维码<p>关注 <span style="color:red;">安逸时光网</span> 微信公众号',
				  text: '<img src="https://m.anyitime.com/public/images/qrcode.png" style="position:relative;width:100%;" />',
				  buttons: [
					{ text: "关闭", className: "primary", onClick: function(){history.go(-1);}},
				  ]
				});
			
			
			return false;
		  });
	 </script>
