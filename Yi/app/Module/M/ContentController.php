<?php
namespace App\Module\M;

use Min\App;

class ContentController extends \App\Module\M\BaseController
{	
	public function onConstruct($redirect = 2)
	{
		parent::onConstruct(2);
		
		require CONF_PATH .'/keypairs.php';
	}

	public function __call($method, $param)
	{
		$id = \str2int(substr($method, 0, -4));
		
		if(!$id) {
			$this->error('参数错误', 1);
		}
		
		$result = $this->request('\\App\\Service\\Article::detail', $id);
		
		if (0 === $result['statusCode']) {
			$result['body']['meta'] = ['menu_active' => 'article_list', 'title' => $result['body']['title'], 'description' => $result['body']['desc']];
			$result['body']['share'] =  shareid($id);
		}
		
		$this->response($result);
		
	}
 
	/*
		content_id
		share_time
		user_id
		type
	
	*/
	
	public function share_get()
	{
		$share_id = $_POST['share_id'];
		$params = shareid_decode($share_id);
		
		if ($params['user_id'] != session_get('USER_ID')) {
			$this->error('参数错误', 111111);
		}

		$params['share_id']		= $share_id;
		$params['share_time']	= $_SERVER['REQUEST_TIME'];
		
		$this->request('\\App\\Service\\Share::share', $params, self::EXITALL);
	}
	
	/*
		generate qrcode in bottom of content and record view log;
	
	*/
	public function qrcode_get()
	{
		$shared_userid	= 0;
		
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if ($url['host'] == $_SERVER['SERVER_NAME'] && preg_match('|^/content/([a-z0-9]+)\.html$|', $url['path'], $match) && !empty($url['query'])) {
				$params = [];
				parse_str($url['query'], $params);
				if (!empty($match[1]) && isset($params['sid']) && validate('words', $params['sid'])) {
					$params['view_time'] 	= $_SERVER['REQUEST_TIME'];
					$params['viewer_id'] 	= session_get('wx_id');
					$params['current_user'] = intval(session_get('USER_ID'));
					$params['content_id']   = $match[1];
					$result = $this->request('\\App\\Service\\Share::view', $params);
					$shared_userid = ($result['body']['user_id']??0);	// 分享者 用户ID
				}
			}
		} 
		$img = config_get('wx_qrcode');
		
		if ($shared_userid) {
			$scene_id = base_convert($shared_userid, 10, 36);
			$img = $this->getQRCode($scene_id, $img);
		} 
		
		redirect($img);
		
		exit;
	}
	 
	 

	public function getQRCode($scene_id, $default = null)
	{
		$cache 	= $this->cache('qrcode');
		$result = $cache->get('qrcode_'. $scene_id, true);
		
		if (empty($result) || $cache->getDisc() === $result) { 
		
			$result = $this->getWX()->getQRCode($scene_id);
			
			if (!empty($result)) {
				$cache->set('qrcode_'. $scene_id, $result, $result['expire_seconds']);
			}	
		}
		return $result['url']?:$default;
	}
	
}