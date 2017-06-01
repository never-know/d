<?php
namespace App\Module\M;

use Min\App;

class QrcodeController extends \App\Module\M\WbaseController
{
	public function onConstruct()
	{
		$openid = session_get('openid');
		if (!isset($openid)) {
			$this->getOpenid();
		}
	}
 
	public function index()
	{
		$uid 		= session_get('UID');
		$wxuser_id 	= session_get('wxuser_id');
		
		$shared_userid	= 0;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if ($url['host'] == $_SERVER['SERVER_NAME'] && '/news.html' == $url['host'] && !empty($url['query'])) {
				$params = [];
				parse_str($url['query'], $params);
				if (isset($params['id']) && isset($params['sid']) && validate('words', $params['sid'])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= $wxuser_id;
					$params['salary'] 		= 20;
					$params['current_user'] = $uid;
					$result = $this->request('\\App\\Service\\Share::record', $params);
					$shared_userid = ($result['body']['userid']??0);	// 分享者 用户ID
				}
			}
		} 
 
		if ($shared_userid) {
			$img = $this->getQRCode($shared_userid, WX_QRCODE);
		} else {
			$img = WX_QRCODE;
		}
		
		redirect($img);
		exit;
	}
	 
	public static function __call($name, $arguments = [])
	{  
		return null;
	}

	public function getQRCode($uid, $default = null)
	{
		$cache 	= $this->cache('qrcode');
		$result = $cache->get('qrcode_'. $uid, true);
		
		if (empty($result) || $cache->getDisc() === $result) { 
		
			$result = $this->getWX()->getQRCode($uid);
			
			if (!empty($result)) {
				$cache->set('qrcode_'. $uid, $result, $result['expire_seconds']);
			}	
		}
	 
		return $result['url']?:$default;
		 
	}
}