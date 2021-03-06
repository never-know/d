<?php
namespace Min\Cache;

/**
 * file as cache 
 *
 * @author  yb
 */

class FileCache
{
    private $option = [];
	private $active	= 'default';
	
	public function  __construct($db_key = '') 
	{
		$this->option = config_get('file_cache');	
	}
	
	public function init($key)
	{
		if (!empty($key) && !empty($this->option[$key])) {
			$this->active = $key;
		}
		return $this;
	}
	
    /**
     * Fetches an entry from the cache.
     * 
     * @param string $id
     * @param int $expiration 0 means on limit  
     */
    public function get($id, $decode = true)
    {
        $file_name = $this->getFileName($id);
		if (is_file($file_name)) {
			//if (empty($expiration) || ($expiration > 0 && filemtime($file_name) > (time() - $expiration))) {
				if ($cache = file_get_contents($file_name)) {
					if ($cache = json_decode($cache, true)) {
						if (isset($cache['data']) && (0 == $cache['expiration'] || $cache['expiration'] > time())) {
							return $cache['data'];
						}
					}
				//}
			}
			unlink($file_name);	 
		} 
		return false;	
		 
    }
	 

    /**
     * Puts data into the cache.
     *
     * @param string $id
     * @param mixed  $data
     * @param int    $lifetime
     *
     * @return bool
     */
    public function set($id, $data, $expiration = 0)
    {
        $file_name = $this->getFileName($id);
 
        if (!make_dir($file_name)) {
            return false;
        }
		
		if ($expiration > 0) $expiration += $_SERVER['REQUEST_TIME'];
		$dir = dirname($file_name);;
		
		$tmp = tempnam($dir, 'swap');
		if (file_put_contents($tmp, safe_json_encode(['data'=>$data, 'expiration' => $expiration]))) {
			if (rename($tmp,$file_name)) {
				if (is_file($tmp)) unlink($tmp);
				return true;
			}
			if (is_file($file_name)) unlink($file_name);
		}
		unlink($tmp);
		return false;	 
    }

    protected function getFileName($id)
    {
		if (!empty($this->option[$this->active]['level'])) {
			$id = hash_path($id);
		} else {
			$id = DIRECTORY_SEPARATOR . $id;
		}
		
        return $this->option[$this->active]['cache_dir'] . $id; 	//implode(DIRECTORY_SEPARATOR, $dirs);
    }
	
	/**
	 * 数据自增
	 * @param string $key KEY名称
	 */
	public function incr($key) 
	{
		return $this->getAndModify($key, function($a){return $a++;}, 1);
	}

	/**
	 * 数据自减
	 * @param string $key KEY名称
	 */
	public function decrement($key) 
	{
		return $this->getAndModify($key, function($a){return $a--;});
	}
	// 不支持并发写，并发请用redis
	
	public function getAndModify($id, Closure $callback, $default_value = 0) 
	{
		$result = $this->get($id);

		if ($result) {
			$result['data'] = $callback($result['data']);
		} else {
			$result['data'] = $default_value;
		}
		
		if ($this->set($id, $result)) {
			return $result['data'];
		} else {
			return false;
		}
	}
	
	public function delete($ids)
	{
		if (is_string($ids)) {
			$ids = [$ids];
		}
		
		foreach ($ids as $key => $value) {
			 $file_name = $this->getFileName($value);
			 if (is_file($file_name)) unlink($file_name);
		}
		
		return true;	
	}
	
	public function getDisc()
	{
		return 'FileCache';
	}
}
