<?php
namespace App\Module\M;

use Min\App;

class QrcodeController extends \App\Module\M\WbaseController
{
	public function onConstruct()
	{
	}
 
	public function index()
	{
		$uid 		= session_get('UID');
		$wxid 		= session_get('wxid');
		
		$shared_userid	= 0;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if ($url['host'] == $_SERVER['SERVER_NAME'] && '/news.html' == $url['host'] && !empty($url['query'])) {
				$params = [];
				parse_str($url['query'], $params);
				if (isset($params['id']) && isset($params['sid']) && validate('words', $params['sid'])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= $wxid;
					$params['salary'] 		= 20;
					$params['current_user'] = $uid;
					$result = $this->request('\\App\\Service\\Share::record', $params);
					$shared_userid = ($result['body']['userid']??0);	// 分享者 用户ID
				}
			}
		} 
		$img = config_get('wx_qrcode');
		
		if ($shared_userid) {
			$img = $this->getQRCode($shared_userid, $img);
		} 
		
		redirect($img);
		
		exit;
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
	
	
	public function  subscribe_get()
	{
		$this->success();
	
	}
}