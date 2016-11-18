<?php
/*
两个数据库，regsms  regsms_out
*/
namespace App\Service;

use Min\App;

class Sms
{	
	private $appkey ='23314175';
	private $secretkey ='e1aecb8048afb006b3d03937b8743972' ;
	private $type;

	public function __construct($type = '')
	{
		if (!empty($type)) {
			$this->type = $type;
		}
	}
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}
	public function regSend($arr)
	{
		if (empty($arr['code']) || empty($arr['phone'])) {
			throw new \Exception('parameter error');
		}else{
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
	
	public function get($name)
	{
		$regkey = '{sms:}' .$this->type .$name;
		return   App::cache('sms')->get($regkey);
	}
	
	public function set($name, $value)
	{
		$regkey = '{sms:}'.$this->type.$name;
		return   App::cache('sms')->set( $regkey, $value);
	}
	public function move($name,$value)
	{	
		$regkey = '{sms:}'.$this->type.$name.':'.$value['ctime'];
		App::cache('sms_out')->set($regkey,$value['code']);
	}
	
	public function check($name,$code)
	{
		$sc = $this->get($name);		
		if (isset($sc['ctime']) && isset($sc['code'])) {
			if (600 < ($_SERVER['REQUEST_TIME'] - $sc['ctime'])) {
				app::usrerror( 3,'验证码超时，请重新发送' );
			}elseif($sc['code'] == $code){
				return true;
			}else{
				app::usrerror( 3,'验证码错误，请重试' );
			}	
		}else{
			app::usrerror( 3,'验证码错误或超时，请重试' );
		}
	}
	
	public function delete($name)
	{
		$sc = $this->get($name);
		$this->move($name,$sc);
		app::cache('sms')->delete('{sms:}'.$this->type.$name);
	}
	
}