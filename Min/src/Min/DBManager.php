<?php
namespace Min;

// DB('user')->query('select * from **** ');


class DBManager
{
	private $conf =	[];
	private $pools = [];

	public function  __construct()
	{
		$this->conf = parse_ini_file(CONF_PATH.'/cache.ini');
	}
	 
	public function init($key)
	{	
		
		if (empty($this->conf[$key])) {
			$key = 'default';			
		}
		
		$info = $this->conf[$key];
		
		if (empty($this->pools[$key])) {
			$info	= $this->conf[$this->active];
			$bin    = $info['namespace'] ?: '\\Min\\Database\\';
			$bin   .= $info['bin'];
			$this->pools[$this->active] = new {$bin}($this->active); 
		}
		return $this->pools[$this->active];
	}
	 
	 
}