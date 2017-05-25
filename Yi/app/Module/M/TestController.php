<?php
namespace App\Module\M;

use Min\App;

class TestController extends \Min\Controller
{
	public function test_get()
	{
		$this->request('\\App\\Service\\Test::index');
		
	}
	
	 

}