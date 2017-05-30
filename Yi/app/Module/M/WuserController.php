<?php
 
class WuserController extends \Min\Controller
{
	const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
	const AUTH_URL = '/token?grant_type=client_credential&';
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
	
	private $appid;
	private $appsecret;
	private $access_token;
 
	public function _construct($conf)
	{
		$this->appid 		= $conf['appid'];
		$this->appsecret 	= $conf['appsecret'];
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
		$key = 'wechat_access_token_'.$this->appid;
		$result = $cache->get($key, true);
		
		if (empty($result) || $cache->getDisc() === $result) {
		
			$r = $this->http_get(self::API_URL_PREFIX.self::AUTH_URL.'appid='.$this->appid.'&secret='.$this->appsecret);
			if ($r) {
				$result = json_decode($r, true);
				if (!$result || isset($result['errcode'])) {
					watchdog($r, 'wx_result');
					return false;
				}
				
				$expire = ($result['expires_in'] ? intval($result['expires_in'])-100 : 7100);
				$cache->set($key, $result, $expire);
			}
		}
		 
		$this->access_token = $result['access_token'];
		return true;
		
	}

	

	
		/**
	 * 批量获取关注用户列表
	 * @param unknown $next_openid
	 */
	public function getUserList($next_openid=''){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = $this->http_get(self::API_URL_PREFIX.self::USER_GET_URL.'access_token='.$this->access_token.'&next_openid='.$next_openid);
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

	/**
	 * 获取关注者详细信息
	 * @param string $openid
	 * @param string $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
	 * @return array {subscribe,openid,nickname,sex,city,province,country,language,headimgurl,subscribe_time,[unionid]}
	 * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
	 */
	public function getUserInfo($openid, $lang = 'zh_CN'){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = $this->http_get(self::API_URL_PREFIX.self::USER_INFO_URL.'access_token='.$this->access_token.'&openid='.$openid.'&lang='.$lang);
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
	
	/**
	 * 批量获取关注者详细信息
	 * @param array $openids user_list{{'openid:xxxxxx'},{},{}}
	 * @return array user_info_list{subscribe,openid,nickname,sex,city,province,country,language,headimgurl,subscribe_time,[unionid]}{}{}...
	 * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
	 */
	public function getUsersInfo($openids){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = $this->http_post(self::API_URL_PREFIX.self::USERS_INFO_URL.'access_token='.$this->access_token,json_encode($openids));
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

	/**
	 * 设置用户备注名
	 * @param string $openid
	 * @param string $remark 备注名
	 * @return boolean|array
	 */
	public function updateUserRemark($openid,$remark){
	    if (!$this->access_token && !$this->checkAuth()) return false;
	    $data = array(
			'openid'=>$openid,
			'remark'=>$remark
	    );
	    $result = $this->http_post(self::API_URL_PREFIX.self::USER_UPDATEREMARK_URL.'access_token='.$this->access_token,safe_json_encode($data));
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
	 * 获取用户分组列表
	 * @return boolean|array
	 */
	public function getGroup(){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = $this->http_get(self::API_URL_PREFIX.self::GROUP_GET_URL.'access_token='.$this->access_token);
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

	/**
	 * 获取用户所在分组
	 * @param string $openid
	 * @return boolean|int 成功则返回用户分组id
	 */
	public function getUserGroup($openid){
	    if (!$this->access_token && !$this->checkAuth()) return false;
	    $data = array(
	            'openid'=>$openid
	    );
	    $result = $this->http_post(self::API_URL_PREFIX.self::USER_GROUP_URL.'access_token='.$this->access_token,safe_json_encode($data));
	    if ($result)
	    {
	        $json = json_decode($result,true);
	        if (!$json || !empty($json['errcode'])) {
	            $this->errCode = $json['errcode'];
	            $this->errMsg = $json['errmsg'];
	            return false;
	        } else
                if (isset($json['groupid'])) return $json['groupid'];
	    }
	    return false;
	}

	/**
	 * 新增自定分组
	 * @param string $name 分组名称
	 * @return boolean|array
	 */
	public function createGroup($name){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$data = array(
				'group'=>array('name'=>$name)
		);
		$result = $this->http_post(self::API_URL_PREFIX.self::GROUP_CREATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
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
	 * 更改分组名称
	 * @param int $groupid 分组id
	 * @param string $name 分组名称
	 * @return boolean|array
	 */
	public function updateGroup($groupid,$name){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$data = array(
				'group'=>array('id'=>$groupid,'name'=>$name)
		);
		$result = $this->http_post(self::API_URL_PREFIX.self::GROUP_UPDATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
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
	 * 移动用户分组
	 * @param int $groupid 分组id
	 * @param string $openid 用户openid
	 * @return boolean|array
	 */
	public function updateGroupMembers($groupid,$openid){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$data = array(
				'openid'=>$openid,
				'to_groupid'=>$groupid
		);
		$result = $this->http_post(self::API_URL_PREFIX.self::GROUP_MEMBER_UPDATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
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
	 * 批量移动用户分组
	 * @param int $groupid 分组id
	 * @param string $openid_list 用户openid数组,一次不能超过50个
	 * @return boolean|array
	 */
	public function batchUpdateGroupMembers($groupid,$openid_list){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$data = array(
				'openid_list'=>$openid_list,
				'to_groupid'=>$groupid
		);
		$result = $this->http_post(self::API_URL_PREFIX.self::GROUP_MEMBER_BATCHUPDATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
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