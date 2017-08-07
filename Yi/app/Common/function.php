<?php

function article_tags($key = null)
{
	static $arr = [0=> '全部', 1 => '品牌文化传播', 2 => '吃喝玩乐', 3 => '生活服务', 127 => '其他'];
	return $key ? $arr[$key] : $arr;
}
 
function article_order($key = null)
{
	static $arr = [1 => '默认排序', 2 => '开始时间降序', 3 => '结束时间降序'];
	return $key ? $arr[$key] : $arr;
}

function message_icon($key = null)
{
	static $arr = [1 => '默认排序', 2 => '开始时间降序', 3 => '结束时间降序'];
	return $key ? $arr[$key] : $arr;
}

function withdraw_account_info($value)
{
	static $arr = [1 => '支付宝', 2 => '微信'];

	$result = $value['real_name'] . '<br>';
	$result .=  (($value['account_type'] == 3) ? $value['extra'] : $arr[$value['account_type']]);
	$result .= '&nbsp;&nbsp;' ;
	if ($value['account_type'] == 3) {
		$info = '尾号&nbsp;&nbsp;' . substr($value['account_name'], -4);
	} else {
		$email = strpos($value['account_name'], '@');
		if (empty($email)) {		// phone
			$start 	= 3;
			$length = 4;
		} elseif ($email < 4) { 	// abc@qq.com a@b.com ab@b.com  
			$start 	= max($email-1, 1);
			$length = 1;
		} else {					// abcd@qq.com
			$start 	= 3;
			$length = $email-3;
		}
		
		//$info = substr($value['account_name'], 0 ,3) . '****' . substr($value['account_name'], $email);
		$info = substr_replace($value['account_name'], '****', $start, $length);
	}	
	
	return $result.$info;
}

function balance_type($type) 
{
	switch ($type) {
		case 2:
			return '分享收益';
		case 3:
			return '一级团队收益';
		case 4:
			return '二级团队收益';
		case 11:
			return '提现';
		default:
			return '';
	
	}

}

function get_avater($wx_id, $prefix = '')
{
	return ( $prefix . '/avater/' . implode('/', str_split(base_convert($wx_id, 10, 36), 2)) . '.jpg');

}

function img_url($img) {
	return strtr($img,['m://' => ASSETS_URL])；
}

function region_get($region_id)
{
	static $region;
	static $run = 0;
	if (empty($region)) {
		$cache_setting = config_get('cache');
		$value = $cache_setting['region'] ?? $cache_setting['default'];
		$region = \Min\App::getService($value['bin'], $value['key'])->get('regionAll');;
	}
	
	if (empty($region)) {
		if (0 == $run) {
			min_socket('https://m.anyitime.com/region/cache.html');
			$run = 1;
		}
		return '加载中...';
	}
	return $region[$region_id] ?? '全国';
}

/* 
 * id格式 ：{article_id}{6}{time}{6}{type}{1}{salt}{2}{md5}{6}{uid}{n}
 * 最小6位36进制对应的十进制 100000 => 60466176; 最大zzzzzz => 2176782335
 * article_id 61000000 到 2176782335  转化为 6位字符串
 * user_id  同上，n位字符串,  {id*n}{n}
 * share_time   时间戳减 （1487411575-61000000）= 1325000000
 * param id : int;
 * type : 0 , 1 
 */
 
// 6亿用户， 7亿数据 生成 24位字符串

function share_encode($content_id)
{ 
	$user_id = session_get('USER_ID');
	
	if (empty($user_id)) {
		return ['timeline' => '', 'friend' => ''];
	}
	
	//zzzzzzz: 78364164095  1000000: 2176782336
	$time 			= $_SERVER['REQUEST_TIME'] - 1295672286;	//	197889303 
	$user_id		= $user_id + 103656280;	
	$content_id		= $content_id  + 103656280;	
	 
	$salt	=   mt_rand(37, 1295);  // 108 ;109   // 129
	if ($salt < 88) {				// $salt2 < $salt3	差值 12
		$salt2 = 108 - $salt;		//	range:	71-21
		$salt3 = 120 - $salt;		//	range:	83-33		
	
	} elseif ($salt < 130){			// $salt2 < $salt3 差值 13
		$salt2 = 183 - $salt;		//	range:	95-54
		$salt3 = 196 - $salt;		//	range:	108-67
	} else {						//	$salt2 > $salt3 差值 1-11			
		$salt2  = ($salt%108)?:108;		// 	range: 	22-108
		$salt3  = ($salt%109)?:109;	 	//	range:	21-107
		// special process
		if ($salt2 < 21 ) {
			$salt2 = 59 - $salt2;		//	range:	(58-39) +  (21-30)
		}
		if ($salt3 < 21) {
			$salt3 = 79 - $salt3;		//	range:	(109-99) + (78-59)
		}	 
	}

	$salt_36 	= base_convert($salt, 10, 36);
	$rand 		= mt_rand(97, 121);
	 
	$parts 		= [];
	$parts[]	= $salt_36[0];
	$parts[]	= base_convert(((($time % 286898) ?: 286898) * $salt2 + $user_id) * $salt3, 10, 36);	//	579113185	7位
	$parts[]	= base_convert($time * ((($salt%60)?:60) + 10), 10, 36);								//	2046年	7位
	$parts[]	= base_convert(((($time % 183868) ?: 183868) * $salt3 + $content_id) * $salt2, 10, 36);	//  695186871	7位
	$parts[]	= $salt_36[1];	
	
	$str = strtr(implode('', $parts), 'g', '_');
	
	$a 	= mt_rand(97, 122);
	$b 	= mt_rand(97, 121); 
	$timeline 	= (($a%2 == 0)?chr($a):chr($a+1)).$str;
	$friend 	= (($b%2 == 1)?chr($b):chr($b+1)).$str;

	return ['timeline' => $timeline, 'friend' => $friend];	
}

function share_decode($shareid)
{
	$param['share_type']	= ord($shareid[0])%2;
	
	$shareid = strtr($shareid, '_', 'g');
	
	$salt_36 		= $shareid[1].$shareid[23];
	$salt			= base_convert($salt_36, 36, 10);
	
	if ($salt < 88) {					// $salt2 < $salt3	差值 12
		$salt2 = 108 - $salt;			//	range:	71-21
		$salt3 = 120 - $salt;			//	range:	83-33		
	
	} elseif ($salt < 130) {			// $salt2 < $salt3 差值 13
		$salt2 = 183 - $salt;			//	range:	95-54
		$salt3 = 196 - $salt;			//	range:	108-67
	} else {							//	$salt2 > $salt3 差值 1-11			
		$salt2  = ($salt%108)?:108;		// 	range: 	22-108
		$salt3  = ($salt%109)?:109;	 	//	range:	21-107

		// special process
		if ($salt2 < 21 ) {
			$salt2 = 59 - $salt2;		//	range:	(58-39) +  (21-30)
		}
		
		if ($salt3 < 21) {
			$salt3 = 79 - $salt3;		//	range:	(109-99) + (78-59)
		}	 
	}
	
	$time = base_convert(substr($shareid, 9, 7), 36, 10);
	$time = $time/((($salt%60)?:60) + 10);
	$param['share_time'] = $time + 1295672286;
	
	$uid = base_convert(substr($shareid, 2, 7), 36, 10);
	$param['user_id']	= $uid/$salt3 - ((($time % 286898) ?: 286898) * $salt2) - 103656280;
	
	$aid = base_convert(substr($shareid, -8, 7), 36, 10);
	
	$param['content_id']	= $aid/$salt2 - ((($time % 183868) ?: 183868) * $salt3) - 103656280;
	
	return $param;
}
  