<?php

namespace helper\template;

class Def extends \helper\template\base\HTML{
	
	/**
	* doing the js and css from here, makes sure, that proper domains are used
	* so
	*/
	protected $addJsIncludes = array(
		'/bootstrap/js/jquery.js',
		'/js/plugins/jquery.placeholder.min.js',//IE placeholders, 
		'/bootstrap/js/bootstrap.min.js',
		//'/bootstrap/js/bootstrap-alert.js',
		//'/bootstrap/js/bootstrap-modal.js',
		//'/bootstrap/js/bootstrap-dropdown.js',
		//'/bootstrap/js/bootstrap-scrollspy.js',
		//'/bootstrap/js/bootstrap-tab.js',
		//'/bootstrap/js/bootstrap-tooltip.js',
		//'/bootstrap/js/bootstrap-popover.js',
		//'/bootstrap/js/bootstrap-button.js',
		//'/bootstrap/js/bootstrap-collapse.js',
		//'/bootstrap/js/bootstrap-carousel.js',
		//'/bootstrap/js/bootstrap-typeahead.js',
		'/bootstrap/js/application.js',
		'/bootstrap/js/init.js'
	);
	
	protected $addCSSIncludes = array(
		'/bootstrap/css/bootstrap.css',
		'/bootstrap/css/bootstrap-responsive.css',
		'/bootstrap/css/app.css',
	);
	
	protected $addCSS = '
		body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		}
	';

	function __construct(){
		parent::__construct('/def/finance');
	}
	
	
	/**
	* deprecated these functions are for backwards combatability
	*/
	public function add2content($data){
		return $this->appendContent($data);
	}
	public function parseHTML($data){
		return $this->appendContent($data);
	}
	public function setTitle($t){}
		
	/**** Manipulate top navigation menu items ****/
	public function addNav($title, $link, $active=false){
		$element = $this->dom->createElement('li');
		
		if($active)
			$element->setAttribute('class', 'active');
		
		$a = $this->dom->createElement('a', $title);
		$a->setAttribute('href', $link);
		$element->appendChild($a);
		$this->dom->getElementById('topNav')->appendChild($element);
	}
	
	/**
	* sets a message to the user
	*/
	public function setMsg($iterator){
		if(!$iterator)
			return;
			
		$content = $this->dom->getElementById('content');
		
		foreach($iterator as $msg){
			//var_dump($msg);
			if(is_object($msg))
				$msg = $msg->generate();
			
			$msg = \helper\html::importNode($this->dom, $msg);
			
			$content->insertBefore($msg, $content->firstChild);
		}
	}
}
