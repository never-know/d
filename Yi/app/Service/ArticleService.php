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
		
		$sql = 'INSERT INTO {article} (`tag`, `start`, `end`, `region`, `title`, `desc`, `icon`) VALUES ('.
			implode(',', [intval($param['tag']), intval($param['start']), intval($param['end']), intval($param['region']), ':title', ':desc', ':icon)']);

		try {
			$this->DBManager()->transaction_start();
			$this->DBManager()->inTransaction();
			$id = $this->query($sql, [':title' => $param['title'], ':desc' => $param['desc'], ':icon' => $param['icon']]);
			$sql2 = 'INSERT INTO {article_content} (id, content) values ('. intval($id). ', :content )';
			$this->query($sql2, [':content' => $param['content']]);
			$this->DBManager()->transaction_commit();
			return $this->success();
		} catch (\Throwable $t) {
			watchdog($t);
			$this->DBManager()->transaction_rollback();
			return $this->error('失败', 1);
		}
	}	
	
	private function edit($param)
	{
		$param['id'] = intval($param['id']);
		
		$sql = 'UPDATE {article}  SET tag = '. intval($param['tag']) . ', start = '. intval($param['start']) . ', end = '. intval($param['end']) . ', region = '. intval($param['region']) . ', title = :title, desc = :desc, icon = :icon  WHERE id = '. $param['id'];

		$result = $this->query($sql, [':title' => $param['title'], ':desc' => $param['desc'],':icon' => $param['icon']]);
		
		$sql2 = 'UPDATE {article_content} set content = :content WHERE id = '. $param['id'];
		$result2 = $this->query($sql2, [':content' => $param['content']]);
			 
		if ($result && $result2) {
			return $this->success();
		} else {
			return $this->error('更新失败', 1);
		}
		
	}

	
	public function list($p)
	{
		//array_walk($p,'trim');
		
		$param = [];
		$param_processed = [];

		if (preg_match('/^([\d]+,)*[\d]+$/', $p['tag'])) {
			
			$source = \App\Conf\Keypairs\article_tags();
			$tag 	= explode(',', $p['tag']);
			
			foreach ($tag as $key => $value) {
				if (!isset($source[$value]))  return $this->error('参数错误', 1);
			}
			if (is_numeric($p['tag'])) {
				$param['filter'][] = 'tag = ' . $p['tag'];
			} else {
				$param['filter'][] = 'tag  in  (' . $p['tag']. ')';	
			}
			$param_processed['tag'] = $tag;
			
		} else {
			return $this->error('参数错误', 1);
		} 
		
		$param_processed['region'] = 0;
		
		if (!empty($p['region']) && $region = intval($p['region']) && $region > 1) {
			
			// 省级 
			if ( 0 == $region%10000000) {
				$param['filter'][] = '(region = 0 OR ( region >=' . $region .' AND region < ' . ($region + 10000000);
				//市
			} else {
				
				
				// 区 
			}
			
			
			$key = 'regionChain_'. $region;
			$cache = $this->cache('region');
			$result = $cache->get($key);
			if (empty($result)) {
				$result = $this->request('\\App\\Service\\Region::nodeChain', $region);
				$cache->set($key, $result);
			}
			if (isset($result['max']) && isset($result['min'])))  $region_sql[] = '(region >= ' . $result['min'] .' and region =< ' . $result['min'] . ')';
			if (isset($result['id'])) 		$region_sql[] = 'region = '. $result['id'];
			if (isset($result['pid'])) 		$region_sql[] = 'region = '. $result['pid'];
			if (isset($result['ppid'])) 	$region_sql[] = 'region = '. $result['ppid'];
			if (isset($result['pppid'])) 	$region_sql[] = 'region = '. $result['pppid'];
			
			$param['filter'][] = '(' . implode(' OR ', $region_sql) . ')' ;
			 
		}
		
		if (!empty($p['author'])) {
			$param['filter'][] = 'author = ' . intval(session_get('UID'));
		}
		
		$param['order'] = ' ';
		if (!empty($p['order'])) {	
			switch (intval($p['order'])) {
				case 1 :
					$param['order'] = ' ORDER BY id DESC ';
					break;
				case 2 :
					$param['order'] = ' ORDER BY ctime DESC ';
					break;
				case 3 :
					$param['order'] = ' ORDER BY end DESC ';
					break;					 
			}
		}
		
		$page		= max(intval($p['page'] ?? 1), 1) - 1;
		$page_size  = max(intval($p['page_size'] ?? 10), 0) ?: 10;
		$param['limit'] = ' LIMIT ' . $page * $page_size . ' ' .$page_size;
		
		$db = $this->DBManager();
		
		$sql_number = 'SELECT count(1) as number FROM {article} WHERE ' . implode(' AND ', $param['filter']); 
		$number = $db->query($sql_number);
		
		if (intval($number[0]['number']) > 0) { 
			$sql = 'SELECT * as number FROM {article} WHERE ' . implode(' AND ', $param['filter']) . $param['order'] . $param['limit'];
			$result['list'] = $db->query($sql);
		} else {
			$result['list'] = [];
		}
		
		$result['params'] = $param_processed;
		$result['page'] = \result_page($number[0]['number'], $page_size, $page);
		
		$this->success($result);
		
	}
	
	public function detail($id)
	{
		$sql = 'SELECT a.*, ac.content FROM {article} AS a LEFT JOIN {article_content} AS ac on ac.id = a.id  WHERE a.id = '. intval($id) . '  LIMIT 1';
		$result = $this->query($sql);
		if (empty($result)) {	
			return $this->error('数据不存在', 1);	
		} else { 
			return $this->success($result);	
		}
	}
	 
}