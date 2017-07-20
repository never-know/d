<?php
namespace Vendor\Wx;

use Min\App;

class WeBase
{
	const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
	const AUTH_URL = '/token?grant_type=client_credential&';
	
	const OAUTH_PREFIX = 'https://open.weixin.qq.com/connect/oauth2';
	const OAUTH_AUTHORIZE_URL = '/authorize?';
	
	const MENU_CREATE_URL = '/menu/create?';
	const MENU_GET_URL = '/menu/get?';
	const MENU_DELETE_URL = '/menu/delete?';
	const MENU_ADDCONDITIONAL_URL = '/menu/addconditional?';
	const MENU_DELCONDITIONAL_URL = '/menu/delconditional?';
	const MENU_TRYMATCH_URL = '/menu/trymatch?';
	
	const USER_GET_URL='/user/get?';
	const USER_INFO_URL='/user/info?';
	const USERS_INFO_URL='/user/info/batchget?';
	const USER_UPDATEREMARK_URL='/user/info/updateremark?';
	const GROUP_GET_URL='/groups/get?';
	const USER_GROUP_URL='/groups/getid?';
	const GROUP_CREATE_URL='/groups/create?';
	const GROUP_UPDATE_URL='/groups/update?';
	const GROUP_MEMBER_UPDATE_URL='/groups/members/update?';
	const GROUP_MEMBER_BATCHUPDATE_URL='/groups/members/batchupdate?';
	
	const QRCODE_CREATE_URL='/qrcode/create?';
	const QR_SCENE = 0;
	const QR_LIMIT_SCENE = 1;
	const QRCODE_IMG_URL='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
	const SHORT_URL='/shorturl?';
	
	const API_BASE_URL_PREFIX = 'https://api.weixin.qq.com'; //以下API接口URL需要使用此前缀
	const OAUTH_TOKEN_URL = '/sns/oauth2/access_token?';
	const OAUTH_REFRESH_URL = '/sns/oauth2/refresh_token?';
	const OAUTH_USERINFO_URL = '/sns/userinfo?';
	const OAUTH_AUTH_URL = '/sns/auth?';
	
	const MEDIA_UPLOAD_URL = '/media/upload?';
	
	private $token;
	private $appid;
	private $appsecret;
	private $access_token;
	private $user_token;
	
	public function __construct($type)
	{
		$conf 				= config_get($type);
		$this->token 		= $conf['token'];
		$this->appid 		= $conf['appid'];
		$this->appsecret 	= $conf['appsecret'];
	}
	
	public function cache()
	{
		$cache_setting = config_get('cache');
		$value = $cache_setting['wx'] ?? $cache_setting['default'];
		return App::getService($value['bin'], $value['key']);
	}
	
	/**
	 * 获取access_token
	 * @param string $appid 如在类初始化时已提供，则可为空
	 * @param string $appsecret 如在类初始化时已提供，则可为空
	 * @param string $token 手动指定access_token，非必要情况不建议用
	 */
	private function checkAuth() 
	{
		if (!empty($this->access_token)) return true;
		
		$cache 	= $this->cache('wx');
		$key = 'wechat_access_token_' . $this->appid;
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
		
			$r = http_get(self::API_URL_PREFIX.self::AUTH_URL.'appid='.$this->appid.'&secret='.$this->appsecret);
			if ($r) {
				$result = json_decode($r, true);
				if (empty($result['access_token'])) {
					watchdog($r, 'wx_result_error');
					return false;
				}
				
				$expire = $result['expires_in'] ? (intval($result['expires_in'])-100) : 7100;
				$cache->set($key, $result, $expire);
			}
		}
		 
		$this->access_token = $result['access_token'];
		return true;
	}


	/* USER AND GROUP */
	
	/**
	 * 获取关注者详细信息
	 * @param string $openid
	 * @param string $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
	 * @return array {subscribe,openid,nickname,sex,city,province,country,language,headimgurl,subscribe_time,[unionid]}
	 * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
	 */
	public function getUserInfo($openid, $lang = 'zh_CN'){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = http_get(self::API_URL_PREFIX.self::USER_INFO_URL.'access_token='.$this->access_token.'&openid='.$openid.'&lang='.$lang);
		if ($result)
		{
			$json = json_decode($result,true);
			if (isset($json['errcode'])) {
				watchdog($result, 'wx_result_error');
				return false;
			}
			return $json;
		}
		return false;
	}


	/* 场景二维码 和短链接转换 */
	
	/**
	 * 创建二维码ticket
	 * @param int|string $scene_id 自定义追踪id,临时二维码只能用数值型
	 * @param int $type 0:临时二维码；1:数值型永久二维码(此时expire参数无效)；2:字符串型永久二维码(此时expire参数无效)
	 * @param int $expire 临时二维码有效期，最大为604800秒
	 * @return array('ticket'=>'qrcode字串','expire_seconds'=>604800,'url'=>'二维码图片解析后的地址')
	 */
	public function getQRCode($scene_id, $type = 0, $expire = 2592000){
		if (!$this->access_token && !$this->checkAuth()) return false;
		if (!isset($scene_id)) return false;
		switch ($type) {
			case '0':
				if (!is_numeric($scene_id))
					return false;
				$action_name = 'QR_SCENE';
				$action_info = array('scene'=>(array('scene_id'=>$scene_id)));
				break;

			case '1':
				if (!is_numeric($scene_id))
					return false;
				$action_name = 'QR_LIMIT_SCENE';
				$action_info = array('scene'=>(array('scene_id'=>$scene_id)));
				break;

			case '2':
				if (!is_string($scene_id))
					return false;
				$action_name = 'QR_LIMIT_STR_SCENE';
				$action_info = array('scene'=>(array('scene_str'=>$scene_id)));
				break;

			default:
				return false;
		}

		$data = array(
			'action_name'    => $action_name,
			'expire_seconds' => $expire,
			'action_info'    => $action_info
		);
		if ($type) {
			unset($data['expire_seconds']);
		}

		$result = http_post(self::API_URL_PREFIX.self::QRCODE_CREATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
		if ($result) {
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result_error');
				return false;
			}
			return $json;
		}
		return false;
	}

	/**
	 * 获取二维码图片
	 * @param string $ticket 传入由getQRCode方法生成的ticket参数
	 * @return string url 返回http地址
	 */
	public function getQRUrl($ticket) {
		return self::QRCODE_IMG_URL.urlencode($ticket);
	}
	
	/**
	 * 长链接转短链接接口
	 * @param string $long_url 传入要转换的长url
	 * @return boolean|string url 成功则返回转换后的短url
	 */
	public function getShortUrl($long_url){
	    if (!$this->access_token && !$this->checkAuth()) return false;
	    $data = array(
            'action'=>'long2short',
            'long_url'=>$long_url
	    );
	    $result = http_post(self::API_URL_PREFIX.self::SHORT_URL.'access_token='.$this->access_token,safe_json_encode($data));
	    if ($result)
	    {
	        $json = json_decode($result,true);
	        if (!$json || !empty($json['errcode'])) {
	           watchdog($result, 'wx_result_error');
	            return false;
	        }
	        return $json['short_url'];
	    }
	    return false;
	}
	
	/* 网页授权 */
	
	/**
	 * oauth 授权跳转接口
	 * @param string $callback 回调URI
	 * @return string
	 */
	public function getOauthRedirect($callback,$state='',$scope='snsapi_userinfo'){
		return self::OAUTH_PREFIX.self::OAUTH_AUTHORIZE_URL.'appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
	}

	/**
	 * 通过code获取Access Token
	 * @return array {access_token,expires_in,refresh_token,openid,scope}
	 */
	public function getOauthAccessToken(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if (!$code) return false;
		$result = http_get(self::API_BASE_URL_PREFIX.self::OAUTH_TOKEN_URL.'appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code');
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result_error');
				return false;
			}
			$this->user_token = $json['access_token'];
			return $json;
		}
		return false;
	}

	/**
	 * 刷新access token并续期
	 * @param string $refresh_token
	 * @return boolean|mixed
	 */
	public function getOauthRefreshToken($refresh_token){
		$result = http_get(self::API_BASE_URL_PREFIX.self::OAUTH_REFRESH_URL.'appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$refresh_token);
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result_error');
				return false;
			}
			$this->user_token = $json['access_token'];
			return $json;
		}
		return false;
	}

	/**
	 * 获取授权后的用户资料
	 * @param string $access_token
	 * @param string $openid
	 * @return array {openid,nickname,sex,province,city,country,headimgurl,privilege,[unionid]}
	 * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
	 */
	public function getOauthUserinfo($access_token,$openid){
		$result = http_get(self::API_BASE_URL_PREFIX.self::OAUTH_USERINFO_URL.'access_token='.$access_token.'&openid='.$openid);
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result_error');
				return false;
			}
			return $json;
		}
		return false;
	}

	/**
	 * 上传临时素材，有效期为3天(认证后的订阅号可用)
	 * 注意：上传大文件时可能需要先调用 set_time_limit(0) 避免超时
	 * 注意：数组的键值任意，但文件名前必须加@，使用单引号以避免本地路径斜杠被转义
     * 注意：临时素材的media_id是可复用的！
	 * @param array $data {"media":'@Path\filename.jpg'}
	 * @param type 类型：图片:image 语音:voice 视频:video 缩略图:thumb
	 * @return boolean|array
	 */
	public function uploadMedia($data, $type) {
		if (!$this->access_token && !$this->checkAuth()) return false;
		//原先的上传多媒体文件接口使用 self::UPLOAD_MEDIA_URL 前缀
		$result = http_post(self::API_URL_PREFIX.self::MEDIA_UPLOAD_URL.'access_token='.$this->access_token.'&type='.$type,$data,true);
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result_error');
				return false;
			}
			return $json;
		}
		return false;
	}
}