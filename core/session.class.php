<?php
/**
* Session handling
*
* look, this is neat :D
* you just get the instance (\core\session::getInstance)
* set a variable ($sessionInstance->somevar = hej;)
* and that var is persistant throug all the session!
*/
namespace core;
class session{
	/**
	* initialize session handler
	*/
	public static function initialize(){
		new session_MongoSession(\config\config::$sessionConfig);
	}
	
	/*********** FOR SINGLETON ***********/
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance(){
		if (!isset(self::$instance)){
			//session persistance
			if(isset($_SESSION['__sessionObject'])){
				self::$instance = \unserialize($_SESSION['__sessionObject']);
			}
			else{
				$c = __CLASS__;
				self::$instance = new $c;
				//prevent circular reference by calling statistic from here
				//and not the constructor
				\core\logHandler::statistic(\core\logHandler::SESSION);
			}
		}
		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone(){
	  trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	// A private constructor; prevents direct creation of object
	private function __construct() {
	}
	function __destruct(){
		$_SESSION['__sessionObject'] = \serialize($this);
	}
	
	/********* THE CLASS ***************/
	
	/**
	* returns session id
	*/
	public function getSid(){
		return session_id();
	}
	
	private function startSession(){
	
	}
}

/*
down from here the actual handler is
*/


/**
 * File contains MongodbSession handler class
 *
 * @author Pritesh Loke <priteshloke@gmail.com>
 * @version 1.0
 */

/**
 * This is the MongoDb session handler class
 *
 * @package px
 * @category API
 */
class session_MongoSession
{
	private $conf = array(
		    // session related vars
		    'max_lock_time'	=> 60,			//if something goes wrong with the server
		    								//a user should not hang all the lifetime
		    'lifetime'      => 3600,        // session lifetime in seconds
		    'database'      => null,   // name of MongoDB database
		    'collection'    => 'session',   // name of MongoDB collection
			
			/*
				not yet implemented
			
			// persistent related vars
			'persistent' 	=> true, 			// persistent connection to DB?
		    'persistentId' 	=> 'MongoSession', 	// name of persistent connection
		
			// whether we're supporting replicaSet
			'replicaSet'		=> null,
			
			*/
			
			// array of mongo db servers
		    'servers'   	=> array(
		        array(
		            'host'          => '127.0.0.1',
		            'port'          => null,
		            'username'      => null,
		            'password'      => null
		        )
		    )
		);
	
	private $__mongo_collection = NULL;
	private $__current_session = NULL;
	/**
	 *
	 * Default constructor set default parameter
	 * @access public
	 */
	public function __construct($config = null)
	{
		if(!is_null($config))
			$this->conf = $config;
		$this->__connect();
		session_set_save_handler(
		array(&$this, 'open'),
		array(&$this, 'close'),
		array(&$this, 'read'),
		array(&$this, 'write'),
		array(&$this, 'destroy'),
		array(&$this, 'gc')
		);
		session_start();
	}
	/**
	 *
	 * connectes the mongo database and create collection
	 * @access private
	 */
	private function __connect()
	{
		$connections = array();
		
		foreach($this->conf['servers'] as $server){
			$l = "";
			if(!\is_null($server['username']))
				$l = $server['username'] . ':' . $server['password'] . '@';
			$l .= $server['host'] . (is_null($server['port']) ? '' : ':'.$server['port']);
			$connections[] = $l;
		}
		$connection_string = sprintf('mongodb://%s', implode($connections, ','));
		
		$object_mongo = new \Mongo($connection_string);
		$object_mongo = $object_mongo->{$this->conf['database']};
		$this->__mongo_collection = $object_mongo->{$this->conf['collection']};
	}
	/**
	 * 
	 * check for collection object
	 * @access public
	 * @param string $session_path
	 * @param string $session_name
	 * @return boolean
	 */
	public function open($session_path, $session_name)
	{
		$result = false;
		if ($this->__mongo_collection != NULL)
		{
			$result = false;
		}
		return $result;
	}
	/**
	 * 
	 * doing noting
	 * @access public
	 * @return boolean
	 */
	public function close()
	{
		return true;
	}
	/**
	 * 
	 * Reading session data based on id
	 * @access public
	 * @param string $session_id
	 * @return mixed 
	 */
	public function read($session_id)
	{
		/*wait for lock*/
		$this->_lock($session_id);
		
		$result = NULL;
		$query['session_id'] = $session_id;
		$query['expiry'] = array('$gte' => time());
		$result = $this->__mongo_collection->findone($query);
		if ($result)
		{
			$this->__current_session = $result;
		}
		return $result['data'];
	}
	
	/**
	* lock the session for data loss prevention
	*
	* the session is locked for a maximum of 
	*/
	private function _lock($id)
	{
		$remaining = 30000000;
		$timeout = 5000;
		
		//first, we check if the document even excists:
		$res = $this->__mongo_collection->findOne(array('session_id' => $id));
		if(is_null($res)){
		
			$data = array(
				'session_id' => $id,
				'locked_to' => time()+$this->conf['max_lock_time'],
				'data' => "",
				'expiry' => $this->__getExpriry());
				
			$this->__mongo_collection->insert($data);
			return true;
		}
		
        do {
			
            try {
                $query = array('session_id' => $id, 'locked_to' => array('$lt' => time()));
                $update = array('$set' => array('locked_to' => time()+$this->conf['max_lock_time']));
                $options = array('safe' => true, 'upsert' => true);
                $result = $this->__mongo_collection->update($query, $update, $options);
                
                //if lock is optained (updated succeded), we return true
                if ($result['ok'] == 1) {
                    return true;
                }
            } catch (MongoCursorException $e) {
                if (substr($e->getMessage(), 0, 26) != 'E11000 duplicate key error') {
                    throw $e; // not a dup key?
                }
            }

			// force delay in microseconds
            usleep($timeout);
            $remaining -= $timeout;

            // backoff on timeout, save a tree. max wait 1 second
            $timeout = ($timeout < 1000000) ? $timeout * 2 : 1000000;

        } while ($remaining > 0);

        // aww shit.
        throw new \Exception('Could not obtain a session lock.');
	}
	
	
	
	/**
	 * 
	 * Writing session data
	 * @access public
	 * @param string $session_id
	 * @param mixed $data
	 * @return boolean
	 */
	public function write($session_id, $data)
	{
		$result = true;
		$expiry = $this->__getExpriry();
		$session_data = array();
		if (empty($this->__current_session))
		{
			$session_data['session_id'] = $session_id;
			$session_data['data'] = $data;
			$session_data['expiry'] = $expiry;
			$session_data['locked_to'] = 0;
		}
		else
		{
			$session_data = (array) $this->__current_session;
			$session_data['data'] = $data;
			$session_data['expiry'] = $expiry;
			$session_data['locked_to'] = 0;
		}
		$query['session_id'] = $session_id;
		$record = $this->__mongo_collection->findOne($query);
		if ($record == null)
		{
			$this->__mongo_collection->insert($session_data);
		}
		else
		{
			$record['data'] = $data;
			$record['expiry'] = $expiry;
			$record['locked_to'] = 0;
			$this->__mongo_collection->save($record);
		}
		return true;
	}
	/**
	 * 
	 * remove session data
	 * @access public
	 * @param string $session_id
	 * @return boolean
	 */
	public function destroy($session_id)
	{
		$query['session_id'] = $session_id;
		$this->__mongo_collection->remove($query);
		return true;
	}
	/**
	 * 
	 * Garbage collection
	 * @access public
	 * @return boolean
	 */
	public function gc()
	{
		$query = array();
		$query['expiry'] = array('$lt' => time());
		
		$this->__mongo_collection->remove($query, array('justOne' => false));
		return true;
	}
	/**
	 * 
	 * get expiry
	 * @access private
	 * @return int
	 */
	private function __getExpriry()
	{
		return time() + $this->conf['lifetime'];
	}
}
?>
