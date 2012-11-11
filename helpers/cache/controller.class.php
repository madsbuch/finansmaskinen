<?php
/**
* caching
*
* yeah, we use dependency injection here ;)
*/

namespace helper;

include 'Cache.interface.php';

class cache{
	
	private $cacher;
	
	function __construct($cacher){
		$this->cacher = $cacher;
	}
	
	/**
	* an easier way of creating this object. No need no create the custom cacher
	* object all the time
	*
	* @param $cacher the type of cacher used
	* @param $collection collection used by the cacher
	*/
	static function getInstance($cacher, $collection){
		$cacher = '\helper\cache\\' . $cacher;
		$cacher = new $cacher($collection);
		return new cache($cacher);
	}
	
	function exists($key){
		return $this->cacher->offsetExists($key);
	}
	
	/**
	* gets a cache entry, or null if none
	*/
	public function get($key){
		return $this->cacher->dataGet($key);
	}
	
	/**
	* sets new entry, and possible expiry
	*
	* remember, this is NOT storage, there it is not for sure, that the data is 
	* in here.
	*/
	public function set($key, $contents, $expiry = -1){
		return $this->cacher->dataSet($key, $contents, $expiry);
	}

	/**
	* run this through some cron somewhere
	*/
	function gc(){
	
	}
}


?>
