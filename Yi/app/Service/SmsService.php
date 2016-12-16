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
	public function realSend($code, $phone)
	{
		return true;
		if (empty($code) || empty($phone)) {
			throw new \Min\MinException('smsService realSend parameter error',30000);
		} else {
			return $this->aliSms($phone, $code);
		}		
	}
	
	public function send($phone)
	{
		$sc =  $this->get($phone); 
		
		if (empty($sc) || 60 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
			
			$code = mt_rand(111111, 999999);
 
			$result = $this->realSend($code, $phone);

			$this->set($phone, ['code' => $code, 'ctime' => $_SERVER['REQUEST_TIME']]);
			
			return $this->success();

		} else {
			return $this->error('短信验证码已发送', 30113);
		}
	}
	
	public function get($name)
	{
		$regkey = '{sms:}'. $this->type. $name;
		return   CM('sms')->get($regkey);
	}
	
	public function set($name, $value)
	{
		$regkey = '{sms:}'. $this->type. $name;
		return   CM('sms')->set($regkey, $value);
	}
	
	public function move($name,$value)
	{	
		$regkey = '{sms:}'. $this->type. $name. ':'. $value['ctime'];
		CM('sms_out')->set($regkey, $value['code']);
	}
	
	public function check($arr)
	{
		$sc = $this->get($arr['phone']);	
		
		if (isset($sc['ctime']) && isset($sc['code'])) {
			
			if (600 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
				return $this->error('验证码已过期', 30111);
			} elseif ($sc['code'] == $arr['code']){
				return $this->success();
			} else {
				return $this->error('短信验证码错误', 30110);
			}	
		} else {
			return $this->error('短信验证码错误或超时，请重试', 30114);
		}
	}
	
	public function delete($name)
	{
		$sc = $this->get($name);
		$this->move($name,$sc);
		CM('sms')->delete('{sms:}'.$this->type.$name);
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
			print_r($response);
		}
		catch (ClientException  $e) {
			print_r($e->getErrorCode());   
			print_r($e->getErrorMessage());   
		}
		catch (ServerException  $e) {        
			print_r($e->getErrorCode());   
			print_r($e->getErrorMessage());
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