<?php
namespace App\Module\Www;

use App\Traits\RegionTrait;
use Min\App;

class RegionController extends \Min\Controller
{
	use RegionTrait;
	/*
	 * 	获取 id 所有子元素, 按 id 升序 
	 *
	 *	parameter: id
	 *　result : [id => [[ID, SHORT_NAME, PARENT_ID], ...]]
	 *
	 */
	 
	public function id_get()
	{
		$id = intval(App::getArgs());
		if ($id < 1) {
			$this->error('参数错误', 1);
		} elseif ($id > 100000000) {
			$result = [];
		} else { 
			$region_list = $this->region_list($id);
			$result = $region_list[$id]??[];
		}		
		$this->success([$id => $result]);
	}
	
	/*
	 * 	重置 region 相关缓存　regionChain_{id} 
	 */
	 
	public function cache_get()
	{ 
		$cache 	= $this->cache('region');
		$nodes = $this->request('\\App\\Service\\Region::allNode');
		$region = [];
		foreach ($nodes['body'] as $key => $value) {
			$region[$value['parent_id']][$key] = $value['short_name'];
			$regionAll[$key] = $value['short_name'];
		}

		$cache->set('regionChain_0', [ 0 => $region[0]]);
		$cache->set('regionAll', $regionAll);
		 
		foreach ($nodes['body'] as $key => $value) {
			if ($key < 100000000) {
				$data = [ 0 => $region[0]];
				$id = $key;
				do {
					if (empty($region[$id])) $result[] = $id . '=>' . $nodes['body'][$id]['short_name'];
					$data[$id] = $region[$id]??[];
					$id 	   = $nodes['body'][$id]['parent_id'];	
				} while ($id > 0);
				
				ksort($data);
				$cacheKey = 'regionChain_' . $key;
				$cache->set($cacheKey, $data);
			}	
		}
		$this->success($result);
	}
 
}