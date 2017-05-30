<?php
namespace App\Module\M;

use Min\App;

class TestController extends \Min\Controller
{
	public function index_get()
	{
		$this->layout($result, null);
		
	}
	public function popup_get()
	{
		$this->layout($result, null);
		
	}
	public function redis_get()
	{
		 $cache  =  $this->cache();
		 $result = $cache->set('name1', 'yangbiao2', 300);
		 var_dump($result);
		 exit;
		
	}
	
	public function getredis_get()
	{
		 $cache  =  $this->cache();
		 $result = $cache->get('name');
		 var_dump($result);
		 exit;
		
	}
	
	 

}