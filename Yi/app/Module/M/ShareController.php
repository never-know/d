<?php
namespace App\Module\M;

use Min\App;

class ShareController extends \App\Module\M\WbaseController
{
	public function logs_get()
	{
	
		$result = $this->request('\\App\\Service\\Share::logs', session_get('UID'));
		$this->response($result);
	
	}
}