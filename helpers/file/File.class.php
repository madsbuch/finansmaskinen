<?php
/**
* a wrapper for file, making it transparent to use local files and mongo files
*/

namespace helper\file;

class File{
	
	const MONGO = 1;
	const FILE = 2;
	
	//holder for resource
	private $r;
	private $t;//the type
	
	/**
	* return int size of file
	*/
	function getSize(){
	
	}
	
	/**
	* returns the full file
	*/
	function getFile(){
		if($this->t === self::MONGO)
			return $this->r->getBytes();
	}
	
	/**
	* desireable, returns a filestream usable in all php contexts (for files)
	*/
	function getStream(){
		if($this->t === self::MONGO)
			return $this->r->getResource();
	}
	
	/**
	* construct
	*/
	function __construct($resource, $type){
		$this->t = $type;
		$this->r = $resource;
	}
	
}

?>
