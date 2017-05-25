<?php
namespace App\Module\M;

use Min\App;

class MenuController extends \Min\Controller
{
	const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
	const AUTH_URL = '/token?grant_type=client_credential&';
	const MENU_CREATE_URL = '/menu/create?';
	const MENU_GET_URL = '/menu/get?';
	const MENU_DELETE_URL = '/menu/delete?';
	const MENU_ADDCONDITIONAL_URL = '/menu/addconditional?';
	const MENU_DELCONDITIONAL_URL = '/menu/delconditional?';
	const MENU_TRYMATCH_URL = '/menu/trymatch?';
	
	private $appid;
	private $appsecret;
	private $access_token;
 
	public function onConstruct()
	{
		$conf				= config_get('anyitime');
		$this->appid 		= $conf['appid'];
		$this->appsecret 	= $conf['appsecret'];
	}
	
	public function index_get()
	{
		if ('yb' != $_GET['master']) {
			exit;
		}
		
		$menu = [
     	    'button' => [
					[
					'type' 	=> 'view',
					'name' 	=> '首页',
					'url' 	=> 'https://m.anyitime.com'
				]
			]
     	];
		
		$result = $this->createMenu($menu);
		$this->response($result, 'JSON');
		
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
		
			$r = http_get(self::API_URL_PREFIX.self::AUTH_URL.'appid='.$this->appid.'&secret='.$this->appsecret);
			if ($r) {
				$result = json_decode($r, true);
				if (!$result || isset($result['errcode'])) {
					watchdog($r, 'wx_result');
					return false;
				}
				
				$expire = $result['expires_in'] ? intval($result['expires_in'])-100 : 7100;
				$cache->set($key, $result, $expire);
			}
		}
		 
		$this->access_token = $result['access_token'];
		return true;	
	}

	
	/**
	 * 创建菜单(认证后的订阅号可用)
	 * @param array $data 菜单数组数据
	 * example:
     * 	array (
     * 	    'button' => array (
     * 	      0 => array (
     * 	        'name' => '扫码',
     * 	        'sub_button' => array (
     * 	            0 => array (
     * 	              'type' => 'scancode_waitmsg',
     * 	              'name' => '扫码带提示',
     * 	              'key' => 'rselfmenu_0_0',
     * 	            ),
     * 	            1 => array (
     * 	              'type' => 'scancode_push',
     * 	              'name' => '扫码推事件',
     * 	              'key' => 'rselfmenu_0_1',
     * 	            ),
     * 	        ),
     * 	      ),
     * 	      1 => array (
     * 	        'name' => '发图',
     * 	        'sub_button' => array (
     * 	            0 => array (
     * 	              'type' => 'pic_sysphoto',
     * 	              'name' => '系统拍照发图',
     * 	              'key' => 'rselfmenu_1_0',
     * 	            ),
     * 	            1 => array (
     * 	              'type' => 'pic_photo_or_album',
     * 	              'name' => '拍照或者相册发图',
     * 	              'key' => 'rselfmenu_1_1',
     * 	            )
     * 	        ),
     * 	      ),
     * 	      2 => array (
     * 	        'type' => 'location_select',
     * 	        'name' => '发送位置',
     * 	        'key' => 'rselfmenu_2_0'
     * 	      ),
     * 	    ),
     * 	)
     * type可以选择为以下几种，其中5-8除了收到菜单事件以外，还会单独收到对应类型的信息。
     * 1、click：点击推事件
     * 2、view：跳转URL
     * 3、scancode_push：扫码推事件
     * 4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框
     * 5、pic_sysphoto：弹出系统拍照发图
     * 6、pic_photo_or_album：弹出拍照或者相册发图
     * 7、pic_weixin：弹出微信相册发图器
     * 8、location_select：弹出地理位置选择器
	 */
	public function createMenu($data){
		if (!$this->checkAuth()) return false;
		$result = http_post(self::API_URL_PREFIX.self::MENU_CREATE_URL.'access_token='.$this->access_token,safe_json_encode($data));
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				 watchdog($result, 'wx_result');
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 获取菜单(认证后的订阅号可用)
	 * @return array('menu'=>array(....s))
	 */
	public function getMenu(){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = http_get(self::API_URL_PREFIX.self::MENU_GET_URL.'access_token='.$this->access_token);
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || isset($json['errcode'])) {
				watchdog($result, 'wx_result');
				return false;
			}
			return $json;
		}
		return false;
	}

	/**
	 * 删除菜单(认证后的订阅号可用)
	 * @return boolean
	 */
	public function deleteMenu(){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = http_get(self::API_URL_PREFIX.self::MENU_DELETE_URL.'access_token='.$this->access_token);
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result');
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 创建个性化菜单(认证后的订阅号可用)
	 * @param array $data
	 * @return bool
	 *
	 */
	public function addconditionalMenu($data){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = http_post(self::API_URL_PREFIX.self::MENU_ADDCONDITIONAL_URL.'access_token='.$this->access_token,safe_json_encode($data));
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result');
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 删除个性化菜单(认证后的订阅号可用)
	 * @param $data {"menuid":"208379533"}
	 *
	 * @return bool
	 */
	public function delconditionalMenu($data){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = http_post(self::API_URL_PREFIX.self::MENU_DELCONDITIONAL_URL.'access_token='.$this->access_token,safe_json_encode($data));
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result');
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 测试个性化菜单匹配结果(认证后的订阅号可用)
	 * @param $data {"user_id":"weixin"} user_id可以是粉丝的OpenID，也可以是粉丝的微信号
	 *
	 * @return bool|array('button'=>array(....s))
	 */
	public function trymatchMenu($data){
		if (!$this->access_token && !$this->checkAuth()) return false;
		$result = http_post(self::API_URL_PREFIX.self::MENU_TRYMATCH_URL.'access_token='.$this->access_token,safe_json_encode($data));
		if ($result)
		{
			$json = json_decode($result,true);
			if (!$json || !empty($json['errcode'])) {
				watchdog($result, 'wx_result');
				return false;
			}
			return $json;
		}
		return false;
	}
	
}