<?php
namespace App\Module\M;

use Min\App;

class SubscribeController extends \Min\Controller
{
	public function index_get()
	{
		$result['no_back'] = 1;
		$this->success($result);
	
	}
}