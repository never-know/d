<?php
namespace App\Module\M;

use Min\App;

class BindController extends \Min\Controller
{
	public function index_get()
	{
		$result['meta'] = ['title' =>'绑定手机号码'];
		$this->layout($result, 'layout_m');
	}
	
	public function index_post()
	{
		//$result['meta'] = ['title' =>'绑定手机号码'];
		if (!validate('phone', $_POST['phone'])) {
			$this->error('手机号码格式错误', 1);
		}
		
		
		$this->layout($result, 'layout_m');
	}
	
	public function fastclick_get()
	{
		
		$result['meta'] = ['title' =>'fastclick'];
		$this->layout($result, null);
	}
}