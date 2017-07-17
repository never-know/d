<?php
namespace Min;

class Service
{	
	protected $db_key 		= 'default';
	protected $cache_key 	= 'default';
	protected $log_type 	= 'debug';
	protected $log_level 	= 'DEBUG';
	
	final public function success($body = [], $message = '操作成功')
	{	
		$result = ['statusCode' => 1, 'message' => '操作成功'];
		
		if (!empty($body)) {
			if (is_string($body)) {
				$result['message'] = $body;
			} elseif (is_array($body)) {
				$result['body'] = $body;
			}
		}
		return $result;
	}

	final public function error($message, $code)
	{	
		if ($code < 2) {
			throw new \Exception('code should greater than 1', 12000);
		}
		
		
		return ['statusCode' => $code, 'message' => $message];
	}
	
	final public function queryi($sql, $marker = '', $param = [])
	{	
		record_time('query start');
		$result =  $this->DBManager()->query($sql, $marker, $param);
		record_time('query end');
		return $result;
	}
	
	final public function query($sql, $param = false, $throwable = false)
	{	
		record_time('query start');
		try {	
			$result = $this->DBManager()->query($sql, $param);
		} catch (\Throwable $t) {		
			if ($param === true || $throwable === true) {
				throw $t;
			} else {
				watchdog($t, 'query_error', 'ERROR');
				$result =  false;			
			}
		}
		watchdog($result);
		record_time('query end');
		return $result;
	}
	
	final public function changeDB($key)
	{	
		if (!empty($key)) {
			$this->db_key = $key;
		}
		return $this;
	}
	
	final public function changeCache($key)
	{	
		if (!empty($key)) {
			$this->cache_key = $key;
		}
		return $this;
	}

	final public function DBManager()
	{		
		$db_setting = config_get('backend');
		$value = $db_setting[$this->db_key]?:$db_setting['default'];
		return \Min\App::getService($value['bin'], $value['key']);
	}

	final public function cache($key = null)
	{
		if (!empty($key)) {
			$this->cache_key = $key;
		}
		
		$cache_setting = config_get('cache');
		$value = $cache_setting[$this->cache_key]??$cache_setting['default'];
		return \Min\App::getService($value['bin'], $value['key']);
	}
	
	final public function getCacheKey($type, $value)
	{
		return '{'. $this->cache_key.'}:'.$type. ':'. strtr($value, ['"'=>'']);
	}
	
	final public function watchdog($data, $extra = null)
	{	
		watchdog($data, $this->log_type, $this->log_level, $extra);
	}
	
	final public function commonList($sql_count, $sql_list) 
	{
		if (is_numeric($sql_count)) {
			$count['count'] = $sql_count;
		} else {
			$count = $this->query($sql_count);
			if (!isset($count['count'])) {
				return $this->error('加载失败', 20106);
			}  
		}
		
		$page 	= \result_page($count['count']);
		
		if ($page['current_page'] > $page['total_page']) {
		
			$list = [];	
			
		} else {
		
			$list = $this->query($sql_list . $page['limit']);
			
			if (false === $list) {
				return $this->error('加载失败', 20106);
			} 	
		}
		
		return $this->success(['page' => $page, 'list' => $list]);
	}
}