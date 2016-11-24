<?php
namespace Min;

class CacheManager
{
	private $conf =	[];
	private $pools = [];
 
	public function  __construct()
	{
		$this->conf = parse_ini_file(CONF_PATH.'/cache.ini');
	}
	 
	public function init($type = 'default')
	{	 
		if (empty($this->conf[$type])) {
			$type = 'default';
		}		
		$info	= $this->conf[$type];		
		if (empty($this->pools[$type])) { 
			$this->pools[$type] = new {$info['bin']}; 
		}
		return $this->pools[$type]->init($info);
	}

}