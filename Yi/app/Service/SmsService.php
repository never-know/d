<?php
/*
两个数据库，regsms  regsms_out
*/
namespace App\Service;

class SmsService extends \Min\Service
{	

	private $type;
	private $conf;
	private $log_type = 'sms_error';
	private $pairs = [
						'reg' 			=> 1,
						'quicklogin' 	=> 2,
						'resetpwd'		=> 3,
						'notice' 		=> 4
					];
					
	public function __construct()
	{
		$this->conf = get_config(get_config('sms'));
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
		return $this;
	}
	
	public function send($phone)
	{
		if (false == $this->firewall($phone)) {
			return $this->error('服务受限', 30207);
		}
		
		$sc =  $this->get($phone);  
		
		if (empty($sc) || 120 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
			
			$code = mt_rand(111111, 999999);
			
			if ($this->realSend($phone, $code) && $this->set($phone, $code)) {
				return $this->success();				
			} else {
				return $this->error('服务受限', 30112);
			}	
			
		} else {
			//return $this->error('短信验证码已发送, 如未收到，请'. (120 + $sc['ctime'] - $_SERVER['REQUEST_TIME']).'秒后重发或者使用微信扫码登陆', 30113);
			watchdog('will not send again in 120 seconds', $this->log_type, 'NOTICE');
			return $this->success();
		}
	}
	
	public function check($arr)
	{
		$sc = $this->get($arr['phone']);	
		
		if (isset($sc['ctime']) && isset($sc['code'])) {
			
			if (900 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
				return $this->error('短信验证码已过期，请重新发送', 30111);
			} elseif ($sc['code'] == $arr['code']) {
			
				$sql = 'UPDATE {sms} set used = 1 where id = '. $sc['id'];
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
		if (empty($code) || 1 !== validate('phone', $phone)) {
			throw new \Min\MinException('smsService realSend parameter error', 20102);
		} else {
		
			$style = (($this->type == 'notice') ? '_notice' : '_yzm');
			
			$method = get_config('sms') .$style; 
			
			return $this->{$method}($phone, $code);
		}		
	}
	
	private function get($phone)
	{
		$result = session_get($this->getKey($phone));
		
		if (empty($result)) {
		
			$sql = 'SELECT * FROM {sms} WHERE phone = '.$phone .' and type = '. $this->pairs[$this->type]. ' AND used = 0 ORDER BY ctime DESC LIMIT 1';
			$result	= $this->query($sql);
		}
		
		return $result;
	}
	
	private function set($phone, $code)
	{	
		$hash = md5($_SERVER['HTTP_USER_AGENT']);
		$ip = ip_address();
		
		$sql = 'INSERT INTO  {sms}  (phone, type, ip, used,  ctime ,code, hash) values ('
				. implode(',', [$phone, $this->pairs[$this->type], $ip, 0, $_SERVER['REQUEST_TIME'], "'". $code. "'", "'". $hash. "')"]);
		
		$result	= $this->query($sql);
		session_inrc('sms_'. $this->type. '_times');
		
		$key = $this->getKey($phone);
		session_set($key, ['code'=> $code, 'ctime'=> $_SERVER['REQUEST_TIME'], 'type' => $this->pairs[$this->type], 'id' => $result]);	
		
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
		return '{sms:}'. $this->type. $phone.$ip.$hash;
	}
	
	private function aliyun_yzm($phone, $code)
	{
		return true;
		include VENDOR_PATH. '/aliyun-php-sdk/aliyun-php-sdk-core/Config.php';
		
		$params = $this->conf;  
		$iClientProfile = \DefaultProfile::getProfile($params['profile'], $params['appkey'], $params['secretkey']);        
		$client = new \DefaultAcsClient($iClientProfile);    
		$request = new \Sms\Request\V20160927\SingleSendSmsRequest();
		
		$params = $params[$this->type];
		$request->setSignName($params['signname']);					//签名名称
		$request->setTemplateCode($params['templateCode']);			//模板code
		$request->setRecNum($phone);								//目标手机号
		$request->setParamString('{"code":"'.$code.'"}');			//模板变量，数字一定要转换为字符串
		try {
			$response = $client->getAcsResponse($request);
			$response['code'] = $code;
			$response['phone'] = $phone;
			watchdog($response, $this->log_type, 'NOTICE');
			return true;
		} catch (\Throwable  $t) {
			watchdog($t, $this->log_type, 'NOTICE');  
			return false;
		}
	}
	
	private function alidayuSms()
	{	
		include VENDOR_PATH . '/Alidayu/TopSdk.php';

		$c = new \TopClient($this->appkey, $this->secretkey);
		
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req->setSmsType('normal');
		$req->setSmsFreeSignName('注册验证');
		$p = json_encode(['code'=> (string) $arr['code'],'product'=>'【张三测试】']);
		$req->setSmsParam($p);
		$req->setRecNum($arr['phone']);
		$req->setSmsTemplateCode('SMS_5059050');
		
		return $c->execute($req);
		 
		if (isset($result->code)) {
			// $result->code = 15  ==> 每个号码每小时最多发送7次 
			return $this->error('发送失败', 30112);
		} else {
			$this->set($phone, ['code' => $code, 'ctime' => $_SERVER['REQUEST_TIME']]);
			return $this->success();
		}

	}
	
	private function firewall($phone)
	{
		if ($this->type == 'notice') {
			return true;
		}
		
		// 同一 SESSION 不超过 10
		if( intval(session_get('sms_'. $this->type. '_times')) > 10) {
			watchdog('session '. $this->type .'> 10', $this->log_type, 'NOTICE');
			return false;
		}
		
		// 无效地址
		$ip = ip_address();
		if(false == $ip || session_get('ip_address') != $ip) {
			watchdog('invalid ip address : 30104', $this->log_type, 'NOTICE');
			return false; 
		}

		// 同一手机号码发送的注册验证码 二小时不超过3次
		$cache = $this->cache('sms');
		
		$key = $this->getKey($phone);
		$result = $cache->get($key);
		watchdog($result);
		watchdog($cache->getDisc());
		watchdog($cache->getDisc() == $result);
		
		if ($cache->getDisc() === $result) {	// 注意 字符串和0和布尔值比较
			$sql = 'SELECT COUNT(1) as no FROM {sms}  WHERE  phone =  '.$phone .' AND type = '. $this->pairs[$this->type]. ' AND used = 0 AND ctime > '. ($_SERVER['REQUEST_TIME'] - 7200);
		
			$result = $this->query($sql);
			$result = intval($result[0]['no']);
			$cache->set($key, $result, 7210);   // 7200m 过期时间
		} elseif (false == $result) {
			$cache->set($key, 0, 7210);
		}
		
		if (intval($result) > 2) {
			watchdog('message has been sended to same phone 3 times in 2 hours', $this->log_type, 'NOTICE');
			return false; 
		}
	
		//同一 IP 下发送的未成功注册的 短信数目 2小时不超过300，
		// 记录规则， 
		
		$key = $this->getKey($phone, $ip);
		$result = $cache->get($key);
		
		if ($cache->getDisc() === $result) {
			$sql = 'SELECT COUNT(1) as no FROM {sms}  WHERE  type = '. $this->pairs[$this->type]. ' AND ip = '. $ip. ' AND used = 0 AND ctime > '. ($_SERVER['REQUEST_TIME'] - 7200) ;
		
			$result = $this->query($sql);
			$result = intval($result[0]['no']);
			$cache->set($key, $result, 7210);   // 7210m 过期时间
		} elseif (false == $result) {
			$cache->set($key, 0, 7210);
		}
		
		
		if (intval($result) > 300) {
			watchdog('message has been sended 300 times from same ip in 2 hours', $this->log_type, 'NOTICE');
			return false; //$this->error('短信发送受限', 30116);
		}	
		
		// 同一IP 同一UA  不超过 50 2小时内
		
		$hash = md5($_SERVER['HTTP_USER_AGENT']);
		
		$key = $this->getKey($phone, $ip, $hash);
		
		$result = $cache->get($key);

		if ($cache->getDisc() === $result) {
		
			$sql = 'SELECT COUNT(1) as no FROM {sms}  WHERE  type = '. $this->pairs[$this->type]. ' AND ip = '. ip_address(). ' AND used = 0 AND ctime > '. ($_SERVER['REQUEST_TIME'] - 7200) .' AND hash = \''. $hash .'\'';
		
			$result = $this->query($sql);
			$result = intval($result[0]['no']);
			$cache->set($key, $result, 7210);   // 7210m 过期时间
		} elseif (false == $result) {
			$cache->set($key, 0, 7210);
		}

		if (intval($result) > 50) {
			watchdog('message has been  sended 50 times from same hash in 2 hours', $this->log_type, 'NOTICE');
			return false; //$this->error('短信发送受限', 30116);
		}	
		
		return true;		
	}
	
}