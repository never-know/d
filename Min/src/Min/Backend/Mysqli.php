<?php
/*****
数据库连接成功，但在查询时断掉。。。。。。

query(user')->;

****/
namespace Min\Backend;

use Min\MinException as MinException;

class Mysqli
{
	private $ref; 
	private $conf = [];
	private $intrans = [];
	private $connections = [];
	private $active_db	= 'default';

	public function  __construct($db_key = '') 
	{
		$this->conf = get_config('mysql');;
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
                throw $this->genException($type);
            }	
		}
	
		return $this->connections[$linkid];
	}
	
	
	private function parse($type)
	{
		$info	= $this->conf[$this->active_db][$type];

		if (empty($info))  throw new MinException('can not get mysql connect info when type ='.$type, -2);
		
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
			$selected_db['fragment'] = urldecode($selected_db['fragment']); //db
			$selected_db['port'] = $selected_db['port'] ?? null;
			
			$connect = new \mysqli($selected_db['host'], $selected_db['user'], $selected_db['pass'], $selected_db['fragment'], $selected_db['port']);
			
		} while ($connect->connect_error && is_array($info) && !empty($info));
		
		if ($connect->connect_error) {	
			throw new MinException('all mysql servers have gone away', -1);
		}
		return $connect;
	}
	
	private function retry($type)
	{
		if (empty($this->intrans[$this->active_db]) &&
			(in_array($this>connect($type)->errno, [2006, 2013]) || false == $this->connect($type)->ping())) {
				$this->close($type);
				return true;
			}
 
		throw $this->genException($type);
	}
	
	public function query($sql, $marker = '', $param = [])
	{
		$this->query_log[] = $sql = strtr($sql, ['{' => $this->conf[$this->active_db]['prefix'], '}' => '']);
		
		watchdog($sql);
		
		$sql_splite = explode(' ', preg_replace('/\s+|\t+|\n+/', ' ', $sql), 2);

		$action = strtolower($sql_splite[0]);
		
		if (!in_array($action, ['select', 'insert', 'update', 'delete'])) {
			throw new MinException('Can not recognize action in sql: '. $sql, -4);
		}
		
		$type = (empty($this->intrans[$this->active_db]) && !empty($this->conf[$this->active_db]['rw_separate']) && 'select' == $action) ? 'slave' : 'master'; 

		if (empty($marker)) {
			return $this->nonPrepareQuery($type, $sql, $action);
		} else {
			return $this->realQuery($type, $sql, $action, $marker, $param);
		}	
	}
	
	public function realQuery($sql, $action, $marker, $param)
	{
		$round = 5;
		while ($round > 0) {
		
			$round -- ;
			$result = false;
			
			if ($stmt = $this->connect($type)->prepare($sql)) {

				$merge		= [$stmt, $marker];
			
				foreach ($param as $key => $value) {
					$merge[] = &$value;		
				}
			
				if (empty($this->ref)) {
					$this->ref	= new \ReflectionFunction('mysqli_stmt_bind_param');		
				}
		
				if ($this->ref->invokeArgs($merge) && $stmt->execute()) {
				
					switch ($action) {
						case 'update' :	
						case 'delete' :
							$result	= $stmt->affected_rows;
							break;	
						case 'insert' :
							$result	= $stmt->insert_id;
							break;
						case 'select' :	
							if ($result_single = $stmt->get_result()) {	
								$result = $result_single->fetch_all();
								$result_single->free_result();
							}  
							break;			
					}
				}
				
				$stmt->close();
			}
			
			if ((false == $result || -1 == $result) && !is_array($result) && $this->retry($type)) {
				continue;
			}
			return $result;
		}
	} 
	
	public function nonPrepareQuery($type, $sql, $action)
	{			
		$round = 5 ;
		while ($round > 0) {
		
			$round -- ;
			$result = false;
			
			if ($result_single	= $this->connect($type)->query($sql, MYSQLI_STORE_RESULT)) {
				switch ( $action ) {
					case 'update' :
					case 'delete' :
						$result	= $this->connect($type)->affected_rows;
						break;
					case 'insert' :
						$result	= $this->connect($type)->insert_id;
						break;
					case 'select' :
						$result	= $result_single->fetch_all();
						$result_single->free_result();
						break;
				}
			}  
			
			if ((false == $result || -1 == $result) && !in_array($result) && $this->retry($type)) {
				continue;
			}	
			return $result;
		}	
	}
	
	public function tStart() 
	{
		$type = 'master';
		if (empty($this->intrans[$this->active_db])) {
			if ($this->connect($type)->begin_transaction()) {
				$this->intrans[$this->active_db] = 1;
				return true;	
			} else {
				throw genException($type);
			}
		} else {
			$this->intrans[$this->active_db]++;
			return true;
		}

	}
	
	public function tCommit() 
	{	
		$type = 'master';
		if ($this->intrans[$this->active_db] == 1 ) {
			$this->connect($type)->commit(); 
		} 
		$this->intrans[$this->active_db]--;	 
	}
		 
	public function tRollback()
	{ 
		$type = 'master';
		if ($this->intrans[$this->active_db] == 1 ) {
			$this->connect($type)->rollback();
		} 
		$this->intrans[$this->active_db]--;
	}
	
	private function inTransaction(){
		return (!empty($this->intrans[$this->active_db]));
	}
	
	public function autocommit($type, $mode)
	{
		return $this->connect($type)->autocommit($mode);
	}
	
	public function genException($type)
	{
		$link_id = $this->getLinkId($type);
		return new MinException(safe_json_encode($this->connections[$link_id]->error_list), $this->connections[$link_id]->errno);
	}
	
	private function getLinkId($type){
		return $type.$this->active_db;
	}
	
	public function close($type)
	{
		$link_id = $this->getLinkId($type);
		if (!empty($this->connections[$link_id]) {
			$this->connections[$link_id]->close();
			unset($this->connections[$link_id]);
		}
		
	}
		
}