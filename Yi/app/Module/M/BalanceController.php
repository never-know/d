<?php
namespace App\Module\M;

use Min\App;

class BalanceController extends \App\Module\M\WbaseController
{
	public function income_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function items_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}
	
	public function detail_get()
	{
		$result['meta'] = ['title' =>'安逸时光,美丽分享'];
		$this->layout($result, 'layout_m');
	}

}