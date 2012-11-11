<?php

namespace helper\parser;

class NaturalLanguage{
	/**
	* words the string is exploded by
	*/
	private $words = array(
		'med',
		'på',
		'for',
		'ved',
		'til',
		
		'opret',
		'send',
		'ny',
		'slet',
		'se',
		'opdater',
	);
	
	private $exclude = array(
		'en',
		'et'
	);
	
	private $objects = array(
		'contacts' => array(
			'kontakt',
			'kontaker'
		)
	);
	
	private $mappings = array();
	
	private $str;
	
	public function __construct($str){
		$this->str = $str;
	}
	
	/**
	* adds a recognizer object
	*/
	function addRecognizer($r){
	
	}
	
	function parse(){
		$arr = explode(' ',  $this->str);
		
		$res = array();
		$pointer = &$res;
		
		foreach($arr as $w){
			if(in_array($w, $this->words)){
				$res[$w] = '';
				$pointer = &$res[$w];
			}
			else
				$pointer .= ' '.$w;
		}
		
		return $this->map($res);
		
	}
	
	public function mapping($key, $newKey){
		$this->mappings[] = array($key, $newKey);
	}
	
	private function map($arr){
		foreach($this->mappings as $mapping){
			if(isset($arr[$mapping[0]]))
				$arr[$mapping[1]] = $arr[$mapping[0]];
		}
		return $arr;
	}
}

?>

