<?php
namespace App\Module\M;

use Min\App;

class ShareController extends \Min\Controller
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
		if (preg_match('|^'. SCHEMA . SERVER_NAME . '/content/([a-zA-Z0-9_\-]+).html$|', $_SERVER['HTTP_REFERER'], $match)){
			$id = str2int($match[1]);
		} else {
			exit;
		}
		
		$result 					= [];
		$result['share_nos']		=  \share_encode($id);
		
		foreach ($result['share_nos'] as $key => $value) {
			$result['share_url'][$key] 	=   SCHEMA . SERVER_NAME . '/content/' . $match[1] .  '/' . $value . '.html';
		}
		
		$wx = $this->getWx();
		$result['js'] = $wx->getJsSign($_SERVER['HTTP_REFERER']);
				
		echo 'function parseJSON(c){if(!c){return null}if(window.JSON&&window.JSON.parse){return window.JSON.parse(c)}var a=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,e=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,b=/(?:^|:|,)(?:\s*\[)+/g,d=/^[\],:{}\s]*$/;if(d.test(c.replace(e,"@").replace(a,"]").replace(b,""))){return(new Function("return "+c))()}}function initXMLhttp(){var a;if(window.XMLHttpRequest){a=new XMLHttpRequest()}else{a=new ActiveXObject("Microsoft.XMLHTTP")}return a}function Ajax(c){if(!c.url){return}if(!c.type){return}if(!c.method){c.method=true}var a=initXMLhttp();a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(c.success){c.success(parseJSON(a.responseText),a.readyState)}}else{if(c.fail){c.fail()}}}};var b=[],l=c.data;if(typeof l==="string"){var f=String.prototype.split.call(l,"&");for(var g=0,e=f.length;g<e;g++){var h=f[g].split("=");b.push(encodeURIComponent(h[0])+"="+encodeURIComponent(h[1]))}}else{if(typeof l==="object"&&!(l instanceof String)){for(var d in l){var h=l[d];if(Object.prototype.toString.call(h)=="[object Array]"){for(var g=0,e=h.length;g<e;g++){b.push(encodeURIComponent(d)+"[]="+encodeURIComponent(h[g]))}}else{b.push(encodeURIComponent(d)+"="+encodeURIComponent(h))}}}}b=b.join("&");if(c.type=="GET"){a.open("GET",c.url+"?"+b,c.method);a.setRequestHeader("X-REQUESTED-WITH","xmlhttprequest");a.send()}if(c.type=="POST"){a.open("POST",c.url,c.method);a.setRequestHeader("Content-type","application/x-www-form-urlencoded");a.setRequestHeader("X-REQUESTED-WITH","xmlhttprequest");a.send(b)}};
		var token = "' . get_token('m_share_index') . '";
		wx.config({
			appId: 	"'. $result['js']['appId'] . '",
			timestamp: '. $result['js']['timestamp']. ',
			nonceStr: "'. $result['js']['nonceStr']. '",
			signature: "'. $result['js']['signature']. '",
			jsApiList: [
				"onMenuShareTimeline", "onMenuShareAppMessage"
			]
		});
	  
		wx.ready(function () {
	   
			wx.onMenuShareTimeline({
				title: content_title,
				link: "' . $result['share_url']['timeline']. '",
				imgUrl: content_icon, 
				success: function () { 
					Ajax({url:"/share.html",type:"POST", data:{key: "' . $result['share_nos']['timeline'] .'", csrf_token: token}});
				},
			});
		
			wx.onMenuShareAppMessage({
				title: content_title,
				desc: content_description,
				link: "' .$result['share_url']['friend']. '",
				imgUrl: content_icon, 
				success: function () { 
					Ajax({url:"/share.html",type:"POST", data:{key: "' . $result['share_nos']['friend'] .'", csrf_token:token}});
			}
		});
	});';
	
	exit;
	
	
	}
	
}