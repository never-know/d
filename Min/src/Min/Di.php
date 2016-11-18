<?php
namespace Min;

class Di 
{
    private $definitions = [];
    private $instances = [];

    public function getService($name, $params = [])
	{
		if (empty($name)) {
			return null;
		}
		
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->definitions[$name])) {
			
			try {
				if (empty($params)) {
					return  new $name;
				} else {
					$obj = new \ReflectionClass($name);
					return $obj->newInstanceArgs($params);
				}	
			} catch (\Throwable $t) {
				return null;
			}
        }
        
        $concrete = $this->definitions[$name]['class'];
        
        $obj = null;

        if ($concrete instanceof \Closure) {
			if (empty($params)) {
				$obj = $concrete();
			} else {
				$obj = call_user_func_array($concrete,$params);
			}
        } elseif (is_string($concrete)) {
			try {
				if (empty($params)) {
					$obj = new $concrete;
				} else {
					$class = new \ReflectionClass($concrete);
					$obj = $class->newInstanceArgs($params);
				}
			} catch (\Throwable $t) {
				$obj = null;
			}
		}
        if ($this->definitions[$name]['shared'] == true && !is_null($obj)) {
            $this->instances[$name] = $obj;
        }
        
        return $obj;
    }

    public function hasService($name)
	{
		if (empty($name)) {	
			throw new \Exception('service name can not be empty');
		}
        return isset($this->definitions[$name]) || isset($this->instances[$name]);
    }

    public function removeService($name)
	{
        unset($this->definitions[$name], $this->instances[$name]);
    }

    public function setService($name,$class)
	{
        $this->registerService($name, $class);
    }

    public function setShared($name, $class)
	{
        $this->registerService($name, $class, true);
    }

    private function registerService($name, $class, $shared = false)
	{	
		if (empty($name)) {	
			throw new \Exception('service name can not be empty');
		}
        $this->removeService($name);
        if (!($class instanceof \Closure) && is_object($class)) {
            $this->instances[$name] = $class;
        } else {
            $this->definitions[$name] = ['class'=>$class, 'shared'=>$shared];
        }
    }
	public function getBackendService($class, $params){
		
		if (empty($class)) {
			return null;
		}
		
        if (!isset($this->instances[$class])) {
			try {
				if (empty($params)) {
					$this->instances[$class] = new $class;
				} else {
					$obj = new \ReflectionClass($class);
					return $obj->newInstanceArgs($params);
				}
				
			} catch (\Throwable $t) {
				$this->instances[$class] = null;
			}
        }
		
		return $this->instances[$class];
	}
	
}