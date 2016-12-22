<?php
/*****
数据库连接成功，但在查询时断掉。。。。。。

query(user')->;

****/
namespace Min\Backend;

use Min\MinException as MinException;

class MysqliPDO
{
	private $active_db	= 'default';
	private $ref = null; 
	private $conf = [];
	private $intrans = [];
	private $connections = [];
	private $query_log = [];

	public function  __construct($db_key = '') 
	{	
		$this->conf = get_config('mysql');
	}
	 
	public function init($active_db) 
	{
		if (!empty($active_db) && !empty($this->conf[$active_db])) {
			$this->active_db = $active_db;
		}
		return $this;		
	}
	
	private function connect($type = 'master')
	{
		$linkid = $this->getLinkId($type);
		
		if (empty($this->connections[$linkid])) {
			$this->connections[$linkid] = $this->parse($type);
		}
		return $this->connections[$linkid];
	}
	
	
	private function parse($type)
	{
		$info	= $this->conf[$this->active_db][$type];

		if (empty($info))  throw new \PDOException('mysql info not found when type ='.$type, -1);
		
		do {
			if (is_array($info)) {
				$db_index = mt_rand(0, count($info) - 1);
				$tmp =  array_splice($info, $db_index, 1);
				$selected_db =  $tmp[0];	
			} else {
				$selected_db = $info;
			}
			
			$selected_db = parse_url($selected_db);
			$selected_db['host'] = urldecode($selected_db['host']);
			$selected_db['user'] = urldecode($selected_db['user']);
			$selected_db['pass'] = isset($selected_db['pass']) ? urldecode($selected_db['pass']) : '';
			$selected_db['port'] = $selected_db['port'] ?? '3306';
			$selected_db['db'] = urldecode($selected_db['fragment']);
			
			$dsn = 'mysql:dbname='. $selected_db['db']. ';host='. $selected_db['host']. ':'. $selected_db['port'].';charset=utf8';
			
			try {
				$error_code = 0;
				$connect = new \PDO($dsn, $selected_db['user'], $selected_db['pass'], array(
					\PDO::ATTR_EMULATE_PREPARES => false,
					\PDO::ATTR_PERSISTENT => true,
					\PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
				));
            
			} catch (\Throwable $t) {
				watchdog($t);
				$error_code = 1;
			}	
			
		} while ($error_code != 0 && is_array($info) && !empty($info));
		
		if ($error_code != 0) {	
			throw new \PDOException('all mysql servers have gone away', -2);
		}
		return $connect;
	}
	 
	public function query($sql, $param)
	{
		$type = (empty($this->intrans[$this->active_db]) && !empty($this->conf[$this->active_db]['rw_separate']) && in_array($action, ['single', 'couple'])) ? 'slave' : 'master'; 
		
		$this->query_log[] = $sql = strtr($sql, ['{' => $this->conf[$this->active_db]['prefix'], '}' => '']);
		
		watchdog($sql);
		
		$sql_splite = explode(' ', preg_replace('/\s+|\t+|\n+/', ' ', $sql), 2);

		$action = strtolower($sql_splite[0]);

		if (!in_array($action, ['select', 'show', 'insert', 'update', 'delete'])) {
			throw new \PDOException('Can not recognize action in sql '. $sql, -3);
		}
	
		if (empty($param)) {
			return $this->nonPrepareQuery($type, $sql, $action);
		} else {
			return $this->realQuery($type, $sql, $action, $param);
		}

	}
	
	private function realQuery($type, $sql, $action, $param = [])
	{
		$round = 5;
		while ($round > 0) {
			$round -- ;
			$on_error = false;	
			try {
				$stmt =  $this->connect($type)->prepare($sql); 
				foreach ($param as $key => $value) {
					$vaule_type = PDO::PARAM_STR;
                    switch ($key) {
                        case is_int($key):
                            $vaule_type = PDO::PARAM_INT;
                            break;
                        case is_bool($key):
                            $vaule_type = PDO::PARAM_BOOL;
                            break;
                        case is_null($key):
                            $vaule_type = PDO::PARAM_NULL;
                            break;
                    }
					
                    $stmt->bindValue($key, $value, $vaule_type);
				}

				$stmt->execute();
				
				switch ( $action ) {
					case 'update' :
					case 'delete' :
						$result	= $stmt->rowCount();
						break;
					case 'insert' :
						$result	= $this->lastInsertId($type);
						break;
					case 'select' :
					case 'show' :
						$result	= $stmt->fetchAll(\PDO::FETCH_ASSOC);
						break;
				}
				return $result;	
				
			} catch (\Throwable $e) {
				$on_error = true;
				if (empty($this->intrans[$this->active_db]) && ($e instanceof \PDOException) && in_array($e->getCode(), [2006, 2013]) {
					continue; 
				} 
				throw $e;				
			} finally {
				if (!empty($stmt)) 	$stmt->closeCursor();
				if (true === $on_error) $this->close($type);
			}
		}
	} 
	
	private function nonPrepareQuery($type, $sql, $action)
	{		
		$round = 5 ;
		while ($round > 0) {
			$round -- ;
			$on_error = false;
			try {

				switch ($action) {
					case 'update' :
					case 'delete' :
					case 'insert' :
						$result	= $this->connect($type)->exe($sql);
						break;
					case 'show' :	
					case 'select' :	
						$result	= $this->connect($type)->query($sql);
						break;
				}
				
				if ($action == 'insert') {
					$result = $this->lastInsertId($type);
				}
				return $result;
				
			} catch (\Throwable $e) {
				$on_error = true;
				if (empty($this->intrans[$this->active_db]) && ($e instanceof \PDOException) && in_array($e->getCode(), [2006, 2013])) {
					continue; 
				} 
				throw $e;
				
			} finally {	
				if (true === $on_error) $this->close($type);
			}
		}	
	}
	
	public function transaction_start() 
	{
		$type = 'master';
		if(empty($this->intrans[$this->active_db])) {
			$this->intrans[$this->active_db] = 1;
			$round = 5;
			while ($round > 0) {
				$round--;
				try {
					$this->connect($type)->beginTransaction();
					return true;
				} catch (\Throwable $e) {
					if (empty($this->intrans[$this->active_db]) && ($e instanceof \PDOException) && in_array($e->getCode(), [2006, 2013])) {
						continue; 
					}  
					throw $e; 
				} finally {	
					if (true === $on_error) $this->close($type);
				}
			}
		} else {
			$this->intrans[$this->active_db]++;
			return true;
		}	 
	}
	
	public function transaction_commit() 
	{	
		$type = 'master';
		if ($this->intrans[$this->active_db] == 1 ) {
			$this->connect($type)->commit(); 
		} 
		$this->intrans[$this->active_db]--;	 
	}
		 
	public function transaction_rollback()
	{ 
		$type = 'master';
		if ($this->intrans[$this->active_db] == 1 ) {
			$this->connect($type)->rollBack();
		} 
		$this->intrans[$this->active_db]--;
	}
	
	private function getLinkId($type){
		
		return $type.$this->active_db;
	}
	
	public function lastInsertId($type)
    {
        return $this->connect($type)->lastInsertId();
    }
		
}