<?php
namespace App\Module\M;

use Min\App;

class MyController extends \App\Module\M\BaseController
{
	public function team_get()
	{
		$result = $this->request('\\App\\Service\\Wuser::member', session_get('wx_id'));
		
		$result['body']['level2'] = 0;
		
		if (!empty($result['body']['list'])) {

			$phones 	= array_column($result['body']['list'], 'phone');
			$benefit 	= $this->request('\\App\\Service\\Balance::benefit', ['phone' =>$phones, 'type' => 3]);
			
			foreach ($benefit['body'] as $b) {
				$a[$b['phone']] = $b['benefit'];
			}
			
			foreach ($result['body']['list'] as &$value) {
				$value['benefit'] 	= $a[$value['phone']]??0;
				$value['phone'] 	= substr_replace($value['phone'], '****', 3, 4);
				$result['body']['level2'] += $value['children'];
			}
			
		}
		$result['body']['meta'] = ['title' =>'一级成员列表'];
		$this->response($result);
	}
	
	public function subteam_get()
	{
		$id = intval(App::getArgs());
		if ( $id < 1) {
			$this->error('参数错误', 30000);
		}
		
		$wuser = $this->request('\\App\\Service\\Wuser');
 
		$user_info = $wuser->checkAccount($id, 'wx_id');
		
		if (isset($user_info['body']['parent_id']) && $user_info['body']['parent_id'] == session_get('wx_id')) {
			$result = $wuser->member($id);
			if (!empty($result['body']['list'])) {
				
				$phones 	= array_column($result['body']['list'], 'phone');
				$benefit 	= $this->request('\\App\\Service\\Balance::benefit', ['phone' =>$phones, 'type' => 4]);
				foreach ($benefit['body'] as $b) {
					$a[$value['phone']] = $b['benfit'];
				}
			
				foreach ($result['body']['list'] as &$value) {
					$value['benfit'] 	= $a[$value['phone']]??0;
					$value['phone'] = substr_replace($value['phone'], '****', 3, 4);
				}
			}
			
			$result['body']['phone'] = substr_replace($user_info['body']['phone'], '****', 3, 4);
			$result['body']['meta'] = ['title' =>'二级成员列表'];
			$this->response($result);	
			
		} else {
			$this->error('用户不存在或您无权查阅该用户信息', 401);
		}
		
	}
	
	public function message_get()
	{
		$list = $this->request('\\App\\Service\\Message::list', session_get('USER_ID'));
		$list['body']['meta'] = ['title' =>'消息列表'];
		$this->response($list);
	
	}
	
	public function share_get()
	{
		$list 	= $this->request('\\App\\Service\\Share::logs', session_get('USER_ID'), self::EXITNONE, true);
		$readed = $this->request('\\App\\Service\\Share::readed', session_get('USER_ID'));
		
		$list['body']['readed'] = $readed['body']['count'];
		$list['body']['meta'] = ['title' =>'分享记录'];
		$this->response($list);
	
	}
	
}