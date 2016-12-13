<?php
/*****
数据库连接成功，但在查询时断掉。。。。。。

query(user')->;

****/
namespace Min\Backend;

use Min\MinException as MinException;

class MysqliNew
{
	private $active_db	= 'default';
	private $ref = null; 
	private $conf = [];
	private $intrans = [];
	private $connections = [];

	public function  __construct($db_key = '') 
	{	
		$this->conf = get_config('Mysql');
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
			$this->connections[$linkid]->set_charset('utf8'); 
		}
		return $this->connections[$linkid];
	}
	
	
	private function parse($type)
	{
		$info	= $this->conf[$this->active_db][$type];

		if (empty($info))  throw new MinException('mysql info not found when type ='.$type, 1);
		
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
			$selected_db['fragment'] = urldecode($selected_db['fragment']);
			$selected_db['port'] = $selected_db['port'] ?? null;
		 
			try {
				$error_code = 0;
				$connect = new mysqli($selected_db['host'], $selected_db['user'], $selected_db['pass'], $selected_db['fragment'], $selected_db['port']);
			} catch (\Throwable $t) {
				$error_code = $t->getCode();
			}
			
		} while ($error_code != 0 && is_array($info) && !empty($info));
		
		if ($error_code != 0) {	
			throw new MinException('all mysql servers have gone away', 2);
		}
		return $connect;
	}
	 
	public function query($sql, $action, $marker = '', $param = [])
	{
		$type = (empty($this->intrans[$this->active_db]) && !empty($this->conf[$this->active]['rw_separate']) && in_array($action, ['single', 'couple'])) ? 'slave' : 'master'; 

		if (empty($marker)) {
			return $this->nonPrepareQuery($type, $sql, $action);
		} else {
			return $this->realQuery($type, $sql, $action, $marker, $param);
		}

	}
	
	public function realQuery($type, $sql, $action, $marker, $param)
	{
		$round = 5;
		while ($round > 0) {
			$round -- ;
			$on_error = false;	
			try {
				$stmt =  $this->connect($type)->prepare($sql); 
				$merge		= [$stmt, $marker];
				foreach ($param as $key => &$value) {
					$merge[] = $value;		
				}
				if (empty($this->ref)) {
					$this->ref	= new \ReflectionFunction('mysqli_stmt_bind_param');		
				}
				
				$this->ref->invokeArgs($merge);
				$stmt->execute();
				
				switch ($action) {
					case 'update' :	
					case 'delete' :
						$result	= $stmt->affected_rows;
						break;	
					case 'insert' :
						$result	= $stmt->insert_id;
						break;
					case 'single' :	
						$get_result = $stmt->get_result()
						$result	= $get_result->fetch_assoc();
						break;
					case 'couple' :
						$get_result = $stmt->get_result();
						$result	= $get_result->fetch_all();
						break;				
				}
				 
				return $result;	
				
			} catch (\Throwable $e) {
				$on_error = true;
				if (empty($this->trans) && ($e instanceof \mysqli_sql_exception) && (in_array($e->getCode(), [2006, 2013]) || false == $this->connect($type)->ping())) {
					continue; 
				} 
				throw $e;
				
			} finally {
				if (!empty($stmt)) $stmt->close();
				if (!empty($get_result)) $get_result->free();
				if (true === $on_error) {
					$this>connect($type)->close();
					unset($this->connections[$this->getLinkId($type)]);
				}
			}
		}
	} 
	
	public function nonPrepareQuery($type, $sql, $action)
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
				if (empty($this->trans) && ($e instanceof \mysqli_sql_exception) && (in_array($e->getCode(), [2006, 2013]) || false == $this->connect($type)->ping())) {
					continue; 
				} 
				
				throw $e;
				
			} finally {
				if ($get_result instanceof \mysqli_result) $get_result->free();
				if (true === $on_error) {
					$this>connect($type)->close();
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
		$this->connect($type)->rollback();
		$this->intrans[$this->active_db] = 0;
		 
	}
	
	private function getLinkId($type){
		
		return $type.$this->active_db
	}
		
}