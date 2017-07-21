<?php
namespace App\Module\M;

use Min\App;

class QrcodeController extends \App\Module\M\BaseController
{
	public function onConstruct($redirect = 2) 
	{
		parent::onConstruct(2);
	}
 
	/*
		generate qrcode in bottom of content and record view log;
	
	*/
	public function index_get()
	{
		$shared_user_wx_id	=6;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if ($url['host'] == $_SERVER['SERVER_NAME'] && preg_match('|^/content/([a-z0-9]+)\.html$|', $url['path'], $match) && !empty($url['query'])) {
				$params = [];
				parse_str($url['query'], $params);
				if (!empty($match[1]) && isset($params['sid']) && validate('words', $params['sid'])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= session_get('wx_id');
					$params['current_user'] = session_get('USER_ID');
					$params['content_id']   = $match[1];
					$result = $this->request('\\App\\Service\\Share::view', $params);
					$shared_user_wx_id = ($result['body']['wx_id']??0);	// 分享者 用户ID
				}
			}
		} 
		
		$img = config_get('wx_qrcode');
		
		if ($shared_user_wx_id) {
			$scene_id = base_convert($shared_user_wx_id, 10, 36);
			$img = $this->getQRCode($scene_id, $img);
		} 
		
		//redirect($img);
		header("Content-Type:image/jpg"); 
		echo file_get_contents(PUBLIC_PATH . $img); 

		exit;
	}
	 
	public function getQRCode($scene_id, $default = null)
	{
		$cache 	= $this->cache('qrcode');
		$key	= 'qrcode_'. $scene_id;
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) { 
		
			$wx 	= $this->getWX();
			$result = $wx->getQRCode($scene_id);
			if (!empty($result['ticket'])) {
			
				$img = http_get($wx->getQRUrl($result['ticket']));
				if (!empty($img)) {
					$result['img_path'] = '/qrcode/' . implode('/', str_split($scene_id, 2)) . '.jpg';
					file_put_contents(PUBLIC_PATH . $result['img_path'], $img);
					$cache->set($key, $result, $result['expire_seconds']-100);
				}
			}
		}
		
		return $result['img_path']?:$default;
	}
}