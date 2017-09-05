<?php
namespace App\Service;

use Min\App;

class TestService extends \Min\Service
{
	 
  
	public function index() 
	{
		$sql = 'INSERT IGNORE INTO {test} (u,a) values(3,1)';
		$sql2 = 'INSERT INTO {test} (u,a) values(3,1)  ON DUPLICATE KEY UPDATE id =LAST_INSERT_ID(id), a =
		(CASE a 
			WHEN 1 THEN 2 
			ELSE 3 
		end)';
		$result = $this->query($sql2);
	 
		var_dump($result);
		exit;

	}
	
	
	public function insert()
	{
		$params = [];
		$params['a'] =  10; 
		$params['u'] =  10; 
	
		$sql = 'INSERT INTO {{test}} ' . build_query_insert($params);
		
		$result = $this->query($sql);
		
		var_dump($result);
		
		$sql = 'INSERT IGNORE INTO {{test}} ' . build_query_insert($params);
		
		$result = $this->query($sql);
		
		var_dump($result);
		exit;
	
	}
	
 
}