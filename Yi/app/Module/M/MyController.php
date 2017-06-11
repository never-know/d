<?php
namespace App\Module\M;

use Min\App;

class MyController extends \App\Module\M\WbaseController
{
	public function team_get()
	{
		$result = $this->request('\\App\\Service\\Team::member', session_get('UID'));
		$this->response($result);
	}
}