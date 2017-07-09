<?php
namespace App\Module\M;

use Min\App;

class AuthController extends \Min\Controller
{
	public function onConstruct($redirect = true)
	{ 
		$open_id = session_get('annyi_open_id');

		if (!isset($open_id)) {
			$open_id = $this->getOpenid();
		}
		
		if (empty($open_id)) {
			exit('can not get open_id');
		}
		
		//return $this->login($redirect);  
	}
	
	
	public function __call($method, $params){
		$open_id = session_get('annyi_open_id');
		redirect('http://wx.zj.annyi.cn?token='. $open_id);
		exit;
	}
	
	public function getOpenid()
	{
		$wx = $this->getWX();
	
		if (isset($_GET['state']) && $_GET['state'] == '6666') {
			$r = $wx->getOauthAccessToken();
			$open_id = $r['openid']??0;
		} else {
			$open_id = 0;
		} 
		
		if (!empty($open_id)) session_set('annyi_open_id', $open_id);
		return $open_id;
		 
	}
 
	final public function getWX()
	{
		require VENDOR_PATH. '/Wx/WxBase.php';
		return new \WeBase('anyitime');
	}
	

}