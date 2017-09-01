<?php
namespace App\Module\M;

use Min\App;

class ShareController extends \App\Module\M\BaseController
{
	/*
		content_id
		share_time
		user_id
		type
	*/
	
	public function index_post()
	{
		$share_no = $_POST['key'];
		$params = share_decode($share_no);
		
		if ($params['user_id'] != session_get('USER_ID')) {
			$this->error('参数错误', 111111);
		}

		$params['share_no']		= $share_no;
		$params['share_time']	= $_SERVER['REQUEST_TIME'];
		
		$this->request('\\App\\Service\\Share::share', $params, self::EXITALL);
	}
	
	
	public function views_get()
	{
		$params = [];
		$params['share_id'] = App::getArgs();
		$params['share_user'] = session_get('USER_ID');
		
		$this->request('\\App\\Service\\Share::shareViews', $params);
	
	}
	
	public function js_get()
	{
		$open_id = session_get('open_id');
		if (preg_match('|^'. SCHEMA . SERVER_NAME . '/content/([a-zA-Z0-9_\-]+)(/[a-zA-Z0-9_\-]+)?\.html$|', $_SERVER['HTTP_REFERER'], $match) && !empty($open_id)) {
			
			if (!empty($match[2])) {
				$jsApiList =  '"getLocation"';
			} else {
				$jsApiList = '"onMenuShareTimeline", "onMenuShareAppMessage"';
			}
			$id = str2int($match[1]);
		} else {
			exit('var msg = "error";');
		}
		
		$result 					= [];
		$result['share_nos']		=  \share_encode($id);
		
		foreach ($result['share_nos'] as $key => $value) {
			$result['share_url'][$key] 	=   SCHEMA . SERVER_NAME . '/content/' . $match[1] .  '/' . $value . '.html';
		}
		
		$wx = $this->getWx();
		$result['js'] = $wx->getJsSign($_SERVER['HTTP_REFERER']);
				
		echo 'var params = {token : "' . get_token('m_share_index') . '"};
		
		wx.config({
			appId: 	"'. 	$result['js']['appId'] . '",
			timestamp: '. 	$result['js']['timestamp']. ',
			nonceStr: "'. 	$result['js']['nonceStr']. '",
			signature: "'. 	$result['js']['signature']. '",
			jsApiList: [' . $jsApiList .' ]
		});
	  
		wx.ready(function () { ' .
		
		(empty($match[2]) ? ('wx.onMenuShareTimeline({
				title: content_title,
				link: "' . $result['share_url']['timeline']. '",
				imgUrl: content_icon, 
				success: function () {
					params.key = "' . $result['share_nos']['timeline'] .'";
					Ajax({url:"/share.html",type:"POST", data: params});
				},
			});
		
			wx.onMenuShareAppMessage({
				title: content_title,
				desc: content_description,
				link: "' .$result['share_url']['friend']. '",
				imgUrl: content_icon, 
				success: function () { 
					params.key = "' . $result['share_nos']['friend'] .'";
					Ajax({url:"/share.html",type:"POST", data:params});
				}
			});') : ('
			
			wx.getLocation({
				type: "wgs84", 
				success: function (res) {
					document.cookie = "lat=" +  res.latitude +";path=/;"; 
					document.cookie = "lng=" +  res.longitude +";path=/;"; 
					document.cookie = "accuracy=" +  res.accuracy +";path=/;"; 
				}
			});')
		) . 
		'});';
	
		exit;
	}
	
}