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
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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

		if (empty($info))  throw new \MinException('mysql info not found when type ='.$type, 1);
		
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
			
			$dsn = 'mysql:dbname='. $selected_db['db']. ';host='. $selected_db['host']. ':'. $selected_db['port'];
			try {
				$error_code = 0;
				$connect = new \PDO($dsn, $selected_db['user'], $selected_db['pass'], array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            
				# We can now log any exceptions on Fatal error. 
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
				# Disable emulation of prepared statements, use REAL prepared statements instead.
				$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
				} catch (\Throwable $t) {
					watchdog($t);
					$error_code = 1;
			}	
		} while ($error_code != 0 && is_array($info) && !empty($info));
		
		if ($error_code != 0) {	
			throw new \MinException('all mysql servers have gone away', 2);
		}
		return $connect;
	}
	 
	public function query($sql, $action, $marker, $param)
	{
		$type = (empty($this->intrans[$this->active_db]) && !empty($this->conf[$this->active_db]['rw_separate']) && in_array($action, ['single', 'couple'])) ? 'slave' : 'master'; 
		
		$this->query_log[] = $sql = strtr($sql, ['{' => $this->conf[$this->active_db]['prefix'], '}' => '']);
		
		watchdog($sql);
		
		if (empty($marker)) {
			return $this->nonPrepareQuery($type, $sql, $action);
		} else {
			return $this->realQuery($type, $sql, $action, $marker, $param);
		}

	}
	
	private function realQuery($type, $sql, $param = [])
	{
		$round = 5;
		while ($round > 0) {
			$round -- ;
			$on_error = false;	
			try {
				$this->stmt =  $this->connect($type)->prepare($sql); 
				foreach ($param as $key => $value) {
				
					$type = PDO::PARAM_STR;
                    switch ($key) {
                        case is_int($key):
                            $type = PDO::PARAM_INT;
                            break;
                        case is_bool($key):
                            $type = PDO::PARAM_BOOL;
                            break;
                        case is_null($key):
                            $type = PDO::PARAM_NULL;
                            break;
                    }
                    // Add type when binding the values to the column
                    $this->stmt->bindValue(':'. $key, $value, $type);
				}

				# Execute SQL 
				$this->stmt->execute();
				
				$rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $sql));
        
				# Which SQL statement is used 
				$statement = strtolower($rawStatement[0]);
        
				if ($statement === 'select' || $statement === 'show') {
					return $this->sQuery->fetchAll($fetchmode);
				} elseif ($statement === 'insert') {
					return $this->lastInsertId($type);
				} elseif ($statement === 'update' || $statement === 'delete') {
					return $this->sQuery->rowCount();
				} else {
					return NULL;
				}
				return $result;	
				
			} catch (\Throwable $e) {
				$on_error = true;
				if (empty($this->intrans[$this->active_db]) && ($e instanceof \mysqli_sql_exception) && (in_array($e->getCode(), [2006, 2013]) || false == $this->connect($type)->ping())) {
					continue; 
				} 
				throw $e;
				
			} finally {
				if (!empty($stmt)) $stmt->close();
				if (!empty($get_result)) $get_result->free();
				if (true === $on_error) {
					$this->connect($type)->close();
					unset($this->connections[$this->getLinkId($type)]);
				}
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
				$get_result	= $this->connect($type)->query($sql, MYSQLI_STORE_RESULT);
				switch ( $action ) {
					case 'update' :
					case 'delete' :
						$result	= $this->connect($type)->affected_rows;
						break;
					case 'insert' :
						$result	= $this->connect($type)->insert_id;
						break;
					case 'single' :	
						$result	= $get_result->fetc_assoc();
						break;
					case 'couple' :
						$result	= $get_result->fetch_all();
						break;
				}
				return $result;
			} catch (\Throwable $e) {
				$on_error = true;
				if (empty($this->intrans[$this->active_db]) && ($e instanceof \mysqli_sql_exception) && (in_array($e->getCode(), [2006, 2013]) || false == $this->connect($type)->ping())) {
					continue; 
				} 
				
				throw $e;
				
			} finally {
				if ($get_result instanceof \mysqli_result) $get_result->free();
				if (true === $on_error) {
					$this->connect($type)->close();
					unset($this->connections[$this->getLinkId($type)]);
				}
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
					$this->connect($type)->begin_transaction();
					return true;
				} catch (\Throwable $e) {
					if (empty($this->trans) && ($e instanceof \mysqli_sql_exception) && (in_array($e->getCode(), [2006, 2013]) || false == $this->connect($type)->ping())) {
						continue; 
					} else {
						throw $e;
					}
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
			$this->connect($type)->rollback();
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