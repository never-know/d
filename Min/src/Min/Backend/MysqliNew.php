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
	private $intrans = '';
	private $ref; 
	private $conf = [];
	private $connections = [];

	public function  __construct($db_key = '') 
	{	
		$this->conf = get_config('Mysql');
		mysqli_report(MYSQLI_REPORT_ALL);
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
		$linkid = $type.$this->active_db;
		
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
			
			$selected_db['user'] = urldecode($selected_db['user']);
			$selected_db['pass'] = isset($selected_db['pass']) ? urldecode($selected_db['pass']) : '';
			$selected_db['host'] = urldecode($selected_db['host']);
			$selected_db['path'] = urldecode($selected_db['path']);
			if (!isset($selected_db['port'])) {
				$selected_db['port'] = NULL;
			}	
			try {
				$error_code = 0;
				$connect = new mysqli($selected_db['host'], $selected_db['user'], $selected_db['pass'], substr($selected_db['path'], 1), $selected_db['port']);
			} catch (\Throwable $t) {
				$error_code = $t->getCode();
			}
			
		} while ($error_code != 0 && is_array($info) && !empty($info));
		
		if ($error_code != 0) {	
			throw new MinException('all mysql servers have gone away', 2);
		}
		return $connect;
	}
	
	private function retry($type, $stmt = null)
	{
		if (2006 == $this>connect($type)->errno || 2013 == $this>connect($type)->errno || false == $this->connect($type)->ping()) {
			if (empty($this->trans)) {
				if (!empty($stmt)) $stmt->close();
				$this>connect($type)->close();
				unset($this->connections[$type.$this->active_db]);
				return true;
			}
		} 

		if (!empty($stmt)) {
			$error_message = json_encode($stmt->error_list);
			$error_no = $stmt->errno;
			$stmt->close();	
		} else {
			$error_message = json_encode($this->connect($type)->error_list);
			$error_no = $this->connect($type)->errno;
		}
		throw new MinException($error_message, $error_no + $this->fix);
	}
	
	public function query($sql, $action, $marker = '', $param = [])
	{
		try {
			if (empty($marker)) {
				return $this->nonPrepareQuery($sql, $action);
			} else {
				return $this->realQuery($sql, $action, $marker, $param);
			}
			
		} catch (MinE) {
		
		
		}
	}
	
	public function realQuery($sql, $action, $marker = '', $param = [])
	{
		$type = $this->intrans ?: ((!empty($this->conf[$this->active]['rw_separate']) && in_array($action, ['single', 'couple'])) ? 'slave' : 'master'); 
		$round = 10 ;
		while ($round > 0) {
			$round -- ;
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
				
			} catch (\mysqli_sql_exception $e) {

				if (in_array($e->getCode(),[2006, 2013]) || false == $this->connect($type)->ping()) {
					if (empty($this->trans)) {
						$this>connect($type)->close();
						unset($this->connections[$type.$this->active_db]);
						$retry = true;
					}
				} 
				
			} catch (\Throwable $t) {
			
			} finally {
			
				if (!empty($stmt)) $stmt->close();
				if (!empty($get_result)) $get_result->free();
				if (!empty($retry)) continue;
				return $result;	
			}
		}
	} 
	
	public function nonPrepareQuery($sql, $action)
	{		
		$type = $this->intrans ?: ((!empty($this->conf[$this->active]['rw_separate']) && in_array($action, ['single', 'couple'])) ? 'slave' : 'master'); 
		
		$round = 10 ;
		while ($round > 0) {
			$round -- ;
			if ($result	= $this->connect($type)->query($sql, MYSQLI_STORE_RESULT)) {
				switch ( $action ) {
					case 'update' :
					case 'delete' :
						$result	= $this->connect($type)->affected_rows;
						break;
					case 'insert' :
						$result	= $this->connect($type)->insert_id;
						break;
					case 'single' :	
						$result	= $result->fetc_assoc();
						break;
					case 'couple' :
						$result	= $result->fetch_all();
						break;
				}
				return $result;
			} else {
				if (true === $this->retry($type)) continue;
			}
		}	
	}
	
	public function transaction_start($db = '', $type = 'master') 
	{
		if (isset($db)) $this->init($db);
		$round = 10 ;
		while ($round > 0) {
			$round -- ;
			if ($this->connect($type)->begin_transaction()) {
				$this->intrans = $type;
				return true;
			} else {
				if (true === $this->retry($type)) {
					continue;
				} 
			}
		}
	}
	
	public function transaction_commit() 
	{
		if ($this->connect($this->intrans)->commit()) {
			$this->intrans = '';
			return true;
		} else {
			throw new MinException(json_encode($this->connect($this->intrans)->error_list), $this->connect($this->intrans)->errno + $this->fix);
		}
	}
		 
	public function transaction_rollback()
	{ 
		if ($this->connect($this->intrans)->rollback()) {
			$this->intrans = '';
			return true;
		} else {
			throw new MinException(json_encode($this->connect($this->intrans)->error_list), $this->connect($this->intrans)->errno + $this->fix);
		}
	}
	
	public function autocommit($type, $mode)
	{
		return $this->connect($type)->autocommit($mode);
	}
		
}