<?php
namespace App\Module\Www;

use Min\App;

class QrcodeController extends \Min\Controller
{ 
	 
	public function index_get()
	{		 
		$openid = $_SESSION['openid'];
		$refer 	= $_SERVER['HTTP_REFERER'];
		if (empty($openid) || empty($refer)) {
			exit;
		}
		
		preg_match($refer, '/([a-z0-9]+)\.html)', $match);
		$info = \shareid_encode($matches[1]);
		if ($info) {
		
			// write view log, update view count,  decrease  account balance;
			// add salary;
			
			// view log  logid, userid , article_id , type, time, openid, 
			// 文章 被分享次数， 被察看次数
			// 用户分享被察看次数， 察看详情
			

			
		}
		$result =  $this->request('\\App\\Service\\Account::resetPwd', $params, $this::EXITALL);
	 	 
	}
}