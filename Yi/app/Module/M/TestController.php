<?php
namespace App\Module\M;

use Min\App;

class TestController extends \Min\Controller
{
	public function index_get()
	{
		$this->success([], null);
		
	}
	public function popup_get()
	{
		$this->success([], null);
		
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
	
	public function ondumpkeytest_get()
	{
			$user = $this->request('\\App\\Service\\Wuser::test');
			exit;
	
	}
	
	public function bulidt_get()
	{
	
		$user = $this->request('\\App\\Service\\Admin::bulid_table');
		exit;
		
	
	
	}
	 

}