<?php

namespace helper\fetcher\ERProviders;

abstract class Rate{
	
	/**
	* fetches xml and returns its dom
	*/
	function fetchXML($source){
		return file_get_contents($source);
	}
	
	abstract function getRate($from, $to, $date);
	abstract function convert($from, $to, $amount, $date);
	
}

?>
