<?php
namespace Min;

class Service
{	
	protected $db_key = 'default';
	protected $cache_key = 'default';
	protected $log_type = 'debug';
	protected $log_level = 'DEBUG';
	
	final public function success($body = [], $message = '操作成功')
	{	
		$result = ['statusCode' => 0, 'message' => '操作成功'];
		
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
		return ['statusCode' => $code, 'message' => $message];
	}
	
	final public function queryi($sql, $marker = '', $param = [])
	{	
		record_time('query start');
		$result =  $this->DBManager()->query($sql, $marker, $param);
		record_time('query end');
		return $result;
	}
	
	final public function query($sql, $param = [], $throwable = false)
	{	
		record_time('query start');
		try {	
			$result = $this->DBManager()->query($sql, $param);
		} catch (\Throwable $t) {		
			if ($param === true || $throwable === true) {
				throw $t;
			} else {
				watchdog($t);
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
	
	final public function watchdog($data, $extra = null)
	{	
		watchdog($data, $this->log_type, $this->log_level, $extra);
	}
}