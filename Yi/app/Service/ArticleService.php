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
			'content_tag' 		=> intval($param['tag']),
			'start_date' 		=> intval($param['start']), 
			'end_date' 			=> intval($param['end']), 
			'region_id' 		=> intval($param['region']),
			'content_title' 	=> ':title', 
			'content_description' 	=> ':desc',
			'content_icon' 		=> ':icon'
		];
		
		$bind = [
			':title' 	=> $param['title'], 
			':desc'		=> $param['desc'],
			':icon' 	=> $param['icon']
		];
		
		$sql = 'INSERT INTO {{article}} ' . query_build_insert($set);
		
		$db = $this->DBManager();
		
		try {
			$db->begin();
			//$db->inTransaction();
			$id = $db->query($sql, $bind);
			 
			$sql2 = 'INSERT INTO {{article_content}} (content_id, content) values ('. $id['id']. ', :content )';
			
			$content = $db->query($sql2, [':content' => $param['content']]);
			 
			$db->commit();
			
			return $this->success();
			
		} catch (\Throwable $t) {
		
			watchdog($t);
			
			$db->rollBack();
			
			return $this->error('失败', 1000);
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
			 
		if (!empty($result) && !empty($result2)) {
			return $this->success();
		} else {
			return $this->error('更新失败', 1000);
		}		
	}

	
	public function list($p)
	{
		$param = [];
		$param_processed = [];
		
		$param_processed['tag'] = [0];
		
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
		
		$param['filter']['region'] = ' region_id = 0';
		$region = intval($p['region']??0);
		
		if ($region > 1) {
			
			if ($region < 100000000) $region *= 1000;
			// 不限 and self 
			$param['filter']['region'] .= ' OR region_id = ' . $region;
			// 省级 
			if ( 0 != $region%10000000) {
				// 非省级的要加上省ID
				$param['filter']['region'] .= ' OR region_id = '. (intval($region/10000000) * 10000000);
				if (0 != $region%100000) {	
					// 非市级的要加上市ID
					$param['filter']['region'] .= ' OR  region_id = '. (intval($region/100000) * 100000);
					if (0 != $region%1000) {
						$param['filter']['region'] .= ' OR  region_id = '. (intval($region/1000) * 1000);
					}
				}
			}
			
			if (!empty($p['sub_region']) && preg_match('/^'. strval($region/1000) .'\d+(,' . strval($region/1000). '\d)*$/', $p['sub_region'])) {
				$param['filter']['region'] .= ' OR region_id = ' . implode(' OR region_id = ', explode(',', $p['sub_region']));
			}
			
			$param['filter']['region'] = '(' . $param['filter']['region']. ')';
		}
		
		if (!empty($p['author'])) {
			$param['filter'][] = 'author = ' . intval($p['author']);
		}
		
		$param['order'] = ' ';
		
		$param_processed['order'] = 1;
		
		if (!empty($p['order'])) {	
			switch (intval($p['order'])) {
				case 1 :
					$param['order'] = ' ORDER BY content_id DESC ';
					$param_processed['order'] = 1;
					break;
				case 2 :
					$param['order'] = ' ORDER BY start_date DESC ';
					$param_processed['order'] = 2;
					break;
				case 3 :
					$param['order'] = ' ORDER BY end_date DESC ';
					$param_processed['order'] = 3;
					break;					 
			}
		}

		$filter = empty($param['filter']) ? '' : ' WHERE ' . implode(' AND ', $param['filter']);
		
		$sql_count 	= 'SELECT count(1) as count FROM {{article}} ' . $filter . ' LIMIT 1'; 
		$sql_list 	= 'SELECT * FROM {{article}} ' . $filter . $param['order'];
		
		$result = $this->commonList($sql_count, $sql_list);
		
		if ($result['statusCode'] != 1) {
			return $result;
		}
		
		$region_namses 	= $this->cache('region')->get('regionChain_' . strval($region/1000), true);
		$names 			= [];
		foreach ($region_names as $key =>$value) {
			$names += $value;
		}
 
		
		if (!empty($result['body']['list'])) {
			foreach ($result['body']['list'] as &$value) {
				$value['id_name'] 		= \int2str($value['content_id']);
				$value['tag_name'] 		= \article_tags($value['content_tag']);
				if (0 != ($value['region_id']%1000)) {
					$value['region_name'] 	= $names[$value['region_id']]??'';
				} else {
					$value['region_name']	= $names[$value['region_id']/1000]??'';
				}
			}
		}
 
		$result['body']['params'] 	= $param_processed;
 
		return $result;
		
	}
	
	public function detail($id)
	{
		$sql = 'SELECT a.*, ac.content FROM {{article}} AS a LEFT JOIN {{article_content}} AS ac on ac.content_id = a.content_id  WHERE a.content_id = '. intval($id) . '  LIMIT 1';
		$result = $this->query($sql);
		if (empty($result)) {	
			return $this->error('数据不存在', 1000);	
		} else { 
			return $this->success( $result);	
		}
	}
	 
}