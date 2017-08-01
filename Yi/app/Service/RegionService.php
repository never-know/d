<?php
namespace App\Service;

use Min\App;

class RegionService extends \Min\Service
{
	/*
	 * 	获取 id 所有子元素, 按 id 升序 
	 *
	 *	parameter: id
	 * result : [id => [[ID, SHORT_NAME, PARENT_ID], ...]]
	 *
	 */
	 
	public function childrenNode($id)
	{
		$id = intval($id);
		$sql = 'SELECT id, short_name as name FROM {{region}} WHERE parent_id  = '. $id. ' ORDER BY id ASC';
		$region	= $this->query($sql);
		return $this->success([$id =>$region]);
	}	
	
	/*
	 * 	region 表全部数据, 按 parent_id, id 升序 
	 *
	 * result : [id => short_name, ...]
	 *
	 */
	public function allNode($level = 4)
	{
		if ($level == 3) {
			$level = ' where id < 100000000 ';
		} else {
			$level = '';
		}
		
		$sql = 'SELECT id, short_name, parent_id FROM {{region}} ' . $level . ' ORDER BY parent_id ASC, id ASC';
		$result	= $this->query($sql, null);
		
		$region = [];
		foreach ($result as $key => $value) {
			$region[$value['id']] = $value;	
		}

		return $this->success($region);
	}
	
	/*
	 * 	获取 id 所有自己, 父级, 父父级等元素的子元素, 按parent_id, id 升序 
	 *
	 *	parameter: id
	 * result : [[ID, SHORT_NAME, PARENT_ID], ...]
	 *
	 */
	 
	public function nodeChain($id)
	{
		$id = intval($id);	
		if ( $id > 1) {
			$sql = 'SELECT d.id as id1, c.id as id2, b.id as id3, a.id  FROM {{region}} a
				LEFT JOIN {{region}} b ON a.parent_id = b.id
				LEFT JOIN {{region}} c ON b.parent_id = c.id
				LEFT JOIN {{region}} d ON c.parent_id = d.id
				WHERE a.id = ' . $id .' LIMIT 1';
				
			$result	= $this->query($sql);
			if (empty($result)) {
				return $this->success([]);
			} else {
				foreach($result as $key => $value) {
					if (empty($value) || $value > 100000000) unset($result[$key]);
				}
			
				$in = implode(',', $result);
				if (is_numeric($in)) {
					$filter = ' parent_id = ' . $in;
				} else {
					$filter = " parent_id in ({$in}) ";
				}
			}
		} else {
			$filter = ' parent_id = 0 ';
		}
		
		$sql2 = 'SELECT id, short_name, parent_id FROM {{region}} WHERE '. $filter .' ORDER BY parent_id ASC, id ASC';
		$result	= $this->query($sql2);
		return $this->success($result);
	}
	
 
	
}