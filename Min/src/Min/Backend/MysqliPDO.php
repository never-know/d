<?php
/*****
数据库连接成功，但在查询时断掉。。。。。。

query(user')->;

 column is unique or primary
 insert into table (column) values ("column_value")  ON DUPLICATE KEY UPDATE id =LAST_INSERT_ID(id) ;
 
 insert into table (column) values ("column_value") ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), logincount =
		(CASE logincount 
			WHEN 12 THEN 13 
			ELSE logincount 
		end)

****/

namespace Min\Backend;

use Min\MinException as MinException;

class MysqliPDO
{
	private $ref = null; 
	private $conf = [];
	private $intrans = [];
	private $connections = [];
	private $active_db	= 'default';
	private $prefix_key	= 'default';

	public function  __construct($db_key = '') 
	{	
		$this->conf = config_get('mysqlpdo');
	}
	 
	public function init($active_db) 
	{
		if (!empty($active_db) && $this->active_db != $active_db) {
		
			$this->prefix_key = $this->active_db = 'default';
			
			if (!empty($this->conf[$active_db])) {
				$this->active_db = $active_db;
				if (!empty($this->conf[$active_db]['prefix'][$active_db])) {
					$this->prefix_key = $active_db;
				}
			}
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

		if (empty($info))  throw new \PDOException('mysql info not found when type ='.$type, -2);
		
		do {
			if (is_array($info)) {
				$db_index = mt_rand(0, count($info) - 1);
				$tmp =  array_splice($info, $db_index, 1);
				$selected_db =  $tmp[0];	
			} else {
				$selected_db = $info;
			}
			
			$selected_db = parse_url($selected_db);
			if (!$selected_db) {
				throw new \PDOException('mysql info parse error when type ='.$type, -3);
			}
			try {
				$error_code = 0;
				$connect = new \PDO($selected_db['host'], $selected_db['user'], $selected_db['pass'], array(
					\PDO::ATTR_EMULATE_PREPARES => false,
					//\PDO::ATTR_PERSISTENT => true,
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
				));
            
			} catch (\Throwable $t) {
				$error_code = 1;
			}	
			
		} while ($error_code != 0 && is_array($info) && !empty($info));
		
		if ($error_code != 0) {	
			throw new \PDOException('all mysql servers have gone away', -1);
		}
		return $connect;
	}
	 
	public function query($sql, $param)
	{
		//$sql = strtr($sql, ['{{' => $this->conf[$this->active_db]['prefix'][$this->prefix_key], '}}' => '']);
		$sql = preg_replace('/\{\{([a-z_]+)\}\}/', $this->conf[$this->active_db]['prefix'][$this->prefix_key] . '$1', $sql, 10);
		
		watchdog($sql);
		
		$sql_splite = preg_split('/\s+/', $sql, 2);

		//$action = strtolower($sql_splite[0]);
		$action = $sql_splite[0];

		if (!in_array($action, ['SELECT', 'INSERT', 'UPDATE', 'DELETE'], true)) {
			throw new \PDOException('Can not recognize action in sql: '. $sql, -4);
		}
		
		$type = (!empty($this->intrans[$this->active_db]) || empty($this->conf[$this->active_db]['rw_separate']) || 'select' !== $action) ? 'master' : 'slave'; 
		
		if (is_array($param) && !empty($param)) {
			return $this->realQuery($type, $sql, $action, $param);
		} else {
			return $this->nonPrepareQuery($type, $sql, $action);
		}
	}
	
	private function realQuery($type, $sql, $action, $param)
	{
		$round = 3;
		
		while ($round > 0) {
			$round -- ;
			$result = [];
			$on_error = false;	
			try {
				$stmt =  $this->connect($type)->prepare($sql); 
				foreach ($param as $key => $value) {
					
					$vaule_type = \PDO::PARAM_STR;
                    switch ($value) {
                        case is_int($value):
                            $vaule_type = \PDO::PARAM_INT;
                            break;
                        case is_bool($value):
                            $vaule_type = \PDO::PARAM_BOOL;
                            break;
                        case is_null($value):
                            $vaule_type = \PDO::PARAM_NULL;
                            break;
                    }
	 
                    $stmt->bindValue($key, $value, $vaule_type);
				}

				$stmt->execute();
	 
				switch ($action) {
					case 'INSERT' :
						$result['id'] 		= $this->lastInsertId($type);
					case 'UPDATE' :
					case 'DELETE' :
						$result['effect'] 	= $stmt->rowCount();
						break;
					case 'SELECT' :
						if (preg_match('/limit\s+1\s*$/i',$sql)) {
							$result		= $stmt->fetch(\PDO::FETCH_ASSOC);
						} else {
							$result		= $stmt->fetchAll(\PDO::FETCH_ASSOC);
						}
						break;
				}
				return $result;	
				
			} catch (\Throwable $e) {
				$on_error = true;
				if (empty($this->intrans[$this->active_db]) && ($e instanceof \PDOException) && in_array(intval($e->errorInfo[1]), [2006, 2013])) {
					watchdog($e);
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
		$round = 3 ;
		while ($round > 0) {
			$round -- ;
			$result = [];
			$on_error = false;
			try {
				switch ($action) {
					case 'UPDATE' :
					case 'DELETE' :
					case 'INSERT' :
						$result['effect']	= $this->connect($type)->exec($sql);
						break;	
					case 'SELECT' :	
						$stmt	= $this->connect($type)->query($sql);
						if (preg_match('/limit\s+1\s*$/i',$sql)) {
							$result	= $stmt->fetch(\PDO::FETCH_ASSOC);
						} else {
							$result	= $stmt->fetchAll(\PDO::FETCH_ASSOC);
						}
						break;
				}

				if ($action === 'INSERT') {
					$result['id'] =  $this->lastInsertId($type);
				}
				return $result;
				
			} catch (\Throwable $e) {
				
				$on_error = true;
				if (empty($this->intrans[$this->active_db]) && ($e instanceof \PDOException) && in_array($e->errorInfo[1], [2006, 2013])) {
					watchdog($e);
					continue; 
				} 
				
				throw $e;	
			} finally {	
				if (!empty($stmt)) 	$stmt->closeCursor();
				if (true === $on_error) $this->close($type);
			}
		}	
	}
	
	public function transaction_start($type = 'master') 
	{
		if(empty($this->intrans[$this->active_db])) {
			$this->intrans[$this->active_db] = 1;
			$round = 3;
			while ($round > 0) {
				$round--;
				$on_error = false;
				try {
					$this->connect($type)->beginTransaction();
					watchdog($this->inTransaction());
					return true;
				} catch (\Throwable $e) {
					$on_error = true;
					if (empty($this->intrans[$this->active_db]) && ($e instanceof \PDOException) && in_array($e->errorInfo[1], [2006, 2013])) {
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
	
	public function transaction_commit($type = 'master') 
	{	
		if (1 === $this->intrans[$this->active_db]) {
			return $this->connect($type)->commit(); 
		} 
		$this->intrans[$this->active_db]--;	 
		return true;
	}
		 
	public function transaction_rollback($type = 'master')
	{ 
		if ($this->intrans[$this->active_db] == 1 ) {
			return $this->connect($type)->rollBack();
		} 
		$this->intrans[$this->active_db]--;
		return true;
	}
	
	private function getLinkId($type){
		
		return $type.$this->active_db;
	}
	
	public function lastInsertId($type)
    {
        return $this->connect($type)->lastInsertId();
    }
	
	public function inTransaction($type = 'master') 
	{

		return $this->connect($type)->inTransaction();
	}
	
	public function close($type)
	{
		$link_id = $this->getLinkId($type);
		if (!empty($this->connections[$link_id])) {
		  unset($this->connections[$link_id]);
		}
	}
}