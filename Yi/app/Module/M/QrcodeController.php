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
		$shared_user_wx_id	= session_get('wx_id')??0;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if (preg_match('|^/content/([a-zA-Z0-9]+)/([a-zA-Z0-9_\-]+)\.html$|', $url['path'], $match)) {
				$params = [];
			
				if (!empty($match[1]) && !empty($match[2])   && validate('words', $match[2])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= session_get('wx_id');
					$params['current_user'] = session_get('USER_ID')??0;
					$params['content_id']   = \str2int($match[1]);
					$params['share_no']   	= $match[2];
					$params['lat']   		= ($_COOKIE['lat']??0)*10000000;
					$params['lng']   		= ($_COOKIE['lng']??0)*10000000;
					$params['ip']   		= ip_address();
					$result = $this->request('\\App\\Service\\ShareView::getShareUser', $params);
					if ($result['body']['record'] == 1 && $result['body']['share_user'] > 1) {
						$sign = md5(md5(config_get('private_key') . ($_SERVER['REQUEST_TIME']*($_SERVER['REQUEST_TIME']%188))));
						min_socket(HOME_PAGE.'/cron/shareview.html?time='.$_SERVER['REQUEST_TIME'].'&sign=' .$sign);
					}
					
					$shared_user_wx_id = ($result['body']['share_user']??0);	// 分享者 用户ID
				}
			}
		} 
		
		$img = PUBLIC_PATH . config_get('wx_qrcode');
		
		if ($shared_user_wx_id) {
			$scene_id = base_convert($shared_user_wx_id, 10, 36);
			$img = $this->getQRCode($scene_id, $img);
		} 
		
		//redirect($img);
		header("Content-Type:image/jpeg"); 
		echo file_get_contents($img); 

		exit;
	}
	 
	public function getQRCode($scene_id, $default = null)
	{
		$cache 	= $this->cache('qrcode');
		$key	= '{qrcode}:'. $scene_id;
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) { 
		
			$wx 	= $this->getWx();
			$result = $wx->getQRCode($scene_id);
			if (!empty($result['ticket'])) {
			
				$img = http_get($wx->getQRUrl($result['ticket']));
				if (!empty($img)) {
					$result['img_path'] = PUBLIC_PATH . '/qrcode/' . implode('/', str_split($scene_id, 2)) . '.jpg';
					
					$dir = dirname($result['img_path']);
					if (!is_dir($dir)) {
						if (!mkdir($dir, 0755, true)) {
							return $default;
						}
					}
					
					file_put_contents($result['img_path'], $img);
					$cache->set($key, $result, $result['expire_seconds']-100);
				}
			}
		}
		
		return $result['img_path']?:$default;
	}
}