<?php
namespace App\Module\Www;

use Min\App;

class UploadController extends \Min\Controller
{
	public function index_post()
	{
		watchdog(123);
		$upload = new \Min\Upload('imgFile');
		$rule = ['base_path' => \PUBLIC_PATH, 'host' => '//'. \PUBLIC_URL];

		if ($upload->save($rule)) {
			echo json_encode(array('error' => 0, 'url' => $upload->getInfo('url')));
		} else {
			echo json_encode(array('error' => 1, 'message' => $upload->getError()));
		}
	}
	
	 
}