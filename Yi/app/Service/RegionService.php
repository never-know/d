<?php
namespace App\Service;

use Min\App;

class RegionService extends \Min\Service
{

	public function node($id)
	{
		$id = intval($id);
		if($id == 0) {
			$region = $this->rootNode();
			return $this->success($region);
		} else {
			$region = $this->childrenNode($id);
			return $this->success([$id =>$region]);
		}
		
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
	public function rootNode()
	{
		$id = intval($id);
		$sql = 'SELECT id, name  FROM {region} WHERE parent_id  = 0 order by id asc';
		$result	= $this->query($sql);
		 
		return $result;
	}	
	 
	 
}