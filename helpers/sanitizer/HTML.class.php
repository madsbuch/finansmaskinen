<?php
/**
* sanitizes html input, so that it is valid, and does not perform
* any XSS attacks
*/

namespace helper\sanitizer;

class HTML{
	
	private $whitelistTags = array(
		'a',
		'b', 
		'i',
		'u',
		'div',
		
		'table',
		'tr',
		'td',
		
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
	);
	
	/**
	* ID should NOT be whitelisted, as it can ruin the rest of the page
	* and render it impossible to get
	*/
	private $whitelistAttr = array(
		'href',
		'class',
		
	);
	
	/**
	* if dom is set, html is considered a domelement object
	*/
	function __construct($html, $dom = null){
	
	}
	
	/**
	* return string containing sanitized html
	*/
	function getSanitizedString(){
	
	}
}

?>
