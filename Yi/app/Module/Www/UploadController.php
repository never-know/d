<?php
namespace App\Module\Www;

use Min\App;

class UploadController extends \App\Module\Www\BaseController
{
	public function index_post()
	{
		$upload = new \Min\Upload('imgFile');
		if ($upload->save()) {
			$result = ['error' => 0, 'url' => $upload->getInfo('url')];
			if ($_POST['size'] == 1) {
				$result['width'] = '400px';
				$result['height'] ='auto';
			}
			$this->response($result);
		} else {
			$this->response(['error' => 1, 'message' => $upload->getError()]);
		}
	} 
}