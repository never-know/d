<?php
namespace App\Module\M;

use Min\App;

class NewsController extends \App\Module\M\WbaseController
{
	public function onConstruct()
	{
		$openid = session_get('openid');
		if (!isset($openid)) {
			$this->getOpenid();
		}
	}
	
	public function index_get()
	{
		$id = intval($_GET['id']);
		if($id < 1) {
			$this->error('商品不存在', 1000);
		}
		
		$openid = session_get('openid');
 
		if (!empty($openid)) {
			$this->request('\\App\\Service\\Wuser::addUserByOpenid', ['openid' => $openid, 'subscribe' => 2]);
		}

		$news = $this->request('\\App\\Service\\News::detail', $id);
	}
 
	public function qrcode()
	{
		$uid 		= session_get('UID') ?? 0;
		$openid_id 	= session_get('openid_id') ?? 0;
		
		$shared_userid	= 0;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if ($url['host'] == $_SERVER['SERVER_NAME'] && '/news.html' == $url['host'] && !empty($url['query'])) {
				$params = [];
				parse_str($url['query'], $params);
				if (isset($params['id']) && isset($params['sid']) && validate('words', $params['sid'])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= $openid_id;
					$params['salary'] 		= 20;
					$params['current_user'] = $uid;
					$result = $this->request('\\App\\Service\\Share::record', $params);
					$shared_userid = ($result['body']['userid']??0);	// 分享者 用户ID
				}
			}
		} 
		
		if ($shared_userid == 0) {
			$img = WX_QRCODE;
		} else {
			$img = $this->getQRCode($shared_userid, WX_QRCODE);
		}
	}
	 
	public static function __call($name, $arguments = [])
	{  
		return null;
	}

	public function getQRCode($uid, $default = null)
	{
		$cache 	= $this->cache('qrcode');
		$result = $cache->get('qrcode_'. $uid);
		
		if (empty($result) || $cache->getDisc() === $result) { 
		
			$result = $this->getWX()->getQRCode($uid);
			
			if (!empty($result)) {
				$cache->set('qrcode_'. $uid, $result, $result['expire_seconds']);
			}	
		}
	 
		return $result['url']?:$default;
		 
	}
}