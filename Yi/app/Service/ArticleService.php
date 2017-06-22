<?php
namespace App\Service;

use Min\App;

class ArticleService extends \Min\Service
{
	public function add($param)
	{
		if (!empty($param['id'])) {
			return $this->edit($param);
		}
		
		$param['region'] = intval($param['region']);
		if ($param['region'] < 100000000) $param['region'] *= 1000;
		
		$set = [
			'tag' 		=> intval($param['tag']),
			'start' 	=> intval($param['start']), 
			'end' 		=> intval($param['end']), 
			'region' 	=> intval($param['region']),
			'title' 	=> ':title', 
			'desc' 		=> ':desc',
			'icon' 		=> ':icon'
		];
		
		$bind = [
			':title' 	=> $param['title'], 
			':desc'		=> $param['desc'],
			':icon' 	=> $param['icon']
		];
		
		$sql = 'INSERT INTO {{article}} ' . query_build_insert($set);

		try {
			$this->DBManager()->transaction_start();
			$this->DBManager()->inTransaction();
			$id = $this->query($sql, $bind);
			$sql2 = 'INSERT INTO {{article_content}} (id, content) values ('. intval($id['id']). ', :content )';
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
		
		if ($param['region'] < 100000000)  $param['region'] = $param['region'] * 1000;
		
		$set = [
			'tag' 		=> intval($param['tag']),
			'start' 	=> intval($param['start']), 
			'end' 		=> intval($param['end']), 
			'region' 	=> intval($param['region']),
			'title' 	=> ':title', 
			'desc' 		=> ':desc',
			'icon' 		=> ':icon'
		];
		
		$bind = [
			':title' 	=> $param['title'], 
			':desc'		=> $param['desc'],
			':icon' 	=> $param['icon']
		];
		
		$sql = 'UPDATE {{article}} SET ' . \build_query_common(', ', $set) .' WHERE id = '. $param['id'];

		$result = $this->query($sql, $bind);
		
		$sql2 = 'UPDATE {{article_content}} SET content = :content  WHERE id = '. $param['id'];
		
		$result2 = $this->query($sql2, [':content' => $param['content']]);
			 
		if ($result && $result2) {
			return $this->success();
		} else {
			return $this->error('更新失败', 1);
		}		
	}

	
	public function list($p)
	{
		$param = [];
		$param_processed = [];
		
		$param_processed['tag'][] = 0;
		
		if (!empty($p['tag'])) {
			if (preg_match('/^([\d]+,)*[\d]+$/', $p['tag'])) {
				
				$source = article_tags();
				$tag 	= explode(',', $p['tag']);
				
				foreach ($tag as $key => $value) {
					if (empty($value) || empty($source[$value]))  return $this->error('参数错误', 1);
				}
				
				if (is_numeric($p['tag'])) {
					$param['filter']['tag'] = 'tag = ' . $p['tag'];
				} else {
					$param['filter']['tag'] = 'tag  in  (' . $p['tag']. ')';	
				}
				
				$param_processed['tag'] = $tag;
				
			} else {
				return $this->error('参数错误', 1);
			} 
		}
		/*
		if (!empty($p['region']) && ($region = intval($p['region'])) && ($region > 1)) {
			
			if ($region < 100000000) $region *= 1000;
			// 省级 
			if ( 0 == $region%10000000) {
				$param['filter'][] = '(region = 0 OR (region >=' . $region .' AND region < ' . ($region + 10000000) . '))';
				//市
			} elseif ( 0 == $region%100000) {	
			
				$param['filter'][] = '(region = 0 OR region = '. intval($region/10000000). ' OR (region  >= ' . $region .' AND region < ' . ($region + 100000) .'))';
				// 倒2 
			} elseif ( 0 == $region%1000) {	
				$param['filter'][] = '(region = 0 OR region = '. intval($region/10000000). ' OR  region = '. intval($region/100000). ' OR  (region  > ' . $region .' AND region < ' . ($region + 1000) .'))';
				// 倒一 
			} else {
				$param['filter'][] = '(region = 0 OR  region  =' . $region .')';
			}	
		}
		*/
		
		$param['filter']['region'] = 'region = 0';
		
		if (!empty($p['region']) && ($region = intval($p['region'])) && ($region > 1)) {
			
			if ($region < 100000000) $region *= 1000;
			// 不限 and self 
			$param['filter']['region'] .= ' OR region = ' . $region;
			// 省级 
			if ( 0 != $region%10000000) {
				// 非省级的要加上省ID
				$param['filter']['region'] .= ' OR region = '. (intval($region/10000000) * 10000000);
				if (0 != $region%100000) {	
					// 非市级的要加上市ID
					$param['filter']['region'] .= ' OR  region = '. (intval($region/100000) * 100000);
					if (0 != $region%1000) {
						$param['filter']['region'] .= ' OR  region = '. (intval($region/1000) * 1000);
					}
				}
			}
			
			$param['filter']['region'] = '(' . $param['filter']['region']. ')';
		}
		
		if (!empty($p['author'])) {
			$param['filter'][] = 'author = ' . intval(session_get('UID'));
		}
		
		$param['order'] = ' ';
		$param_processed['order'] = 1;
		if (!empty($p['order'])) {	
			switch (intval($p['order'])) {
				case 1 :
					$param['order'] = ' ORDER BY id DESC ';
					$param_processed['order'] = 1;
					break;
				case 2 :
					$param['order'] = ' ORDER BY start DESC ';
					$param_processed['order'] = 2;
					break;
				case 3 :
					$param['order'] = ' ORDER BY end DESC ';
					$param_processed['order'] = 3;
					break;					 
			}
		}

		$filter = empty($param['filter']) ? '' : ' WHERE ' . implode(' AND ', $param['filter']);
		
		$sql_count = 'SELECT count(1) as count FROM {{article}} ' . $filter; 
		
		$count = $this->query($sql_count);
		
		if (!isset($count['count'])) {
			return $this->error('加载失败', 20106);
		}  
		
		$page 	= \result_page($count['count']);
		
		
		if ($page['current_page'] > $page['total_page']) {
			$list = [];
		} else {
		
			$sql = 'SELECT * FROM {{article}} ' . $filter . $param['order'] . $page['limit'];
			$list = $this->query($sql);
			
			if (false === $list) {
				return $this->error('加载失败', 20106);
			} 
			
			foreach ($list as &$value) {
				$value['id_name'] 		= \int2str($value['id']);
				$value['tag_name'] 		= \article_tags($value['tag']);
				$value['region_name'] 	= \region_get($value['region']);				
			}
		}  
		
		$result['params'] 	= $param_processed;
		$result['page'] 	= $page;
		$result['list'] 	= $list;
		
		return $this->success($result);
		
	}
	
	public function detail($id)
	{
		$sql = 'SELECT a.*, ac.content FROM {{article}} AS a LEFT JOIN {{article_content}} AS ac on ac.id = a.id  WHERE a.id = '. intval($id) . '  LIMIT 1';
		$result = $this->query($sql);
		if (empty($result)) {	
			return $this->error('数据不存在', 1);	
		} else { 
			return $this->success(['detail' => $result]);	
		}
	}
	 
}