<?php
namespace Min\Cache;

class Redis{
	
	private $active	= 'default';
	private $conf 	= [];
	private $pools 	= [];

	public function  __construct($key = '')
	{
		$this->conf = get_config('Redis');;
	}
	 
	public function init($key)
	{
		if (!empty($key) && empty($this->conf[$key])) {
			$this->active = $key;
		}
		return $this;
	}
	public function connect()
	{		
		if (empty($this->conf[$this->active])) {
			throw new \Min\MinException('redis info not found when type ='.$this->active,10000);
		}			

		$info	= $this->conf[$this->active];
		$linkId = $info['host'].$info['port'];
		
		if (empty($this->pools[$linkId])) {		
			$redis = new \Redis(); 
			$reids->connect($info['host'], $info['port'],$info['timeout'],null,$info['delay']);		
			$reids->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
			$this->pools[$linkId] = $redis;	
		}
	
		if (isset($info['auth'])) $this->pools[$linkId]->auth($info['auth']);
		if (isset($info['db']))   $this->pools[$linkId]->select($info['db']); 
		return $this->pools[$linkId];
	}
	 
	
	/**
	 * 设置值
	 * @param string $key KEY名称
	 * @param string|array $value 获取得到的数据
	 * @param int $timeOut 时间
	 */
	public function set($key, $value, $timeOut = 0)
	{
		if ($timeOut > 0) {
			$retRes = $this->connect()->set($key, $value, $timeOut);
		} else {
			$retRes = $this->connect()->set($key, $value);
		}
		return $retRes;
	}

	/**
	 * 通过KEY获取数据
	 * @param string $key KEY名称
	 */
	public function get($key) 
	{
		$result = $this->connect()->get($key);
		return $result;
	}
	
	public function setTimeout($key, $ttl)
	{
		$result = $this->connect()->setTimeout($key, $ttl);
		return $result;
	}
	
	/**
	 * 删除一条数据
	 * @param string $key KEY名称
	 */
	public function delete($key) {
		return $this->connect()->delete($key);
	}
	
	/**
	 * 清空数据
	 */
	public function flushAll() {
		return $this->connect()->flushAll();
	}
	
	/**
	 * 数据入队列
	 * @param string $key KEY名称
	 * @param string|array $value 获取得到的数据
	 * @param bool $right 是否从右边开始入
	 */
	public function push($key, $value, $right = true) 
	{
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
		return $val;
	}
	
	/**
	 * 数据自增
	 * @param string $key KEY名称
	 */
	public function incr($key) 
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