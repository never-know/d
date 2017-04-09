<?php

class AnyiController extends \Min\Controller{
	
	protected  $weObj; 
	protected  $user;		//用户 WeixinUser 中的信息
	protected  $account;	//公众号 WeixinAccount 中的ID
	
	
	public function index_post()
	{
		include VENDOR_PATH.'/wechat/TPWechat.class.php';

		$options = config_get('anyitime');

		$this->weObj = new \TPWechat($options);
	

		$type = $this->weObj->getRevType();

		switch($type) {
			
			case \Wechat::MSGTYPE_TEXT:
					$this->text_reply();
					break;
			case \Wechat::MSGTYPE_EVENT:
			
				$t = $this->weObj->getRevEvent();

				if (\Wechat::EVENT_SUBSCRIBE == $t['event']) { 
				
					$this->subscribe_reply();
					
				} elseif (\Wechat::EVENT_UNSUBSCRIBE == $t['event']) {
					
					$this->unsubscribe_reply();
					
				} elseif (\Wechat::EVENT_MENU_CLICK == $t['event']) {

					$this->menuclick_reply($t['key']);
					 
				}
				
				break;
				
			case Wechat::MSGTYPE_IMAGE:
			default:
				$this->default_reply();
		}
		

		 
	}
	
	
	public function subscribe_reply() {
		
		// 未关注
		if (empty($this->user['subscribe'])) {
			$data = $this->weObj->getUserInfo($openid);
			$data['account_id'] = $this->account;
			$data['id'] = $model->add($data);
			if($data['id']) {
				$this->user=$data;
			}else{
				$this->weObj->text('无法提供该公众号服务')->reply();
			}
		}

		$model = M('WeixinReply');
		$result = $model->where('type = 4 and account_id ='.$this->account)->find();
		$this->real_reply($result);
		
		exit;

	}
	
	protected function unsubscribe_reply(){
		
		
		
	}
	protected function text_reply(){
	
		$key = $this->weObj->getRevContent();
		$result = M('WeixinReply')->where('type = 0 and account_id ='.$this->account.' and ( ( keywords = \''.$key.'\' and match_type=1) or ( keywords like \'%'.$key.'%\' and match_type = 0) )')->find();
		$this->real_reply($result);
	}
	
	
	protected function default_reply(){
	
		$result = M('WeixinReply')->where('type = 1 and account_id ='.$this->account)->find();
		$this->real_reply($result,0);
	}
	
	protected function real_reply($result,$default=1){
	
		if($result['o_msg_type'] == 'news'){
			
			$new=array();
			$url_data = unserialize($result['data']);
			if($result['ctl']!="url")
				$url = SITE_DOMAIN.wap_url("index",$result['ctl'],$url_data);
			else
				$url = $url_data['url'];
			$url = $this->weObj->getOauthRedirect($url,$this->account,'snsapi_base');
			
			$new[]=array('Title'=>$result['reply_news_title'],'Description'=>$result['reply_news_description'],'PicUrl'=>pathfix($result['reply_news_picurl']),'Url'=>$url);

			$relate_replys = M('WeixinReply')->join('fanwe_weixin_reply_relate as rr on fanwe_weixin_reply.id = rr.relate_reply_id')->where('rr.main_reply_id = '.$result['id'])->select(); 
         
  			foreach($relate_replys as $k=>$item){
				 if($item){
				 	
				 	$url_data = unserialize($item['data']);
				 	if($item['ctl']!='url')
				 		$url = SITE_DOMAIN.wap_url('index',$item['ctl'],$url_data);
				 	else
				 		$url = $url_data['url'];

					$new[]=array('Title'=>$item['reply_news_title'],'Description'=>$item['reply_news_description'],'PicUrl'=>pathfix($item['reply_news_picurl']),'Url'=>$url);
				 }
			}

			$this->weObj->news($new)->reply();
			
		}elseif($result['o_msg_type']=='text'){
			$content = htmlspecialchars_decode(stripslashes($result['reply_content']));
			$content = str_replace(array('<br/>','<br />','&nbsp;'), array("\n","\n",' '), $content);
			$this->weObj->text($content)->reply();
			
		}elseif($default){
			$this->default_reply();
		}else{
		
			$this->weObj->text('谢谢关注')->reply();
		}
	
	
	}
	
	
	protected function menuclick_reply($key){

		switch($key){
			
			case 'qcode':
		
				if(empty($this->user['user_id'])){
					$this->redirect('Bind/index'); 
					exit;
				}  
			
				require  APP_ROOT_PATH.'/mapi/Lib/core/common.php';
				$user_id = $this->user['user_id'];
				$u = M('User')->field('id,user_name,avatar,fx_mall_name')->where('id='.$user_id)->find();
				empty($u) && $u=array('id'=>0,'user_name'=>'','avatar'=>'','fx_mall_name'=>'');
				$shop_name =  empty($u['fx_mall_name'])?$u['user_name']:$u['fx_mall_name'];

				$uhead = get_muser_avatar($user_id,"big");
				$qrcode = gen_qrcode('http://'.$_SERVER['HTTP_HOST'].'/mob/#!shop/1/'.$user_id);
				$file = APP_ROOT_PATH.gen_card($uhead,$qrcode,$shop_name);
				$media_id = S(md5($file));
				if(empty($media_id)){
					$media = array('media'=>'@'.$file);
					$result = $this->weObj->uploadMedia($media,'image');
					if($result){  
						S(md5($file),$result['media_id'],3600*70);
						$media_id =   $result['media_id'];
					}
				} 

				$this->weObj->image($media_id)->reply();
			
			break;
			
			
		}
		
		
		
		
	}
	
	 
	
}