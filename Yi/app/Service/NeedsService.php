<?php
namespace App\Service;

use Min\App;

class NeedsService extends \Min\Service
{
	public function add($param)
	{
		if (!empty($param['id'])) {
			return $this->edit($param);
		}
 
		$set = [
			'status'		=> 0,
			'create_time'	=> time(),
			'title' 		=> ':title', 
			'desc' 			=> ':desc'
		];
		
		$bind = [
			':title' 	=> $param['title'], 
			':desc'		=> $param['desc']
		];
		
		$sql = 'INSERT INTO {{needs}} ' . build_query_insert($set);
		 
		$result = $this->query($sql, $bind);
		
		if ($result['id'] > 0) {
		
			return $this->success();
		} else {
			return $this->error('操作失败', 20109);
		}
			
		 
	}	
	
	private function edit($param)
	{
		$param['id'] = intval($param['id']);
		
		
		$set = [
			 
			'title' 		=> ':title', 
			'desc' 			=> ':desc'
		];
		
		$bind = [
			':title' 	=> $param['title'], 
			':desc'		=> $param['desc']
		];
 
		$sql = 'UPDATE {{needs}} SET ' . \build_query_common(', ', $set) .' WHERE needs_id = '. $param['id'];

		$result = $this->query($sql, $bind);
 
		if ($result['effect']>0 ) {
			return $this->success();
		} else {
			return $this->error('更新失败', 20109);
		}		
	}

	
	public function list()
	{
		$sql_count 	= 'SELECT count(1) as count FROM {{needs}} LIMIT 1'; 
		$sql_list 	= 'SELECT * FROM {{needs}} order by needs_id desc ' ;
		
		$result = $this->commonList($sql_count, $sql_list);
	 
		return $result;
		
	}
	
	public function detail($id)
	{
		$sql = 'SELECT * FROM {{needs}}  WHERE needs_id = '. intval($id) . '  LIMIT 1';
		$result = $this->query($sql);
		if (empty($result)) {	
			return $this->error('数据不存在', 20109);	
		} else { 
			return $this->success($result);	
		}
	}
	 
}