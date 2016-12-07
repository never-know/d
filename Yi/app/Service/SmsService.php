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
		if (empty($code) || empty($phone)) {
			throw new \Exception('smsService realSend parameter error');
		} else {
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
		}		
	}
	
	public function send($phone)
	{
		$sc =  $this->get($phone); 
		
		if (empty($sc) || 60 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
			
			$code = mt_rand(111111, 999999);
 
			$result = $this->realSend($code, $phone);
			if (isset($result->code)) {
				// $result->code = 15  ==> 每个号码每小时最多发送7次 
				return $this->error('发送失败', 30112);
			} else {
				$this->set($phone, ['code' => $code, 'ctime' => $_SERVER['REQUEST_TIME']]);
				return $this->success('发送成功');
			}
		} else {
			return $this->error('短信验证码已发送', 30113);
		}
	}
	
	public function get($name)
	{
		$regkey = '{sms:}' .$this->type .$name;
		return   CM('sms')->get($regkey);
	}
	
	public function set($name, $value)
	{
		$regkey = '{sms:}'.$this->type.$name;
		return   CM('sms')->set( $regkey, $value);
	}
	public function move($name,$value)
	{	
		$regkey = '{sms:}'.$this->type.$name.':'.$value['ctime'];
		CM('sms_out')->set($regkey,$value['code']);
	}
	
	public function check($arr)
	{
		$sc = $this->get($arr['phone']);	
		
		if (isset($sc['ctime']) && isset($sc['code'])) {
			
			if (600 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
				return $this->error('验证码已过期', 30110);
			} elseif ($sc['code'] == $arr['code']){
				return $this->success();
			} else {
				return $this->error('验证码错误', 30110);
			}
			
		} else {
			return $this->error('验证码错误或超时，请重试', 30110);
		}
	}
	
	public function delete($name)
	{
		$sc = $this->get($name);
		$this->move($name,$sc);
		app::cache('sms')->delete('{sms:}'.$this->type.$name);
	}
	
}