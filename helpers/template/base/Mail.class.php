<?php
/**
* std html template
*/

namespace helper\template\base;
abstract class Mail{		
	
	protected $dom;
	
	protected $subject = 'No subject';
	
	/**
	* magic methods
	*/
	function __construct($tpl = '/defMail/finance'){
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
			if(isset($data->subject))
				$this->subject = $data->subject;
		}
		$this->dom->getElementsByTagName('body')->item(0)->appendChild(
			\helper\html::importNode($this->dom, $content));
	}

	
	/**
	* generate html for output
	*/
	public function generate(){
		return $this->dom->saveHTML();
	}
	
	/**
	* generates text only alternative
	*/
	public function generateAlt(){
		return "";
	}
	
	/**
	* returns the mail subject
	*/
	public function generateSubject(){
		return $this->subject;
	}
	
	
}

?>
