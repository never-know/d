<?php
/*
两个数据库，regsms  regsms_out
*/
namespace App\Service;

class Sms extends Min\Service
{	
	private $appkey ='23314175';
	private $secretkey ='e1aecb8048afb006b3d03937b8743972' ;
	private $type;

	public function init($type)
	{
		$this->type = $type;
		return $this;
	}
	
	public function send($phone)
	{
		$sc =  $this->get($phone); 
		
		if (empty($sc) || 60 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
			
			$code = mt_rand(111111, 999999);
			
			if ($this->realSend($code, $phone)) {
				$this->set($phone, ['code' => $code, 'ctime' => $_SERVER['REQUEST_TIME']]);
				return $this->success();
			} else {
				return $this->error('发送失败', 30112);
			}			

		} else {
			return $this->error('短信验证码已发送', 30113);
		}
	}
	
	public function check($arr)
	{
		$sc = $this->get($arr['phone']);	
		
		if (isset($sc['ctime']) && isset($sc['code'])) {
			
			if (600 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
				return $this->error('验证码已过期，请重新发送', 30111);
			} elseif ($sc['code'] == $arr['code']){
				return $this->success();
			} else {
				return $this->error('短信验证码错误', 30110);
			}	
		} else {
			return $this->error('短信验证码错误或超时，请重新发送', 30114);
		}
	}
	
	private function realSend($code, $phone)
	{
		if (empty($code) || empty($phone)) {
			throw new \Min\MinException('smsService parameter error', 20102);
		} else {
			return $this->aliSms($phone, $code);
		}		
	}
	
	private function get($name)
	{
		return  CM('sms')->get($this->getKey($name));
	}
	
	private function set($name, $value)
	{
		return  CM('sms')->set($this->getKey($name), $value);
	}
	
	private function move($name, $value)
	{	
		$regkey = '{sms:}'. $this->type. $name. ':'. $value['ctime'];
		$result = CM('sms_out')->set($regkey, $value['code']);
		if (!$result) watchdog($regkey.' move fail ', 'redis', 'NOTICE');
	}
	
	private function delete($name)
	{
		$result = CM('sms')->delete($this->getKey($name));
		if (!$result) watchdog($regkey.' set fail ', 'redis', 'NOTICE');
	}
	
	private function getKey($name)
	{
		return '{sms:}'. $this->type. $name;
	}
	private function aliSms($phone, $code){
		
		include VENDOR_PATH. 'aliyun-php-sdk-core/Config.php';
		use Sms\Request\V20160927 as Sms;            
		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAIMERSujNfnvLi", "3VlFq7xmdaKxfcz5DfguYLfJ813Zfz");        
		$client = new DefaultAcsClient($iClientProfile);    
		$request = new Sms\SingleSendSmsRequest();
		$request->setSignName("注册验证码");	/*签名名称*/
		$request->setTemplateCode("SMS_33465600");	/*模板code*/
		$request->setRecNum($phone);	/*目标手机号*/
		$request->setParamString('{"code":"'.$code.'"}');	/*模板变量，数字一定要转换为字符串*/
		try {
			$response = $client->getAcsResponse($request);
			watchdog($response);
			return true;
		}
		catch (\ClientException  $e) {
			watchdog($e);  
			return false;
		}
		catch (\ServerException  $e) {        
			watchdog($e); 
			return false;			
		}
		
	}
	
	private function alidayuSms(){
		
		include VENDOR_PATH . '/Alidayu/TopSdk.php';

		$c = new \TopClient($this->appkey, $this->secretkey);
		
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		//$req->setExtend("123456");
		$req->setSmsType('normal');
		$req->setSmsFreeSignName('注册验证');
		$p = json_encode(['code'=> (string) $arr['code'],'product'=>'【张三测试】']);
		$req->setSmsParam($p);
		$req->setRecNum($arr['phone']);
		$req->setSmsTemplateCode('SMS_5059050');
		
		return $c->execute($req);
		/*
		
			if (isset($result->code)) {
				// $result->code = 15  ==> 每个号码每小时最多发送7次 
				return $this->error('发送失败', 30112);
			} else {
				$this->set($phone, ['code' => $code, 'ctime' => $_SERVER['REQUEST_TIME']]);
				return $this->success();
			}
		
		
		*/
		
	}
	
}