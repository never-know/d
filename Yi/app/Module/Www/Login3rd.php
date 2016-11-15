<?php

/**
* 第三方登录回调函数  
* 
*  
* 
* @property 类型 $prop 属性描述
* 
* @author ${AUTHOR}     
* @package application.components（参见路径别名）
* 
*/
class Login3rdController extends BaseController {
	
	
	public function loginAction(){
 
		$type = $_GET['type'];
		$classname = '\\Oauth\\'.$type;
		$Oauth = new $classname;
		$Oauth->login();
 		
	}

	public function qqAction(){
 
		$Oauth = new \Oauth\qq;
		$Oauth->callback();
		$Oauth->get_openid();
		$user = $Oauth->get_user_info();
		\Common\Util::log($user);
		$user = json_decode($user,true);
		
		if($user['ret']>0){ 
			$this->error('获取用户信息失败');
		}else{ 
			$param['openid'] = $_SESSION['qq_openid'];
			$param['platform'] = 1;
			$param['icon'] = $user['figureurl_qq_1'];
			$param['nick'] = $user['nickname'];
			$this->sendRequest($param);
		}
		exit;
 		
	}

	public function wxAction(){
		$Oauth = new \Oauth\wx;
		$Oauth->callback();
		$user = $Oauth->get_user_info();
		\Common\Util::log($user);
		if(isset($user['errcode'])){ 
			$this->error('获取用户信息失败');
		}else{ 
			$param['openid'] = $_SESSION['wx_openid'];
			$param['platform'] = 2;
			$param['icon'] = $user['headimgurl'];
			$param['nick'] = $user['nickname'];
			$this->sendRequest($param);
		}
 		
	}
	
	public function wbAction(){
	 
		$Oauth = new \Oauth\wb;
		$Oauth->callback();
 
		$user = $Oauth->get_user_info();
		\Common\Util::log($user);
		if(isset($user['error_code'])){ 
			$this->error('获取用户信息失败');
		}else{ 
			$param['openid'] = $_SESSION['wb_uid'];
			$param['platform'] = 3;
			$param['icon'] = $user['profile_image_url'];
			$param['nick'] = $user['name'];
			$this->sendRequest($param);
		}
 		
	}

	public function regAction(){
		
		$type = intval($_POST['type']);
		
		$param  = $_SESSION['params'.$type];
		 
		if(empty($param)) $this->error('参数错误');
		$param['captcha'] = $_POST['captcha'];
		$param['mobile']  = $_POST['name'];
		$this->sendRequest($param,'ThirdpartyLoginB');
		
	}
	
	private function sendRequest($param,$model='ThirdpartyLoginA'){

		$result = $this->request($model,$param);	

		if( $result['statusCode']>=200 && $result['statusCode'] <300 ){ 
			$_SESSION['is_login'] = true;
			$_SESSION['user'] = $result['result'][0]['body'][0];
			$_SESSION['token'] = $result['token'];
			$return = array('status'=>1);
			if($model == 'ThirdpartyLoginB') {
				$return['return'] = '/user/user';
				$this->output($return);
			}else{
				$this->redirect('/user/user');
			}
		}else{
			if(isset($result['result'][0]['body'][0]['unRegister'])){
				$_SESSION['params'.$param['platform']] = $param;
				$this->getView()->display($this->getViewPath().'/user/bind.html',array('type'=>$param['platform']));
				exit;
			}
		}
		 
	}
    

}
