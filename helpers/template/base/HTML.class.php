<?php
/**
* std html template
*/

namespace helper\template\base;
abstract class HTML{		
	protected $addJsIncludes = array();
	protected $addJs;
	
	protected $addCSSIncludes = array();
	protected $addCSS;
	
	protected $tutorialSlides = array();
	
	protected $dom;
	
	/**
	* magic methods
	*/
	function __construct($tpl = '/defUser/finance'){
		//initialise DOM
		$this->dom = new \DOMDocument();
		$this->dom->loadHTML(file_get_contents(__DIR__.'/..'.$tpl.'.html'));
	}
	
	/**
	* add content
	*/
	public function appendContent($data){
		$content = $data;
		//check if it is a layoutobject, and respond to that
		if(is_object($data) && \is_subclass_of($data, 'helper\layout\LayoutBlock')){
			//do the generation first, as it may alter fieldvars of the object
			$content = $data->generate();
			
			//additional javascript includes
			$this->addJsIncludes = array_merge($this->addJsIncludes,
				$data->addJsIncludes);
			
			//additional javascript includes
			$this->addCSSIncludes = array_merge($this->addCSSIncludes,
				$data->addCSSIncludes);
			
			//additional javascript string
			$this->addJs .= $data->addJs;
			
			//additional javascript string
			$this->addCSS .= $data->addCSS;
			
			//merge in the tutorial slides
			$this->tutorialSlides = array_merge($this->tutorialSlides, $data->tutorialSlides);
		}
		$this->dom->getElementById('content')->appendChild(
			\helper\html::importNode($this->dom, $content));
	}

	
	/**
	* generate html for output
	*
	* NOTE! this only reads the first element of the input (recursive though)
	*/
	public function generate(){
		//add all the javascript stuff
		$body = $this->dom->getElementsByTagName('body')->item(0);
		$head = $this->dom->getElementsByTagName('head')->item(0);
		
				//add evt tutorial
		if(!empty($this->tutorialSlides)){
			$theUl = $this->dom->createElement('ul');
			$theUl->setAttribute('id', 'tlyPageGuide');
			$theUl->setAttribute('class', 'hide');
			$theUl->setAttribute('data-tourtitle', __('Need help for this page?'));
			
			//making some nicer position flow
			$pos = array('left', 'right', 'top', 'bottom');
			$i = 0;
			
			foreach($this->tutorialSlides as $selector => $desc){
				$theLi = $this->dom->createElement('li');
				
				$p = $pos[$i];
				
				if(is_array($desc)){
					$p = $desc[1];
					$desc = $desc[0];
				}
					
				
				$theLi->setAttribute('class', 'tlypageguide_'.$p);
				$theLi->setAttribute('data-tourtarget', $selector);
				$theLi->appendChild(new \DOMElement('div', $desc));
				$theUl->appendChild($theLi);
				$i = $i % 3 == 0 && $i > 0 ? 0 : $i+1;
			}
			$body->appendChild($theUl);
		}
		
		//make elements unique
		$this->addJsIncludes = array_unique($this->addJsIncludes);
		$this->addCSSIncludes = array_unique($this->addCSSIncludes);
		
		//add custom css
		if(!is_null($this->addCSS)){
			$ele = $this->dom->createElement('style', $this->addCSS);
			$head->appendChild($ele);
		}
		
		//do some internal settings parsing
		$ele = $this->dom->createElement('script', '
			var commaSeparator = \''.\core\localization::$commaSeparator.'\';
			var thousandsSeparator = \''.\core\localization::$thousandsSeparator.'\';
		');
		$body->appendChild($ele);
		
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

			$prepend = \config\config::$protocol.'://'.\config\config::$domain['static'];
			if(substr($incl, 0, 4) == 'http'){
				$prepend = '';
			}

			$ele->setAttribute('href', $prepend
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
