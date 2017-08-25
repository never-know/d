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
				
				foreach ($tag as $key => $t) {
					if (empty($t) || empty($source[$t]))  return $this->error('参数错误', 1);
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
		
		//$param['filter']['region'] = ' region_id = 0';
		
		$param_processed['region'] = [0];
		
		$region = intval($p['region']??0);
		
		if ($region > 1 && $region < 100000000) {
 
			$patten = '/^'. $region .'\d+(,' .  $region . '\d+)*$/';
			
			$region *= 1000;	
  
			if ( 0 != $region%10000000) {				// 非省级的要加上省ID
				$param_processed['region'][] 			=  (intval($region/10000000) * 10000000);	
				if (0 != $region%100000) {				// 非市级的要加上市ID
					$param_processed['region'][]  		=  (intval($region/100000) * 100000);	
					if (0 != $region%1000) {			// 非区级的要加上区ID
						$param_processed['region'][] 	= (intval($region/1000) * 1000);
					}
				}
			}
			
			$param_processed['region'][] = $region;
			
			if (!empty($p['sub_region']) && preg_match($patten, $p['sub_region'])) {
				$param_processed['region']   = array_merge($param_processed['region'], explode(',', $p['sub_region']));
			}
 
			$param['filter']['region'] = ' region_id in ( ' .  implode(',', $param_processed['region']). ')';
		} else {
			$param['filter']['region'] = ' region_id = 0 ';
		}

		if (!empty($p['author'])) {
			$author = intval($p['author']);
			$param['filter']['author'] 		= 'author = ' . $author;
			$param_processed['author'] 		= $author;
		}
 	
		switch (intval($p['order']??1)) {
			
			case 2 :
				$param['order'] = ' ORDER BY start_date DESC ';
				$param_processed['order'] = 2;
				break;
			case 3 :
				$param['order'] = ' ORDER BY end_date DESC ';
				$param_processed['order'] = 3;
				break;	
			case 1 :
			default:
				$param['order'] = ' ORDER BY content_id DESC ';
				$param_processed['order'] = 1;
				break;			
		}
 
		$filter = empty($param['filter']) ? '' : ' WHERE ' . implode(' AND ', $param['filter']);
		
		$sql_count 	= 'SELECT count(1) as count FROM {{article}} ' . $filter . ' LIMIT 1'; 
		$sql_list 	= 'SELECT * FROM {{article}} ' . $filter . $param['order'];
		
		$result = $this->commonList($sql_count, $sql_list);
		
		if ($result['statusCode'] != 1) {
			return $result;
		}
		
		$region_names 	= $this->cache('region')->get('regionChain_' . intval($region/1000), true);
		
		
		$names 			= ['0' => '不限'];
		foreach ($region_names as $key =>$vv) {
			$names += $vv;
		}
		 
		if (!empty($result['body']['list'])) {
			foreach ($result['body']['list'] as &$v) {
				$v['id_name'] 		= \int2str($v['content_id']);
				$v['tag_name'] 		= \article_tags($v['content_tag']);
				if (0 != ($v['region_id']%1000)) {
					$v['region_name'] 	= $names[$v['region_id']]??'';
				} else {
					$v['region_name']	= $names[$v['region_id']/1000]??'';
				}
			}
		}
 
		$param_processed['region_id'] 		= [];
		$param_processed['region_title'] 	= [];
		$param_processed['subregion_id'] 	= [];
		$param_processed['subregion_title'] = [];
		
		if (!empty($param_processed['region'])) {
			foreach ($param_processed['region'] as $value) {
				if (empty($value)) continue;
				$mask = $value%1000;
				if (0 == $mask) {
					$region_id = $value/1000;
					
					if ($names[$region_id] == '北京' || $names[$region_id] == '上海' || $names[$region_id] == '重庆' || $names[$region_id] == '天津' ) {
						$param_processed['region_title'][] = '';
					} else {
						$param_processed['region_title'][] = $names[$region_id]??'';
					}
					
					$param_processed['region_id'][] = $region_id;
				} else {
					$param_processed['subregion_title'][] = $names[$value]??'';
					$param_processed['subregion_id'][] = $value;
				}
			}
			
		} else {
			$param_processed['region_title'] 	= ['不限'];
			$param_processed['region_id']		= [0];
		}
		
		$param_processed['subregion_title'] = $param_processed['subregion_title']?:['不限'];
		$param_processed['subregion_id']	= $param_processed['subregion_id']?:[0];
		
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