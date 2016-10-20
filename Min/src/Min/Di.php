<?php
namespace Min;

class Di 
{
    private $definitions = [];
    private $instances = [];

    public function get($name, $params = [])
	{
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->definitions[$name])) {
            return null;
        }
        
        $concrete = $this->definitions[$name]['class'];
        
        $obj = null;

        if ($concrete instanceof \Closure) {
			if (empty($params)) {
				$obj = $concrete();
			} else {
				$obj = call_user_func_array($concrete,$params);
			}
        }
        elseif (is_string($concrete)) {
            if (empty($params)) {
                $obj = new $concrete;
            } else {
                $class = new \ReflectionClass($concrete);
                $obj = $class->newInstanceArgs($params);
            }
        }

        if ($this->definitions[$name]['shared'] == true && !is_null($obj)) {
            $this->instances[$name] = $obj;
        }
        
        return $obj;
    }

    public function has($name)
	{
        return isset($this->definitions[$name]) || isset($this->instances[$name]);
    }

    public function remove($name)
	{
        unset($this->definitions[$name], $this->instances[$name]);
    }

    public function set($name,$class)
	{
        $this->registerService($name, $class);
    }

    public function setShared($name, $class)
	{
        $this->registerService($name, $class, true);
    }

    private function registerService($name, $class, $shared = false)
	{
        $this->remove($name);
        if (!($class instanceof \Closure) && is_object($class)) {
            $this->instances[$name] = $class;
        } else {
            $this->definitions[$name] = ['class'=>$class, 'shared'=>$shared];
        }
    }
}