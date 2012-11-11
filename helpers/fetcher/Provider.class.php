<?php
/**
* provider abstract class.
*
* this is for information providers, where there is a possibility of setting one
* detail, and get a set based on the search.
*/
namespace helper\fetcher;

abstract class Provider{
	
	protected $model;
	
	function __construct($model){
		$this->model = $model;
	}
	
	abstract function setDetail($key, $value);
	abstract function getOne();
	abstract function getAll();
	
}

?>
