<?php
/*****
数据库连接成功，但在查询时断掉。。。。。。

query(user')->;

****/
namespace Min\Database;

class Mysqli
{
	private $active_db	= 'default';
	private $intrans = '';
	private $ref; 
	private $conf = [];
	private $connections = [];
	private $rw_separate = true;

	public function  __construct($db_key = '') 
	{
		$this->conf = parse_ini_file(CONF_PATH.'/mysql.ini');	
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
			
			if (!$this->connections[$linkid]) {
                throw new \Exception(mysqli_connect_error());
            }	
			
			if (!$this->connections[$linkid]->set_charset('utf8')) {
                throw new \Exception(mysqli_error($this->connections[$linkid]));
            }	
		}
	
		return $this->connections[$linkid];
	}
	
	
	private function parse($type)
	{
		$info	= $this->conf[$this->active_db][$type];

		if (empty($info))  throw new \Exception('error get mysql connect info '.$info);
		
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
			$connect = mysqli_connect($selected_db['host'], $selected_db['user'], $selected_db['pass'], substr($selected_db['path'], 1), $selected_db['port']);
			
		} while (!$connect && is_array($info) && !empty($info));
		
		if(!$connect){	
			throw new \Exception('all mysql servers have gone away');
		}
		return $connect;
	}
	
	private function retry($type)
	{
		if (2006 == mysqli_errno($this->connect($type)) || false == mysqli_ping($this->connect($type))) {
			if (empty($this->trans)) {
				unset($this->connections[$type.$ths->active_db]);
				return true;
			}
		} 
		throw new \Exception(mysqli_error($this->connect($type)));
	}
	
	public function query($sql, $type, $marker = '', $param = [])
	{
		if (empty($marker)) {
			return $this->queryNP($sql, $type);
		}
		
		$db_type = $this->intrans ?: (($this->rw_separate == true && ($type=='single' || $type == 'couple')) ? 'slave' : 'master'); 
		
		while (true) {
		
			$stmt = mysqli_prepare($this->connect($db_type), $sql);
			
			if (!$stmt) { 
				if(true === $this->retry($db_type)) continue;
			}
			
			$merge		= [$stmt, $marker];
			
			foreach ($param as &$value) {
				$merge[] = $value;		
			}
			
			if (empty($this->ref)) {
				$this->ref	= new \ReflectionFunction('mysqli_stmt_bind_param');		
			}
			
			 $this->ref->invokeArgs($merge);
				
			if (mysqli_stmt_execute($stmt)) {

				switch ($type) {
					case 'update' :	
					case 'delete' :
						$result	= mysqli_stmt_affected_rows($stmt);
						break;	
					case 'insert' :
						$result	= mysqli_stmt_insert_id($stmt);
						break;
					case 'single' :	
						if ($result_single = mysqli_stmt_get_result($stmt)) {
							$result	= mysqli_fetch_assoc($result_single);
						} else {
							if (true === $this->retry($db_type)) continue;
						}
						break;
					case 'couple' :
						if ($result_couple = mysqli_stmt_get_result($stmt)) {
							$result	= mysqli_fetch_all($result_couple);
						} else {
							if (true === $this->retry($db_type)) continue;
						}
						break;				
				}
				mysqli_stmt_close($stmt);
				return $result;
				
			} else {		
				if (true === $this->retry($db_type)) continue;
				 
			}
		}
	} 
	
	public function queryNP($sql, $type)
	{		
		$db_type = empty($this->intrans) ? (($this->rw_separate == true && ($type == 'single' || $type=='couple')) ? 'slave' : 'master') : $this->intrans;
		
		while (true) {
		
			if ($result	= mysqli_query($this->connect($db_type),$sql)) {
				switch ( $type ) {
					case 'update' :
					case 'delete' :
						$result	= mysqli_affected_rows($this->connect($db_type));
						break;
					case 'insert' :
						$result	= mysqli_insert_id($this->connect($db_type));
						break;
					case 'single' :	
						$result	= mysqli_fetch_assoc($result);
						break;
					case 'couple' :
						$result	= mysqli_fetch_all($result);
						break;
				}
				return $result;
			} else {
				if (true === $this->retry($db_type)) continue;
			}
		}
		
	}
	
	public function transaction_start($type='master', $db = '') 
	{
		if (isset($db)) $this->set_active_db($db);
		while (true) {
			if (mysqli_begin_transaction($this->connect($type))) {
				$this->intrans = $type;
				return true;
			} else {
				if (true === $this->retry($type)) {
					continue;
				} else {
					throw new \Exception('transaction_start failed');
				}
			}
		}
	}
	
	public function transaction_commit() {
	
		if (mysqli_commit($this->connect($this->intrans))) {
			$this->intrans = '';
			return true;
		} else {
			throw new \Exception('transaction_commit failed');
		}
	}
		 
	public function transaction_rollback()
	{ 
		if (mysqli_rollback($this->connect($this->intrans))) {
			$this->intrans = '';
			return true;
		} else {
			throw new \Exception('transaction_rollback failed');
		}
	}
	
	public function autocommit($type, $mode)
	{
		return mysqli_autocommit($this->connect($type),$mode);
	}
		
}