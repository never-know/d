<?php
namespace App\Module\M;

use Min\App;
/* 框架功能测试  */
class FunctestController extends \App\Module\M\TestBaseController
{

	// insert  and insert ignore_user_abort
	
	public function onConstruct($redirect = 1)
	{
		if (empty($_REQUEST['sign1']) || $_REQUEST['sign1'] != 'ybcoming') {
			exit;
		}
		
		parent::onConstruct($redirect);
	}
	
	
	/* 测试 insert 和  INSERT  IGNORE 
		array(2) {
			["effect"]=>
			int(1)
			["id"]=>
			string(3) "206"
		}
		array(2) {
			["effect"]=>
			int(0)
			["id"]=>
			string(1) "0"
		}
	*/
 
	public function insert_get()
	{
		$this->request('\\App\\Service\\Test::insert');
		exit; 
	
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
	
	public function phpinfo_get()
	{
		phpinfo();
		//$user = $this->request('\\App\\Service\\Admin::bulid_table');
		exit;

	}
	
	public function password_get()
	{
		echo password_hash('123456', PASSWORD_BCRYPT, ['cost' => 9]);
		exit;
	
	}
	
	 

}