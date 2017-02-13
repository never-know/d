<?php
namespace App\Service;

use Min\App;

class RegionService extends \Min\Service
{

	public function node($id)
	{
		$id = intval($id);
		$region = $this->allChildrenNode($id);
		return $this->success([$id =>$region]);

	}	
	
	private function childrenNode($id)
	{
		$sql = 'SELECT id, name, parent_id FROM {region} WHERE parent_id > '. ($id - 1) .' AND parent_id <' .($id + 10000) .' ORDER BY parent_id asc , sort asc';
		$result	= $this->query($sql);
		
		$region = [];
		foreach ($result as $key => &$value) {
			$parent_id = $value['parent_id'] ;
			unset($value['parent_id']);
			$region[$parent_id][] = $value;	
		}
		return $region;
	
	}
	private function allChildrenNode($id)
	{
		$id = intval($id);
		$sql = 'SELECT id, name  FROM {region} WHERE parent_id  = '. $id. ' order by id asc';
		$result	= $this->query($sql);
		return $result;
	}	
	 
	private function nodeChain($id)
	{
		$id = intval($id);
		
		if ($id > 100000000) {
			// 四级目录
			$sql = 'SELECT e.id ,a.id AS pid, b.id AS ppid,  c.id AS pppid FROM yi_region e 
				LEFT JOIN yi_region a ON a.id = e.parent_id
				LEFT JOIN yi_region b ON b.id = a.parent_id  
				LEFT JOIN yi_region c ON c.id = b.parent_id   
				WHERE  e.id = '. $id ;	
		} else {
			// 三级目录
			$sql = 'SELECT max(e.id) AS max, min(e.id) AS min, '. $id . ' AS id, a.id AS pid,  b.id AS ppid, c.id AS pppid  FROM yi_region e 
				LEFT JOIN yi_region a ON a.id = e.parent_id
				LEFT JOIN yi_region b ON b.id = a.parent_id  
				LEFT JOIN yi_region c ON c.id = b.parent_id   
				WHERE  ((max >100000000 or min > 100000000) and  e.level = 4 ) AND e.parent_id = '. $id ;
		}
		二级 
		//
		$sql = 'select a.id,a.level,a.name, max(b.id) , min(b.id), max(c.id) , min(c.id) from yi_region a 
				  left join yi_region b on b.parent_id = a.id 
				  left join yi_region c on c.parent_id = b.id
				  where a.level = 2
				  group by a.id  '
		
		$sql .= ' LIMIT 1'
		$result	= $this->query($sql);
		return $result;
	}	
	
}