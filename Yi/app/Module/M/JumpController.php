<?php
namespace App\Module\M;

use Min\App;

class JumpController extends \App\Module\M\BaseController
{

	public function onConstruct($redirect = 2) 
	{
		parent::onConstruct(2);
	}
 
	public function __call($method, $params)
	{
		$key = substr($method,0, -4);

		$pairs = [
			'zj' => ['wx.zj.annyi.cn6688', 'http://wx.zj.annyi.cn/auth/']
		];

		$new_key = bin2hex(rc4($pairs[$from][0],  implode('_', [$from, $state, $current, $open_id])));
		 
		redirect($pairs[$from][1] . $new_key.'.html');
		
		exit;
	}
	
	public function getOpenid($state)
	{
		require VENDOR_PATH. '/Wx/WxBase.php';
		
		$wx = new \WeBase('anyitime');
	
		if (!empty($_GET['state']) && $_GET['state'] == $state) {
			$r = $wx->getOauthAccessToken();
			$open_id = $r['openid']??0;
		} else {
			$open_id = 0;
		} 
		
		if (!empty($open_id)) session_set('open_id', $open_id);
		return $open_id;	 
	}
  
}