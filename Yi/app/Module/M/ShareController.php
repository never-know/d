<?php
namespace App\Module\M;

use Min\App;

class ShareController extends \Min\Controller
{
	public function views_get()
	{
		$params = [];
		$params['share_id'] = App::getArgs();
		$params['share_user'] = session_get('USER_ID');
		
		$this->request('\\App\\Service\\Share::shareViews', $params);
	
	}
}