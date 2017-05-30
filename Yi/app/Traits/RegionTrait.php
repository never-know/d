<?php
namespace App\Traits;

trait RegionTrait
{
	/*
	 * 获取region_id 同级元素和所有父级元素的同级元素
	 *
	*/
	
	public function region_list($region_id)
	{
		$region_key =  ($region_id > 100000000) ? intval($region_id/1000) : $region_id;
		$key = 'regionChain_'. $region_key;
		$cache = $this->cache('region');
		
		if ($region_id > 0) {
			$region_list = $cache->get($key, true);
		}
		
		if (empty($region_list)) {
		
			$region_list = $cache->get('regionChain_0', true);
			
			if (empty($region_list)) {
				$region_list0 = $this->request('\\App\\Service\\Region::nodeChain', 0);
				foreach ($region_list0['body'] as $v) {
					$region_list[0][$v['id']] = $v['short_name'];
				}
				$cache->set('regionChain_0', $region_list);
			}
			if ($region_id > 0) {	
				$node_chain = $this->request('\\App\\Service\\Region::nodeChain', $region_key);
				if  (!empty($node_chain['body'])) {
					foreach ($node_chain['body'] as  $value) {
						$region_list[$value['parent_id']][$value['id']] = $value['short_name'];
					}
					$cache->set($key, $region_list);
				}
			}
		}
		return $region_list;
	}
}