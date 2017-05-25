<?php
 
class WeQrcode
{
	const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
	const AUTH_URL = '/token?grant_type=client_credential&';
	const QRCODE_CREATE_URL='/qrcode/create?';
	const QR_SCENE = 0;
	const QR_LIMIT_SCENE = 1;
	const QRCODE_IMG_URL='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
	const SHORT_URL='/shorturl?';
	
	private $appid;
	private $appsecret;
	private $access_token;
 
	public function _construct($conf)
	{
		$this->appid 		= $conf['appid'];
		$this->appsecret 	= $conf['appsecret'];
	}
	
	/**
	 * GET 请求
	 * @param string $url
	 */
	private function http_get($url){
		$oCurl = curl_init();
		if(stripos($url,"https://")!==FALSE){
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}

	/**
	 * POST 请求
	 * @param string $url
	 * @param array $param
	 * @param boolean $post_file 是否文件上传
	 * @return string content
	 */
	private function http_post($url,$param,$post_file=false){
		$oCurl = curl_init();
		if(stripos($url,"https://")!==FALSE){
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
	        if (PHP_VERSION_ID >= 50500 && class_exists('\CURLFile')) {
	            	$is_curlFile = true;
	        } else {
	        	$is_curlFile = false;
	            	if (defined('CURLOPT_SAFE_UPLOAD')) {
	                	curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, false);
	            	}
	        }
		if (is_string($param)) {
	            	$strPOST = $param;
	        }elseif($post_file) {
	            	if($is_curlFile) {
		                foreach ($param as $key => $val) {
		                    	if (substr($val, 0, 1) == '@') {
		                        	$param[$key] = new \CURLFile(realpath(substr($val,1)));
		                    	}
		                }
	            	}
			$strPOST = $param;
		} else {
			$aPOST = array();
			foreach($param as $key=>$val){
				$aPOST[] = $key."=".urlencode($val);
			}
			$strPOST =  join("&", $aPOST);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($oCurl, CURLOPT_POST,true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取access_token
	 * @param string $appid 如在类初始化时已提供，则可为空
	 * @param string $appsecret 如在类初始化时已提供，则可为空
	 * @param string $token 手动指定access_token，非必要情况不建议用
	 */
	private function checkAuth($appid='',$appsecret='',$token=''){
		if (!$appid || !$appsecret) {
			$appid = $this->appid;
			$appsecret = $this->appsecret;
		}
		if ($token) { //手动指定token，优先使用
		    $this->access_token=$token;
		    return $this->access_token;
		}

		$authname = 'wechat_access_token'.$appid;
		if ($rs = $this->getCache($authname))  {
			$this->access_token = $rs;
			return $rs;
		}

		$result = $this->http_get(self::API_URL_PREFIX.self::AUTH_URL.'appid='.$appid.'&secret='.$appsecret);
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || isset($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			}
			$this->access_token = $json['access_token'];
			$expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;
			$this->setCache($authname,$this->access_token,$expire);
			return $this->access_token;
		}
		return false;
	}

	
	/**
	 * 创建二维码ticket
	 * @param int|string $scene_id 自定义追踪id,临时二维码只能用数值型
	 * @param int $type 0:临时二维码；1:数值型永久二维码(此时expire参数无效)；2:字符串型永久二维码(此时expire参数无效)
	 * @param int $expire 临时二维码有效期，最大为604800秒
	 * @return array('ticket'=>'qrcode字串','expire_seconds'=>604800,'url'=>'二维码图片解析后的地址')
	 */
	public function getQRCode($scene_id,$type=0,$expire=604800){
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

		$result = $this->http_post(self::API_URL_PREFIX.self::QRCODE_CREATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
		if ($result) {
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
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
	    $result = $this->http_post(self::API_URL_PREFIX.self::SHORT_URL.'access_token='.$this->access_token,safe_json_encode($data));
	    if ($result)
	    {
	        $json = json_decode($result,true);
	        if (!$json || !empty($json['errcode'])) {
	            $this->errCode = $json['errcode'];
	            $this->errMsg = $json['errmsg'];
	            return false;
	        }
	        return $json['short_url'];
	    }
	    return false;
	}


}