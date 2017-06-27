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
		$user_id 		= session_get('USER_ID');
		$wx_id 		= session_get('wx_id');
		
		$shared_userid	= 0;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if ($url['host'] == $_SERVER['SERVER_NAME'] && '/news.html' == $url['host'] && !empty($url['query'])) {
				$params = [];
				parse_str($url['query'], $params);
				if (isset($params['id']) && isset($params['sid']) && validate('words', $params['sid'])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= $wx_id;
					$params['salary'] 		= 20;
					$params['current_user'] = $user_id;
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
	 
	 

	public function getQRCode($user_id, $default = null)
	{
		$cache 	= $this->cache('qrcode');
		$result = $cache->get('qrcode_'. $user_id, true);
		
		if (empty($result) || $cache->getDisc() === $result) { 
		
			$result = $this->getWX()->getQRCode($user_id);
			
			if (!empty($result)) {
				$cache->set('qrcode_'. $user_id, $result, $result['expire_seconds']);
			}	
		}
	 
		return $result['url']?:$default;
		 
	}
	
	
	public function  subscribe_get()
	{
		$this->success();
	
	}
}