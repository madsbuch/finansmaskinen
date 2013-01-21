<?php

namespace helper\template;

class Def extends \helper\template\base\HTML{
	
	/**
	* doing the js and css from here, makes sure, that proper domains are used
	* so
	*/
    protected $addJsIncludes = array(
        //language,
        '/js/language/da_DK.js',

        //framework
        '/js/libs/jquery-1.7.2.min.js',
        '/bootstrap/js/bootstrap.js',

        //plugins
        '/js/plugins/jquery.placeholder.min.js',//IE placeholders,
        '/js/plugins/iphone-style-checkboxes.js',

        //those are removed when refactoring to new bootstrap is done
        //'/bootstrap/js/bootstrap-transition.js',
        //'/bootstrap/js/bootstrap-alert.js',
        //'/bootstrap/js/bootstrap-modal.js',
        //'/bootstrap/js/bootstrap-dropdown.js',
        //'/bootstrap/js/bootstrap-tooltip.js',
        //'/bootstrap/js/bootstrap-popover.js',
        //'/bootstrap/js/bootstrap-button.js',

        //some plugins
        '/js/plugins/jquery.dataTables.min.js',
        '/js/plugins/jquery.dataTables.paging.js',
        '/js/libs/jquery-ui-1.10.0.custom.min.js',
        '/js/plugins/picker.jquery.js',
        '/js/plugins/jquery.pageguide.js',
        '/js/plugins/jquery.sheepItPlugin-1.0.0.min.js',
        '/js/plugins/bootstrap-datepicker.js',
        '/js/plugins/jquery.form.js',

        //and customization
        '/templates/finance/js/application.js',
        '/templates/finance/js/init.js',
    );

	protected $addCSSIncludes = array(
		'/bootstrap/css/bootstrap.min.css',
		'/templates/finance/css/app.css',
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
