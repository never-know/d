<?php
namespace App\Module\M;

use Min\App;

class MyController extends \App\Module\M\WbaseController
{
	public function team_get()
	{
		$result = $this->request('\\App\\Service\\Wuser::member', session_get('wxid'));
		
		if (isset($result['body']['list'])) {
			foreach ($result['body']['list'] as &$value) {
				$value['phone'] = substr_replace($value['phone'], '****', 3, 4);
			}
		}
		
		$this->response($result);
	}
	
	public function subteam_get()
	{
		$id = intval(App::getArgs());
		if ( $id < 1) {
			$this->error('参数错误', 30000);
		}
		
		$wuser = $this->request('\\App\\Service\\Wuser');
 
		$result = $wuser->checkAccount($id, 'wxid');
		
		if (isset($result['body']['pid']) && $result['body']['pid'] == session_get('wxid')) {
			$result = $wuser->member($id);
			if (isset($result['body']['list'])) {
				foreach ($result['body']['list'] as &$value) {
					$value['phone'] = substr_replace($value['phone'], '****', 3, 4);
				}
			}
			$result['body']['phone'] = substr_replace($wuser['body']['phone'], '****', 3, 4);
			$this->response($result);	
		} else {
			$this->error('用户不存在或您无权查阅该用户信息', 401);
		}
		
	}
	
	public function message_get()
	{
		$this->success([]);
	
	}
	
	public function share_get()
	{
		$this->success([]);
	
	}
	
}