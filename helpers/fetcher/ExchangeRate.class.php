<?php

namespace helper\fetcher;

class ExchangeRate {
	
	/**
	* return in string. higher precisision
	*/
	public $string = false;
	
	/**
	* array of objects providing ER service
	*/
	private $ERProviders = array();
	
	/**
	* constructor
	*/
	function __construct(){
		//we add the european central bank as provider
		$this->ERProviders[] = new ERProviders\ECB();
	}
	
	/**
	* this will return some rate from to
	*
	* this function returns the multiplication factor needed to get from some
	* rate in from, to some rate in to
	*/
	function getRate($from, $to, $timestamp=null){
		//forwarding the request to the first object that responds proper
		foreach($this->ERProviders as $p){
			if(($t = $p->getRate($from, $to, $timestamp))){//yeah, the single = is correct ;)
				return $t;
			}
		}
		return false;
	}
	
	/**
	* returns conversion
	*/
	function convert($from, $to, $amount, $timestamp=null){
		//forwarding the request to the first object that responds proper
		foreach($this->ERProviders as $p)
			if(($t = $p->convert($from, $to, $amount, $timestamp)));//yeah, the single = is correct ;)
				return $t;
		return false;
	}
	
}
