<?php
namespace App\Module\Www;

use Min\App;

class RegionController extends \Min\Controller
{
	public function id_get()
	{
		$id = App::getArgs();
		if (!is_numeric($id)) {
			$this->error(1,'参数错误');
		}
		$key = 'region_'. $id;
		$cache = $this->cache('region');
		$result = $cache->get($key, 0);
		if (empty($result)) {
			$result = $this->request('\\App\\Service\\Region::node', $id);
			$cache->set($key, $result);
		}
		$this->success($result);
	}
 
}