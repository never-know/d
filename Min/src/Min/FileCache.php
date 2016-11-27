<?php
namespace Min;

/**
 * file as cache 
 *
 * @author  yb
 */

class FileCache
{
    private $option = [];
	
	public function  __construct($db_key = '') 
	{
		$this->option = get_config('filecache');	
	}
	 
	
	public function init($option = null)
    { 
		if (is_array($option)) {
			arrray_merge($this->option, $option);
		}
		return $this;
    }

    /**
     * Fetches an entry from the cache.
     * 
     * @param string $id
     * @param string $type  
     */
    public function get($id,$expiration = 0)
    {
        $file_name = $this->getFileName($id);
		if (is_file($file_name)) {
			if ($expiration > 0 && filemtime($file_name) < (time() - $expiration)) {
				unlink($file_name);	 
			} elseif ($cache = file_get_contents($file_name)) {
				if ($cache = json_decode($cache, true)) {
					if (isset($cache['data'])) {
						return $cache['data'];
					}
				}
			}
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
    public function set($id, $data)
    {
        $file_name = $this->getFileName($id);
		$dir = dirname($file_name);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
		
		$tmp = tempnam($dir, 'swap');;
		if (file_put_contents($tmp, json_encode(['data'=>$data]))) {
			if (rename($tmp,$file_name)) {
				return true;
			}
			unlink($tmp);
		}
		return false;	 
    }

    protected function getFileName($id)
    {
        $hash = md5($id);
        $dirs = [
            $this->option['cache_dir'],
            substr($hash, 0, 2),
            substr($hash, 2, 2),
            substr($hash, 4, 2),
			$hash
        ];
        return implode(DIRECTORY_SEPARATOR, $dirs);
    }
 
}
