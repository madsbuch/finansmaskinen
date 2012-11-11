<?php
/**
* take a object and a html template, and substitutes some fields
*
* substitution is done by following methods:
* ${} for a language specific variable
* #{} for a object reference (in dot syntax: arr.arr1.arr2.last)
*
* depends: helper_html, \core\inputParser
*/

namespace helper\layout;


class ObjectView extends LayoutBlock{

	function __construct($tpl, $obj){
	
	}
	
	function generate(){
	
	}
	
	private function replace($str){
	
	}
}
?>
