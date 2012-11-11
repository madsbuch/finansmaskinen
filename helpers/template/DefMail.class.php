<?php
/**
* templateting object for mails
*/

namespace helper\template;

class DefMail extends \helper\template\base\Mail{
	
	protected $addJsIncludes;
	
	protected $addCSSIncludes;
	
	protected $addCSS;
	
	function __construct(){
		parent::__construct('/defMail/finance');
	}
}
