<?php
/**
* this works as a abstract class for templates
*
* DEPRECATED!!! take a look in the base folder
*/


namespace helper;
abstract class template{		
	protected $addJsIncludes = array();
	protected $addJs;
	
	protected $addCSSIncludes = array();
	protected $addCSS;
	
	protected $dom;
	
	/**
	* magic methods
	*/
	function __construct($tpl = '/defUser/finance'){
		//initialise DOM
		$this->dom = new \DOMDocument();
		$this->dom->loadHTML(file_get_contents(__DIR__.$tpl.'.html'));
	}

	
	/**
	* generate html for output
	*/
	public function generate(){
		//add all the javascript stuff
		$body = $this->dom->getElementsByTagName('body')->item(0);
		$head = $this->dom->getElementsByTagName('head')->item(0);
		
		//make elements unique
		$this->addJsIncludes = array_unique($this->addJsIncludes);
		$this->addCSSIncludes = array_unique($this->addCSSIncludes);
		
		//add custom css
		if(!is_null($this->addCSS)){
			$ele = $this->dom->createElement('style', $this->addCSS);
			$head->appendChild($ele);
		}
		
		//add js includes
		foreach($this->addJsIncludes as $incl){
			//@TODO some site awareness
			$ele = $this->dom->createElement('script');
			
			$ele->setAttribute('src', \config\config::$protocol
				.'://'
				.\config\config::$domain['static']
				.$incl);
			$body->appendChild($ele);
		}
		
		//and css includes
		foreach($this->addCSSIncludes as $incl){
			//@TODO some site awareness
			$ele = $this->dom->createElement('link');
			
			$ele->setAttribute('href', \config\config::$protocol.'://'
				.\config\config::$domain['static']
				.$incl);
			$ele->setAttribute('rel', 'stylesheet');
			
			$head->appendChild($ele);
		}
		
		//and the js string
		if(!is_null($this->addJs)){
			$ele = $this->dom->createElement('script', $this->addJs);
			$body->appendChild($ele);
		}
		
		
		return $this->dom->saveHTML();
	}
	
	
}

?>
