<?php
namespace core;

/**
 * this class should be rethought, everything is "return"'ed out
 */
class debug{
	/*********** FOR SINGLETON ***********/
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance(){	
		if (!is_object(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone(){
	  trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	/********* THE CLASS ***************/
	
	private function __construct(){
		return;
		if(!DEBUG)//check if we are debugging
			return;
		
		//start timer
		$this->timer = microtime(true);
		
		//set error reporting
		error_reporting(-1);
		
		//open log
		$this->log = new \core\file(TMPDIR."lastrun.log");
		
		$ip = \core\inputParser::getInstance();
		
		$this->log->append("\nDomain: ".$ip->getDomain());
		$this->log->append("\nURI: ".$ip->getURI()."\n");
	}
	
	function __destruct(){
		return;
		if(!DEBUG)// check if we are debugging
			return;
		//print things to file
		
		$this->log->append("\nEvents:");
		if(isset($this->events) && is_array($this->events))
			foreach($this->events as $time => $event)
				$this->log->append("\n".$time.' '.$event);
		$this->log->append("\n\n");
		
		$now = microtime(true);
		$time = (string)($now - $this->timer);
		
		$this->log->append("\nStatistics:\n");
		$this->log->append("Total execution time: $time");
		if(isset($this->stats) && is_array($this->stats))
			foreach($this->stats as $time => $event)
				$this->log->append("\n".$event.': '.$time);
	}
	
	public function classLoad($class){
		return;
		//if(!DEBUG)// check if we are debugging
		//	return;
		//$this->classes[] = $class;
	}
	
	public function eventByTime($event){
		return;
		if(!DEBUG)// check if we are debugging
			return;
		
		//calculate the time
		$now = microtime(true);
		$time = (string)($now - $this->timer);
		
		//note the event to the time
		$this->events[$time] = $event;
	}
	
	public function startTimer($id){
		return;
		$this->ctimer[$id] = microtime(true);
	}
	
	public function add2statistics($id){
		return;
		//calculate the time
		$now = microtime(true);
		$time = (string)($now - $this->ctimer[$id]);
		
		//note the event to the time
		$this->stats[$time] = $id;
	}
}
?>
