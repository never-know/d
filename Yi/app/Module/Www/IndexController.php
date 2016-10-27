<?php
namespace App\Module\Www;

use Min\App;

class IndexController
{
	public function __construct($args)
	{
		if ($args=='index') {
			$this->index();
		} elseif ($args=='test') {
			$this->test();
		}
	}

	private function index()
	{
		layout();

	}
	private function test()
	{
		layout();

	}



}