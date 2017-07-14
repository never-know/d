<?php
namespace App\Module\M;

use Min\App;

class WxController extends \Min\Controller
{
	private $conf = [];
	private $_msg;
	private $_receive;
	private $access_token;
	
	public function onConstruct()
	{
		$this->conf = config_get('anyitime');
	}
	
	public function index_get()
	{
		// 验证服务器
		if (!empty($_GET['echostr'])) {
			if ($this->checkSignature()) {
				exit($_GET['echostr']);
			}
		}
		exit('error');
	}
	
	public function index_post()
	{
		if (!$this->checkSignature()) {
			exit;
		}
		
		$this->_receive = $this->getRev();
		
		$mt 	= strtolower($this->_receive['MsgType']);
		
		$func = (('event' == $mt) ? ('process_event_'.strtolower($this->_receive['Event'])) : ('process_'. $mt)); 
		 
		$this->{$func}();
		
		$this->reply();
		
		exit;	 
	}
	
	
	private function process_text() 
	{
		$content = $this->getRevContent();
		$this->text('您好，祝您生活愉快');
	}
	
	private function process_location() 
	{
		$content = $this->getRevGeo();
		
		$this->text('谢谢，已获得您的位置信息');
	}
	
	private function process_event_subscribe() 
	{
		$scene_id = $this->getRevSceneId();
		
		watchdog($scene_id, 'wx_sceneid', 'INFO');
		
		// 添加  yi_user_wx 
		
		$param						= [];
		$param['wx_ip']				= ip_address();
		$param['parent_id']			= (empty($scene_id) ? 0 : base_convert($scene_id, 36, 10));
		$param['open_id']			= $this->getRevFrom();
		$param['subscribe_time'] 	= $_SERVER['REQUEST_TIME'];
		$param['subscribe_status']	= 3;

		$add =  $this->request('\\App\\Service\\Wuser::addUserByOpenid', $param);
		
		if ($add['statusCode'] == 30205) {
			$this->text('谢谢您再次关注，您可以先<a href="https://m.anyitime.com/bind.html" > 绑定手机号码 </a>, 祝您生活愉快');
		} elseif ($add['statusCode'] == 30207) {
			$this->text('谢谢您再次关注, 祝您生活愉快');
		} else ($add['statusCode'] == 30208 || $add['statusCode'] == 1 ) {
			$this->text('谢谢关注，您可以先<a href="https://m.anyitime.com/bind.html" >绑定手机号码</a>,祝您生活愉快');
		} else {
			watchdog($param, 'wx_subscribe_error', 'ERROR', $add);
			$this->text('谢谢关注,祝您生活愉快');
		}
	}
	
	private function process_event_unsubscribe() 
	{
		$open_id = $this->getRevFrom();
		$add =  $this->request('\\App\\Service\\Wuser::unsubscribe', $open_id);
		$this->text('see you later');
	}
	
	private function process_event_location() 
	{
		$this->text('thank you');	
	}
	
	private function process_event_scan() 
	{
		exit('');	
	}
	
	private function process_event_click() 
	{
		exit('');
	}
	
	private function process_event_view() 
	{
		exit('');
	}

	private function checkSignature()
	{
        $signature 	= $_GET['signature']??'';
	    //$signature 	= $_GET['msg_signature']??$signature; //如果存在加密验证则用加密验证段
        $timestamp 	= $_GET['timestamp']??'';
        $nonce 		= $_GET['nonce']??'';

		$token 		= $this->conf['token'];
		
		$tmpArr 	= array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr 	= sha1(implode($tmpArr));
		 
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	 
	/**
     * 获取微信服务器发来的信息
     */
	private function getRev()
	{
		$postStr = file_get_contents("php://input");
		watchdog($postStr, 'wx_receive');
		if (!empty($postStr)) {
			return (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		} else {
			exit('');
		}
		 
	}

	/**
	 * 获取消息发送者
	 */
	private function getRevFrom() 
	{
		if (isset($this->_receive['FromUserName']))
			return $this->_receive['FromUserName'];
		else
			return false;
	}

	/**
	 * 获取消息接受者
	 */
	private function getRevTo() 
	{
		if (isset($this->_receive['ToUserName']))
			return $this->_receive['ToUserName'];
		else
			return false;
	}

	/**
	 * 获取接收消息的类型
	 */
	private function getRevType() 
	{
		if (isset($this->_receive['MsgType']))
			return $this->_receive['MsgType'];
		else
			return false;
	}

	/**
	 * 获取消息ID
	 */
	private function getRevID() 
	{
		if (isset($this->_receive['MsgId']))
			return $this->_receive['MsgId'];
		else
			return false;
	}

	/**
	 * 获取消息发送时间
	 */
	private function getRevCtime() 
	{
		if (isset($this->_receive['CreateTime']))
			return $this->_receive['CreateTime'];
		else
			return false;
	}

	/**
	 * 获取接收消息内容正文
	 */
	private function getRevContent()
	{
		if (isset($this->_receive['Content']))
			return $this->_receive['Content'];
		else if (isset($this->_receive['Recognition'])) //获取语音识别文字内容，需申请开通
			return $this->_receive['Recognition'];
		else
			return false;
	}
	
	/**
	 * 获取接收地理位置
	 */
	private function getRevGeo()
	{
		if (isset($this->_receive['Location_X'])){
			return array(
				'x'=>$this->_receive['Location_X'],
				'y'=>$this->_receive['Location_Y'],
				'scale'=>$this->_receive['Scale'],
				'label'=>$this->_receive['Label']
			);
		} else
			return false;
	}

	/**
	 * 获取上报地理位置事件
	 */
	private function getRevEventGeo()
	{
        	if (isset($this->_receive['Latitude'])){
        		 return array(
				'x'=>$this->_receive['Latitude'],
				'y'=>$this->_receive['Longitude'],
				'precision'=>$this->_receive['Precision'],
			);
		} else
			return false;
	}

	/**
	 * 获取接收事件推送
	 */
	private function getRevEvent()
	{
		if (isset($this->_receive['Event'])){
			$array['event'] = $this->_receive['Event'];
		}
		if (isset($this->_receive['EventKey'])){
			$array['key'] = $this->_receive['EventKey'];
		}
		if (isset($array) && count($array) > 0) {
			return $array;
		} else {
			return false;
		}
	}

	/**
	* 获取二维码的场景值
	*/
	private function getRevSceneId ()
	{
		if (isset($this->_receive['EventKey'])) {
			return substr($this->_receive['EventKey'], 8);
		} else{
			return false;
		}
	}

	private static function xmlSafeStr($str)
	{
		return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';
	}

	/**
	 * 数据XML编码
	 * @param mixed $data 数据
	 * @return string
	 */
	private static function data_to_xml($data) 
	{
	    $xml = '';
	    foreach ($data as $key => $val) {
	        is_numeric($key) && $key = "item id=\"$key\"";
	        $xml    .=  "<$key>";
	        $xml    .=  ( is_array($val) || is_object($val)) ? self::data_to_xml($val)  : self::xmlSafeStr($val);
	        list($key, ) = explode(' ', $key);
	        $xml    .=  "</$key>";
	    }
	    return $xml;
	}

	/**
	 * XML编码
	 * @param mixed $data 数据
	 * @param string $root 根节点名
	 * @param string $item 数字索引的子节点名
	 * @param string $attr 根节点属性
	 * @param string $id   数字索引子节点key转换的属性名
	 * @param string $encoding 数据编码
	 * @return string
	*/
	private function xml_encode($data, $root='xml', $item='item', $attr='', $id='id', $encoding='utf-8') 
	{
	    if(is_array($attr)){
	        $_attr = array();
	        foreach ($attr as $key => $value) {
	            $_attr[] = "{$key}=\"{$value}\"";
	        }
	        $attr = implode(' ', $_attr);
	    }
	    $attr   = trim($attr);
	    $attr   = empty($attr) ? '' : " {$attr}";
	    $xml   = "<{$root}{$attr}>";
	    $xml   .= self::data_to_xml($data, $item, $id);
	    $xml   .= "</{$root}>";
	    return $xml;
	}

	/**
	 * 过滤文字回复\r\n换行符
	 * @param string $text
	 * @return string|mixed
	 */
	private function _auto_text_filter($text) 
	{
		//if (!$this->_text_filter) return $text;
		return str_replace("\r\n", "\n", $text);
	}

	/**
	 * 设置回复消息
	 * Example: $obj->text('hello')->reply();
	 * @param string $text
	 */
	private function text($text='')
	{
		$msg = array(
			'ToUserName' 	=> $this->getRevFrom(),
			'FromUserName'	=>	$this->getRevTo(),
			'MsgType'		=>	'text',
			'Content'		=>	$this->_auto_text_filter($text),
			'CreateTime'	=>	time()
		);
		$this->_msg = $msg;	
		 
	}
	/**
	 * 设置回复消息
	 * Example: $obj->image('media_id')->reply();
	 * @param string $mediaid
	 */
	private function image($mediaid = '')
	{
		$msg = array(
			'ToUserName' 	=> $this->getRevFrom(),
			'FromUserName'	=> $this->getRevTo(),
			'MsgType'		=> 'image',
			'Image'			=> array('MediaId'=>$mediaid),
			'CreateTime'	=> time(),
		);
		$this->_msg = $msg;	
	}
	
	/**
	 * 设置回复图文
	 * @param array $newsData
	 * 数组结构:
	 *  array(
	 *  	"0"=>array(
	 *  		'Title'=>'msg title',
	 *  		'Description'=>'summary text',
	 *  		'PicUrl'=>'http://www.domain.com/1.jpg',
	 *  		'Url'=>'http://www.domain.com/1.html'
	 *  	),
	 *  	"1"=>....
	 *  )
	 */
	private function news($newsData = [])
	{
		$count = count($newsData);

		$msg = array(
			'ToUserName' 	=> $this->getRevFrom(),
			'FromUserName'	=> $this->getRevTo(),
			'MsgType'		=> 'news',
			'CreateTime'	=> time(),
			'ArticleCount'	=> $count,
			'Articles'		=> $newsData
		);
		$this->_msg = $msg;	
	}

	/**
	 *
	 * 回复微信服务器, 此函数支持链式操作
	 * Example: $this->text('msg tips')->reply();
	 * @param string $msg 要发送的信息, 默认取$this->_msg
	 * @param bool $return 是否返回信息而不抛出到浏览器 默认:否
	 */
	private function reply($return = false)
	{
		 
		if (empty($this->_msg)) return false;   //防止不先设置回复内容，直接调用reply方法导致异常

		$xmldata=  $this->xml_encode($this->_msg);
		watchdog($xmldata, 'wx_replay');
		if ($return)
			return $xmldata;
		else
			echo $xmldata;
	}
 
	function __call($name, $args)
	{
		exit('error');
    }
	
	


}