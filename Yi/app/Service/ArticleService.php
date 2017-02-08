<?php
namespace App\Service;

use Min\App;

class ArticleService extends \Min\Service
{

	public function add($param)
	{
		$sql = 'INSERT INTO {article} (tag, start, end, region, title, desc, icon) values ('.
			implode(',', [intval($param['tag']), intval($param['start']), intval($param['end']), intval($param['region']), ':title', ':desc', ':icon)']);

		try {
			$this->DBManager()->transaction_start();
			$id = $this->query($sql, [':title' => $param['title'], ':desc' => $param['desc'],':icon' => $param['icon']]);
			$sql2 = 'INSERT INTO {article_content} (id, content) values ('. intval($id). ', :content )';
			$this->query($sql, [':content' => $param['content']]);
			$this->DBManager()->transaction_commit();
			return true;
		} catch (\Throwable $t) {
			watchdog($t);
			$this->DBManager()->transaction_rollback();
			return false;
		}
		
	}	
	
	 
}