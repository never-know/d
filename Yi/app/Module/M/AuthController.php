<?php
namespace App\Module\M;

use Min\App;

class AuthController extends \Min\Controller
{
	public function __call($method, $params)
	{
		$key = rc4('anyitime.com6688', hex2bin(substr($method,0, -4)));
		
		list($from, $state, $time) = explode('_', $key, 3);
		
		$current = time();
		
		if ($time > $current || $time < $current - 30) {
			$open_id = 0;
		} else {
			$open_id = session_get('open_id');
		}
		
		if (!isset($open_id)) {
			$open_id = $this->getOpenid($state);
		}

		$pairs = [
			'annyi' => ['wx.zj.annyi.cn6688', 'http://wx.zj.annyi.cn/auth/']
		];
		
		if (empty($pairs[$from])) {
			exit('error');
		}
		
		$new_key = bin2hex(rc4($pairs[$from][0],  implode('_', [$state, $current, $open_id])));
		 
		redirect($pairs[$from][1] . $new_key.'.html');
		
		exit;
	}
	
	public function getOpenid($state)
	{
		$wx = new \Vendor\Wx\WxBase('anyitime');
	
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