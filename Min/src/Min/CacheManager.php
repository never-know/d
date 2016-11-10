<?php
namespace Min;

class CacheManager
{
	private $conf =	[];
	private $pools = [];
	private $active	= 'default';

	public function  __construct($key = '')
	{
		if (!empty($key)) $this->setActive($key);
	}
	 
	public function setActive($key)
	{	
		$this->active = $key;	
	}
	public function connect()
	{
		if (empty($this->conf)) {
			$this->conf = parse_ini_file(APP_PATH.'/conf/cache.ini');
			if (empty($this->conf[$this->active])) {
				throw new \Exception('can not get active '.$this->active.' info in CacheManager, default selected');
				$this->active = 'default';
			}
		}
		if (empty($this->pools[$this->active])) {
			$info	= $this->conf[$this->active];
			$bin    = $info['namespace'] ?? '\\Min\\Inc\\';
			$bin   .= $info['bin'];
			$this->pools[$this->active] = new {$bin}($this->active); 
		}
		return $this->pools[$this->active];
	}
	 
	
	/**
	 * 设置值
	 * @param string $key KEY名称
	 * @param string|array $value 获取得到的数据
	 * @param int $timeOut 时间
	 */
	public function set($key, $value, $timeOut = 0) 
	{
		$retRes = $this->connect()->set($key, $value);
		if ($timeOut > 0) $this->connect()->setTimeout($key, $timeOut);
		return $retRes;
	}

	/**
	 * 通过KEY获取数据
	 * @param string $key KEY名称
	 */
	public function get($key) 
	{
		$result = $this->connect()->get($key);
		return json_decode($result, true);
	}
	
	/**
	 * 删除一条数据
	 * @param string $key KEY名称
	 */
	public function delete($key) 
	{
		return $this->connect()->delete($key);
	}
	
	/**
	 * 清空数据
	 */
	public function flushAll() 
	{
		return $this->connect()->flushAll();
	}
	
	/**
	 * 数据入队列
	 * @param string $key KEY名称
	 * @param string|array $value 获取得到的数据
	 * @param bool $right 是否从右边开始入
	 */
	public function push($key, $value ,$right = true)
	{
		//$value = json_encode($value);
		return $right ? $this->connect()->rPush($key, $value) : $this->connect()->lPush($key, $value);
	}
	
	/**
	 * 数据出队列
	 * @param string $key KEY名称
	 * @param bool $left 是否从左边开始出数据
	 */
	public function pop($key, $left = true) 
	{
		$val = $left ? $this->connect()->lPop($key) : $this->connect()->rPop($key);
		//return json_decode($val);
		return $val;
	}
	
	/**
	 * 数据自增
	 * @param string $key KEY名称
	 */
	public function increment($key) 
	{
		return $this->connect()->incr($key);
	}

	/**
	 * 数据自减
	 * @param string $key KEY名称
	 */
	public function decrement($key) 
	{
		return $this->connect()->decr($key);
	}
	
	/**
	 * key是否存在，存在返回ture
	 * @param string $key KEY名称
	 */
	public function exists($key) 
	{
		return $this->connect()->exists($key);
	}
	 
}