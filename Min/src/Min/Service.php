<?php
namespace Min;

class Service
{	
	protected $db_key;
	protected $cache_key;
	
	final public function success($body = [], $message = '操作成功')
	{	
		if (!empty($body)) $result['body'] = $body;
		$result['code'] = 0;
		$result['message'] = $message;
		return $result;
	}

	final public function error($message, $code)
	{	
		return ['code' => $code, 'message' => $message];
	}
	
	final public function query($sql, $action, $marker = '', $param = [])
	{	
		return $this->databaseManager()->query($sql, $action, $marker, $param);
	}
	
	final public function selectDB($key)
	{	
		if (!empty($key)) {
			$this->db_key = $key;
		}
		return $this;
	}
	
	final public function selectCache($key)
	{	
		if (!empty($key)) {
			$this->cache_key = $key;
		}
		return $this;
	}
	
	
	final public function databaseManager()
	{		
		$db_setting = get_config('backend');
		$value = $db_setting[$this->db_key]?:$db_setting['default'];
		return \Min\App::getService($value['bin'])->init($value['key']);
	}

	final public function cache($key = null, $key = null)
	{
		if (!empty($key)) {
			$this->cache_key = $key;
		}
		
		$cache_setting = get_config('cache');
		$value = $cache_setting[$this->cache_key]??$cache_setting['default'];
		return App::getService($value['bin'])->init($value['key']);
	}

}