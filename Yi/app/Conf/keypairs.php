<?php

function article_tags($key = null)
{
	static $arr = [0=> '全部', 1 => '品牌文化传播', 2 => '吃喝玩乐', 3 => '生活服务', 127 => '其他'];
	return $key ? $arr[$key] : $arr;
}
 
function article_order($key = null)
{
	static $arr = [1 => '默认排序', 2 => '开始时间降序', 3 => '结束时间降序'];
	return $key ? $arr[$key] : $arr;
}


function region_get($region_id)
{
	static $region;
	static $run = 0;
	if (empty($region)) {
		$cache_setting = config_get('cache');
		$value = $cache_setting['region'] ?? $cache_setting['default'];
		$region = \Min\App::getService($value['bin'], $value['key'])->get('regionAll');;
	}
	if (empty($region)) {
		if (0 == $run) {
			register_shutdown_function(function(){
				file_get_contents(HOME_PAGE.'/region/cache.html');
			});
			$run = 1;
		}
		return '加载中...';
	}
	return $region[$region_id] ?? '全国';
}
  