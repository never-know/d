<?php
/*
两个数据库，regsms  regsms_out
*/
namespace App\Service;

class SmsService extends \Min\Service
{	
	protected $log_type = 'sms_error';
	protected $log_level = 'NOTICE';
	private $type;
	private $conf;
	private $timeout = 120;
	private $pairs = [
						'reg' 			=> 1,
						'bind' 			=> 2,
						'quicklogin' 	=> 3,
						'resetpwd'		=> 4,
						'notice' 		=> 5,
						
					];
					
	public function __construct()
	{
		$this->conf = config_get(config_get('sms'));
		if (empty($this->conf)) {	
			throw new \Min\MinException('SMS参数未配置', 20105);
		}
	}
	
	public function init($type)
	{
		if (empty($this->pairs[$type])) {	
			throw new \Min\MinException('无效的SMS类型', 20103);
		}
		if (empty($this->conf[$type])) {	
			throw new \Min\MinException('SMS参数未配置, type:'. $type, 20105);
		}
		
		$this->type = $type;
	}
	
	public function send($phone)
	{
		if (false == $this->firewall($phone)) {
			return $this->error('服务受限', 30207);
		}
		
		$sc =  $this->get($phone);  
		
		if (empty($sc) || $this->timeout < ($_SERVER['REQUEST_TIME'] - $sc['send_time'])) {
			
			$code = mt_rand(111111, 999999);
			$result = $this->realSend($phone, $code);
			watchdog($result);
			if ($result->Code == 'OK') {
				$this->set($phone, $code);
				return $this->success();				
			} else {
				return $this->error('服务受限', 30112);
			}	
			
		} else {
			//return $this->error('短信验证码已发送, 如未收到，请'. (120 + $sc['send_time'] - $_SERVER['REQUEST_TIME']).'秒后重发或者使用微信扫码登陆', 30113);
			$this->watchdog('will not send again in '. $this->timeout. ' seconds');
			return $this->success();
		}
	}
	
	public function check($arr)
	{
		$sc = $this->get($arr['phone']);	
		
		if (isset($sc['send_time']) && isset($sc['code'])) {
			
			if (900 < ($_SERVER['REQUEST_TIME'] - $sc['send_time'])) {
				return $this->error('短信验证码已过期，请重新发送', 30111);
			} elseif ($sc['code'] == $arr['code']) {
			
				$sql = 'UPDATE {{sms}} set used = 1 where id = '. $sc['id'];
				$this->query($sql);
				
				$cache = $this->cache('sms');
				
				$key =  $this->getKey($arr['phone']);
				$cache->decr($key);
				
				$ip = ip_address();
				$key = $this->getKey($arr['phone'], $ip);
				$cache->decr($key);
				
				$hash = md5($_SERVER['HTTP_USER_AGENT']);
				$key = $this->getKey($arr['phone'], $ip, $hash);
				$cache->decr($key);
				
				session_derc('sms_'. $this->type. '_times');
				
				return $this->success();
				
			} else {
				return $this->error('短信验证码错误', 30110);
			}	
		} else {
			return $this->error('短信验证码错误或超时，请重新发送', 30114);
		}
	}
	
	private function realSend($phone, $code)
	{
		if (empty($code) || !validate('phone', $phone)) {
			throw new \Min\MinException('SmsService realSend parameter error', 20102);
		} else {
		
			$style = (($this->type == 'notice') ? '_notice' : '_yzm');
			
			$method = config_get('sms') .$style; 
			
			return $this->{$method}($phone, $code);
		}		
	}
	
	private function get($phone)
	{
		$result = session_get($this->getKey($phone));
		
		if (empty($result)) {
		
			$sql = 'SELECT * FROM {{sms}} WHERE phone = '.$phone .' and type = '. $this->pairs[$this->type]. ' AND used = 0 ORDER BY send_time DESC LIMIT 1';
			$result	= $this->query($sql);
		}
		
		return $result;
	}
	
	private function set($phone, $code)
	{	
		$hash = md5($_SERVER['HTTP_USER_AGENT']);
		$ip = ip_address();
		
		$sql = 'INSERT INTO  {{sms}}  (phone, type, ip, used,  send_time ,code, hash) values ('
				. implode(',', [$phone, $this->pairs[$this->type], $ip, 0, $_SERVER['REQUEST_TIME'], "'". $code. "'", "'". $hash. "')"]);
		
		$result	= $this->query($sql);
		session_inrc('sms_'. $this->type. '_times');
		
		$key = $this->getKey($phone);
		session_set($key, ['code'=> $code, 'send_time'=> $_SERVER['REQUEST_TIME'], 'type' => $this->pairs[$this->type], 'id' => $result['id']]);	
		
		$cache = $this->cache('sms');
		$cache->incr($key);
		
		$key = $this->getKey($phone, $ip);
		$cache->incr($key);
		
		$key = $this->getKey($phone, $ip, $hash);
		$cache->incr($key);
 
		return true;
	}
	
	private function getKey($phone, $ip = '', $hash = '')
	{
		return '{sms}:'. $this->type. $phone.$ip.$hash;
	}
	
	private function aliyun_yzm($phone, $code)
	{
		$templateCode = $this->conf[$this->type];
		$sms = new \Vendor\Ali\AliSms($this->conf['AccessKeyId'], $this->conf['AccessKeySecret']);
		return $sms->sendSms($this->conf['signname'], $templateCode, $phone, ['code' => $code]); 
	}
	
	 
	private function firewall($phone)
	{
		if ($this->type == 'notice') {
			return true;
		}
		
		// 同一 SESSION 不超过 10
		if( intval(session_get('sms_'. $this->type. '_times')) > 10) {
			$this->watchdog('session '. $this->type .' > 10');
			return false;
		}
		
		// 无效地址
		$ip = ip_address();
		if (false == $ip || session_get('ip_address') != $ip) {
			$this->watchdog('invalid ip address : 30104');
			return false; 
		}

		// 同一手机号码发送的注册验证码 二小时不超过3次
		$cache = $this->cache('sms');
		
		$key = $this->getKey($phone);
		$result = $cache->get($key);
		 
		if ($cache->getDisc() === $result) {	// 注意 字符串和0和布尔值比较
			$sql = 'SELECT COUNT(1) as no FROM {{sms}}  WHERE  phone =  '.$phone .' AND type = '. $this->pairs[$this->type]. ' AND used = 0 AND send_time > '. ($_SERVER['REQUEST_TIME'] - 7200);
		
			$result = $this->query($sql);
			$result = intval($result[0]['no']);
			$cache->set($key, $result, 7210);   // 7200m 过期时间
		} elseif (false == $result) {
			$cache->set($key, 0, 7210);
		}
		
		if (intval($result) > 2) {
			$this->watchdog('message has been sended to same phone 3 times in 2 hours');
			return false; 
		}
	
		//同一 IP 下发送的未成功注册的 短信数目 2小时不超过300，
		// 记录规则， 
		
		$key = $this->getKey($phone, $ip);
		$result = $cache->get($key);
		
		if ($cache->getDisc() === $result) {
			$sql = 'SELECT COUNT(1) as no FROM {{sms}}  WHERE  type = '. $this->pairs[$this->type]. ' AND ip = '. $ip. ' AND used = 0 AND send_time > '. ($_SERVER['REQUEST_TIME'] - 7200) ;
		
			$result = $this->query($sql);
			$result = intval($result[0]['no']);
			$cache->set($key, $result, 7210);   // 7210m 过期时间
		} elseif (false == $result) {
			$cache->set($key, 0, 7210);
		}
		
		
		if (intval($result) > 300) {
			$this->watchdog('message has been sended 300 times from same ip in 2 hours');
			return false; //$this->error('短信发送受限', 30116);
		}	
		
		// 同一IP 同一UA  不超过 50 2小时内
		
		$hash = md5($_SERVER['HTTP_USER_AGENT']);
		
		$key = $this->getKey($phone, $ip, $hash);
		
		$result = $cache->get($key);

		if ($cache->getDisc() === $result) {
		
			$sql = 'SELECT COUNT(1) as no FROM {{sms}}  WHERE  type = '. $this->pairs[$this->type]. ' AND ip = '. ip_address(). ' AND used = 0 AND send_time > '. ($_SERVER['REQUEST_TIME'] - 7200) .' AND hash = \''. $hash .'\'';
		
			$result = $this->query($sql);
			$result = intval($result[0]['no']);
			$cache->set($key, $result, 7210);   // 7210m 过期时间
		} elseif (false == $result) {
			$cache->set($key, 0, 7210);
		}

		if (intval($result) > 50) {
			$this->watchdog('message has been  sended 50 times from same hash in 2 hours');
			return false; //$this->error('短信发送受限', 30116);
		}	
		
		return true;		
	}
	
}