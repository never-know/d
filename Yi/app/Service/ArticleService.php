<?php
namespace App\Service;

use Min\App;

class ArticleService extends \Min\Service
{

	public function add($param)
	{
		if (!empty($param['id'])) {
			return $this->eidt($param);
		}
		
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
	
	public function edit($param)
	{
		$param['id'] = intval($param['id']);
		
		$sql = 'UPDATE {article}  SET tag = '. intval($param['tag']) . ', start = '. intval($param['start']) . ', end = '. intval($param['end']) . ', region = '. intval($param['region']) . ', title = :title, desc = :desc, icon = :icon  WHERE id = '. $param['id'];

		$result = $this->query($sql, [':title' => $param['title'], ':desc' => $param['desc'],':icon' => $param['icon']]);
		
		$sql2 = 'UPDATE {article_content} set content = :content WHERE id = '. $param['id'];
		$result2 = $this->query($sql2, [':content' => $param['content']]);
			 
		return $result && $result2;
		
	}	
	
	 
}