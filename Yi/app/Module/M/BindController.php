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
	public function fastclick_get()
	{
		$result['meta'] = ['title' =>'绑定手机号码'];
		$this->layout($result, 'layout_m');
	}
}