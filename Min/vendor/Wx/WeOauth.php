<?php
 
class WeOauth
{	
	const OAUTH_PREFIX = 'https://open.weixin.qq.com/connect/oauth2';
	const OAUTH_AUTHORIZE_URL = '/authorize?';
	
	private $appid;
	private $appsecret;
	private $access_token;
	
	public function _construct($conf)
	{
		$this->appid 		= $conf['appid'];
		$this->appsecret 	= $conf['appsecret'];
	}
	
	public function getOauthRedirect($callback, $state='', $scope='snsapi_userinfo') {
		return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
	}

	/**
	 * 通过code获取Access Token
	 * @return array {access_token,expires_in,refresh_token,openid,scope}
	 */
	 
	public function getOauthAccessToken() {
	
		$code = isset($_GET['code'])?$_GET['code']:'';
		if (!$code) return false;
		$result = http_get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code');
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || !empty($json['errcode'])) { 
				return false;
			}
			return $json['openid'];	 
		}
		
		return false;
	}
	
	private function http_get($url) {
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
	 
}



