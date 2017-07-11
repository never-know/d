<?php
namespace App\Module\M;

use Min\App;

class MyController extends \App\Module\M\BaseController
{
	public function team_get()
	{
		$result = $this->request('\\App\\Service\\Wuser::member', session_get('wx_id'));
		
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
 
		$result = $wuser->checkAccount($id, 'wx_id');
		
		if (isset($result['body']['parent_id']) && $result['body']['parent_id'] == session_get('wx_id')) {
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