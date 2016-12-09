<?php
/*****
数据库连接成功，但在查询时断掉。。。。。。

query(user')->;

****/
namespace Min\Backend;

use Min\MinException as MinException;

class Mysqli
{
	private $active_db	= 'default';
	private $intrans = '';
	private $ref; 
	private $conf = [];
	private $connections = [];
	private $rw_separate = true;
	private $fix	= 100000;

	public function  __construct($db_key = '') 
	{
		$this->conf = get_config('Mysql');;
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
			
			if (!$this->connections[$linkid]->set_charset('utf8')) {
                throw new MinException(json_encode($this->connections[$linkid]->error_list), $this->connections[$linkid]->errno + $this->fix);
            }	
		}
	
		return $this->connections[$linkid];
	}
	
	
	private function parse($type)
	{
		$info	= $this->conf[$this->active_db][$type];

		if (empty($info))  throw new MinException('can not get mysql connect info when type ='.$type, $this->fix+1);
		
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
			
			$connect = new mysqli($selected_db['host'], $selected_db['user'], $selected_db['pass'], substr($selected_db['path'], 1), $selected_db['port']);
			
		} while ($connect->connect_error && is_array($info) && !empty($info));
		
		if ($connect->connect_error) {	
			throw new MinException('all mysql servers have gone away', $this->fix + 2);
		}
		return $connect;
	}
	
	private function retry($type, $stmt = null)
	{
		if (2006 == $this->connect($type)->errno || false == $this->connect($type)->ping()){
			if (empty($this->trans)) {
				if (!empty($stmt)) $stmt->close();
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
		if (empty($marker)) {
			return $this->queryNP($sql, $action);
		}
		
		$type = $this->intrans ?: (($this->rw_separate == true && ($action=='single' || $action == 'couple')) ? 'slave' : 'master'); 
		
		while (true) {
		
			$stmt =  $this->connect($type)->prepare($sql);
			
			if (!$stmt) { 
				if(true === $this->retry($type)) continue;
			}
			
			$merge		= [$stmt, $marker];
			
			foreach ($param as $key => &$value) {
				$merge[] = $value;		
			}
			
			if (empty($this->ref)) {
				$this->ref	= new \ReflectionFunction('mysqli_stmt_bind_param');		
			}
			
			 $this->ref->invokeArgs($merge);
				
			if ($stmt->execute()) {

				switch ($action) {
					case 'update' :	
					case 'delete' :
						$result	= $stmt->affected_rows;
						break;	
					case 'insert' :
						$result	= $stmt->insert_id;
						break;
					case 'single' :	
						if ($result_single = $stmt->get_result()) {
							$result	= $result_single->fetch_assoc();
							$result_single->free_result();
						} elseif (true === $this->retry($type))  {
							continue; 
						}
						break;
					case 'couple' :
						if ($result_couple = $stmt->get_result()) {
							$result	= $result_couple->fetch_all();
							$result_couple->free_result();
						} elseif (true === $this->retry($type)) {
							continue;
						}
						break;				
				}
				
				$stmt->close();
				return $result;
				
			} elseif (true === $this->retry($type, $stmt)) {			
				 continue; 
			}
		}
	} 
	
	public function queryNP($sql, $action)
	{		
		$type = empty($this->intrans) ? (($this->rw_separate == true && ($action == 'single' || $action=='couple')) ? 'slave' : 'master') : $this->intrans;
		
		while (true) {
		
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
		
		while (true) {
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