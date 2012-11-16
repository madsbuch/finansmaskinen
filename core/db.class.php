<?php
/**
* \core\db, this is for MySQL connections
*
* the DML abstractions should be safe to injections 
*/
namespace core;
class db{
	
	/**
	* the system uses multiple databases, but also a lot of connections to each
	* the function will return an active connection to the database.
	*
	* this should be used, unless there is a very good reason for something else
	*/
	public static function getInstance($config, $type = "mysql"){
		if($type == "mysql")
			return new \core\db($config);
		elseif($type == "mongo")
			return new \core\db_mongo($config);
		else
			throw new \Exception("We don't understand $type \n backtrace: " . print_r(debug_backtrace(), true));
	}
	
	/**
	* public dbh, apps can use the DBH object directly (database handle)
	*/
	public $dbh;
	
	/**
	* Construct a new \database connection
	*
	* @param string $configIndex defined in config. the supergroup to fetchdata from
	*/
	function __construct($config){
		if(DEBUG)
			$this->debug = \core\debug::getInstance();
		
		//fetch information
		if(is_array($config))
			$dbarray = $config['mysql'];
		elseif(is_string($config))
			$dbarray = \config\config::$configs[$config]['mysql'];
		
		//create PDO object, to hold connection
		$dsn = 'mysql:dbname='.$dbarray['dbname'].';host='.$dbarray['host'].';charset=utf8';

		try {
			$this->dbh = new \PDO($dsn, $dbarray['username'], $dbarray['password']);
		}
		//using internal exeption handler
		catch (PDOException $e){
			if(DEBUG){
				$this->debug->eventByTime('connection failed');
				echo "Connection failed: ".$e->getMessage();
			}
		}
	}
	
	function __destruct(){
		//destruct PDO handle
		$this->dbh = null;
	}
	
	/**
	* insert
	* 
	* insert row(s) into table, $array either:
	*    array('column' => 'content' ... )
	* or
	*    array(0 => array('val1', 'val2'...), 1 => array('val1', 'val2'...))
	*
	* The last one is for multiinsert, in case of use, $cols is a 1-dimensional array
	* containing column names
	*
	* @return insert id
	*/
	public function insert($data, $table, $cols = false){
		if(!$cols){
			$datafield = array_keys($data);
			$qmarks = array_fill(0, count($data), '?');
			$data = array_values($data);
		}
		else{
			$datafield = $cols;
			$qmarks = array_fill(0, count($cols), '?');
		}	
		
		//@TODO use PDO parameter bindings for sequrity
		//remember the `, otherwise reserved words (f.eks. key) cannot be used!
		$sql = "INSERT INTO ".$table." (`".implode("`,`", $datafield)."`)
		VALUES (".implode(',', $qmarks).");";
		$stmt = $this->dbh->prepare($sql);
		
		if(DEBUG){
			$debug = \core\debug::getInstance();
			$debug->eventByTime($sql);
		}
		
		if(!$cols){
			$stmt->execute($data);
			$err = $stmt->errorInfo();
			if($err[0] != 00000){
				trigger_error("statement failed: ".$err[2], E_USER_WARNING);
				//samthing went wront
				return false;
			}
			//we did it!
			return $this->dbh->lastInsertId();
		}
		else{
			foreach($data as $d)
				$stmt->execute($d);
			return true;
		}
	}
	
	/**
	* delete
	*
	* retrieve single row from table
	*/
	function delete($table, $where){
		
	}
	
	/**
	* update
	*
	* update a row
	* @param $data	array('col' => 'newValue', col2 => 'newValue')
	*/
	function update($data, $table, $where){
		//generate update string
		$comp = array();
		$str = false;
		foreach($data as $key => $value){
			$str ? $str .= ", $key = :$key" : $str = "$key = :$key";
			$comp[":".$key] = $value;
		}
		
		//construction of where string
		$where = $this->rewriteWhere($where);
		
		//@TODO use PDO parameter bindings for sequrity
		
		$stmt = $this->dbh->prepare("UPDATE ".$table." SET ".$str." WHERE ".$where.";");
		
		$stmt->execute($comp);
		
		return $stmt->rowCount();
	}
	
	/**
	* getRow
	*
	* retrieve single row from table. $where should be an array:
	* array('col', 'search'[, ' = ']) (as getList)
	*/
	function getRow($table, $where){
		$arr = $this->getList($table, $where, 1);
		if(isset($arr[0]))
			return $arr[0];
		return false;
	}
	
	/**
	* getList
	*
	* fetch all rows that match
	* array('col', 'search'[, ' == '])
	* or:
	* array(
	*	array( array('col0', 'search0'[, ' == '])[, 'AND'])
	*	array( array('col1', 'search1'[, ' == '])[, 'AND'])
	* )
	* the last boolean operator is stripped, the operator relates to the next
	* statement
	* 
	*
	* alternatively just a string can be given like:
	* 'col == seach'
	*
	* order:
	* array(keyCol [=> 'DESC'], keyCol2 [=> 'DESC'])
	*/
	function getList($table, $where="", $limit = false, $order = false){
		$ret = array();
		
		$where = $this->rewriteWhere($where);
		
		//create limit string
		if($limit)
			if(is_array($limit))
				$limit = "LIMIT {$limit[0]}, {$limit[1]}";
			elseif(is_numeric($limit))
				$limit = "LIMIT 0, {$limit}";
			else
				$limit = "";
		else
			$limit = "";
		
		//create order string
		if($order && is_array($order)){
			$comp = array();
			$str = false;
			foreach($order as $key => $value){
				//looks like $value == 0 if it's not set?
				if($value == 0){
					$key = $value;
					$key = "";
				}
				$str ? $str .= ", $key $value " : $str = "$key $value";
			}
			$order = "ORDER BY ".$str;
		}
		else
			$order = "";
		
		//@TODO use PDO parameter bindings for sequrity
		$sth = $this->dbh->prepare("SELECT * FROM ".$table." WHERE ".$where." {$order} {$limit};");
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	
	/**
	* Query
	*
	* only use if no other possibility is available
	*/
	public function query($statement){
		$query = $this->dbh->query($statement);
		return $query;
	}
	
	/**
	* function that rewrite array wheres to string
	*
	* array('col', 'search'[, ' == '])
	* or:
	* array(
	*	array( array('col0', 'search0'[, ' = '])[, 'AND'])
	*	array( array('col1', 'search1'[, ' = '])[, 'AND'])
	* )
	*/
	private function rewriteWhere($where){
		if(is_array($where)){
			if(is_array($where[0])){
				$newWhere = '';
				$prev=false;//the previous array, used for the boolean operator
				foreach($where as $single){
					//assume that it is a one niveau array
					
					//adding boolean operator
					if($prev){
						if(!isset($prev[3]))
							$prev[3] = "AND";
						$newWhere .= ' '.$prev[3];
					}
					
					if(!isset($single[2]))
						$single[2] = '=';
					$newWhere .= ' '.$single[0].' '.$single[2].' '.$single[1];
					
					//setting prev for next iteration
					$prev = $single;
				}
			}
			else{
				//assume that it is a one niveau array
				if(!isset($where[2]))
					$where[2] = '=';
				$newWhere = $where[0] .' '. $where[2] .' '. $where[1];
			}
			
			$where = $newWhere;
		}
		
		return $where;
	}
}

/**
* mongoDB wrapper
*/
class db_mongo{
	
	/**
	* the database object
	*/
	public $database;
	
	/**
	* configuration
	*/
	private $config;
	
	/**
	* so, if the class is used as a function, we return a instance
	* of the database, or of the collection if it is specified
	*/
	function __invoke($collection = null){
		if(!is_null($collextion))
			return $this->getCollection($collection);
		return $this->database;
	}
	
	/**
	* create connection to the database
	*/
	function __construct($config){
		//fetch information
		if(is_array($config))
			$this->config = $config['mongo'];
		elseif(is_string($config))
			$this->config = \config\config::$configs[$config]['mongo'];
		
		$connections = array();
		foreach($this->config['servers'] as $server){
			$l = "";
			if(!\is_null($server['username']))
				$l = $server['username'] . ':' . $server['password'] . '@';
			$l .= $server['host'] . (is_null($server['port']) ? '' : ':'.$server['port']);
			$connections[] = $l;
		}
		$connection_string = sprintf('mongodb://%s', implode($connections, ','));
		
		$object_mongo = new \Mongo($connection_string);
		$this->database = $object_mongo->{$this->config['dbname']};
	}
	
	public function getCollection($collection){
		return $this->database->$collection;
	}
}

?>
