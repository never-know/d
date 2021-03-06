<?php
/*

登陆：

1 成功，

失败：
		2 验证码问题
		0 用户名密码错误 
		101 用户名密码错误且显示验证码

*/


namespace App\Module\Passport;

use Min\App;

class LoginController
{    

    public function __construct($action){  
		if ($action == 'islogged') {
			$this->islogged();
		} elseif (isset($_SESSION['logined']) && true === $_SESSION['logined']){
			header('Location: '.HOME_PAGE);
		} elseif (isset($_POST['login']) && $_POST['login'] == 1) {
			$this->dologin();			
		} else {	
			switch ($action) {
				case 'popup':
					$this->popup();	
					break;
				default:
				   $this->display();	
			}
		} 
		exit;
    }

	private function display(){
		layout('type-login');
	}
	
	private function popup(){
		view();
	}
   
    private function dologin(){
		
		
			
		 
        if (empty($_POST['loginname']) || empty($_POST['loginpwd'])) {
            usr_error( 0,'用户名或密码不能为空');
        } elseif (validate('quotes',$_POST['loginname'])) {
            usr_error(0, '用户名或密码错误');
        } else{ 
		
			$cache_error_key = '{loginerror:}'.$_POST['loginname'];
			$code_ok = true;
			$miss = $this->showcaptcha($cache_error_key);
			
			if( 1 === $miss['show']){
				$code = new \min\inc\captcha;
				$code_ok = $code->checkcode($_POST['code'],'login');
			}
			
			if( true === $code_ok ){
			
				$service = app::service('account');
				if( 1 === $miss['cache']){
					$cache = app::cache('loginerror')->connect();
					$result = $cache->get(md5($_POST['loginname']));
				}
				
				if(!isset($result) || false == $result){
					$result = $service->checkpwd($_POST['loginname']);
				}
				
				if( false === $result){
					trigger_error('系统忙，请重试',E_USER_ERROR);
				}elseif(null == $result){
					$result['holder'] = 1;
				}elseif (isset($result['pwd']) && password_verify($_POST['loginpwd'], $result['pwd'])){
				
					$service->inituser($result);
					if( 1 == $miss['cache']) {
						$cache->delete($cache_error_key,md5($_POST['loginname']));
						unset($_SESSION['loginerror']);
					}
					app::response(1); 
				}
				
				if( 0 === $miss['cache'] )	{
					$cache = app::cache('loginerror')->connect();
					$cache->set(md5($_POST['loginname']),$result,1200);
				}
				$loginerror = $cache->incr($cache_error_key);
			
				if( 1 == $loginerror ) $cache->setTimeout($cache_error_key,7200);
				
				// 需要显示验证码 code =2

				isset($_SESSION['loginerror'])? $_SESSION['loginerror']++ : $_SESSION['loginerror'] = 1 ;

				$status = (  2 < $loginerror || 7 < $_SESSION['loginerror']  ) ? 101 : 0 ;
			
				app::usrerror( $status,'用户名或密码错误');	
				
			}
		}
			   
    }
	
   	private function initUser($result){
	
		if($uid > 0) {
			// 每次登陆都需要更换session id ;
			session_regenerate_id();
			setcookie('nickname', $name, 0, '/', COOKIE_DOMAIN);
			//app::usrerror(-999,ini_get('session.gc_maxlifetime'));
			// 此处应与 logincontroller islogged 相同
			
			setcookie('logged', 1, time() + ini_get('session.gc_maxlifetime') - 10, '/', COOKIE_DOMAIN);
			$_SESSION['logined'] = true;
			$_SESSION['UID'] = $result['uid'];
			$_SESSION['user'] =$result;

		}
	}

    public function islogged(){
	
       $result = (int) (isset($_SESSION['logined']) && $_SESSION['logined'] == true) ;
	   
		setcookie('logged', $result ,  $result * (time()+ini_get('session.gc_maxlifetime')-10),'/',COOKIE_DOMAIN);
        app::response($result);
    }
	
	
	private function showcaptcha($name){
		 
		 $var1 = (int) app::cache('loginerror')->get($name) ;
		 
		 if( $var1 >9 ){
			app::usrerror( 0 ,'账户已锁定，请2小时后再登录');
		 }
		$var2 = isset($_SESSION['loginerror'])?$_SESSION['loginerror']:0;
	
		$x=0;$y=0;
	
		if( 0 < $var1 ) $x = 1;
		if( 2 < $var1 || 7 < $var2 ) $y =1;
		return ['cache'=>$x,'show'=>$y];
		
	}
	
	
}