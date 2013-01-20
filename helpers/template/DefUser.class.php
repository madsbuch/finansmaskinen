<?php

namespace helper\template;

class DefUser extends \helper\template\base\HTML{
	
	protected $addJsIncludes = array(
		//language,
		'/js/language/da_DK.js',

		//framework
		'/bootstrap/js/jquery.min.js',
		'/bootstrap/js/bootstrap.js',

		//plugins
		'/js/plugins/jquery.placeholder.min.js',//IE placeholders, 
		'/js/plugins/iphone-style-checkboxes.js',
		//'/bootstrap/js/bootstrap-transition.js',
		//'/bootstrap/js/bootstrap-alert.js',
		//'/bootstrap/js/bootstrap-modal.js',
		//'/bootstrap/js/bootstrap-dropdown.js',
		//'/bootstrap/js/bootstrap-tooltip.js',
		//'/bootstrap/js/bootstrap-popover.js',
		'/js/plugins/jquery.dataTables.min.js',
		'/js/plugins/jquery.dataTables.paging.js',
		//'/bootstrap/js/bootstrap-button.js',
		'/js/libs/jquery-ui-1.8.17.custom.min.js',
		'/js/plugins/picker.jquery.js',
		'/js/plugins/jquery.pageguide.js',


		//some plugins
		'/js/plugins/jquery.sheepItPlugin-1.0.0.min.js',
		'/bootstrap/js/bootstrap-modal.js',
		'/js/plugins/bootstrap-datepicker.js',
		'/js/plugins/jquery.form.js',

		//and customization
		'/templates/finance/js/application.js',
		'/templates/finance/js/init.js',
	);
	
	protected $addCSSIncludes = array(
		'/bootstrap/css/bootstrap.css',
		'/bootstrap/css/bootstrap-responsive.css',
		'/css/plugins/iphone-style-checkboxes.css',
		'/css/plugins/jquery.pageguide.css',
		'/templates/finance/css/app.css',
	);
	
	protected $addCSS = '
		body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		}
	';
	
	function __construct(){
		parent::__construct('/defUser/finance');
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
	
	public function setPrimaryTitle($title){
		$this->dom->getElementById('primaryNavTitle')->insertBefore(
			$this->dom->createTextNode($title),
			$this->dom->getElementById('primaryNavTitle')->firstChild);
	}
	
	public function setSecondaryTitle($title){
		$this->prepareSecNav();
		$this->dom->getElementById('secondaryNavTitle')->insertBefore(
			$this->dom->createTextNode($title),
			$this->dom->getElementById('secondaryNavTitle')->firstChild);
	}
	
	public function addPrimaryNav($title, $link){
		$element = $this->dom->createElement('li');
		$a = $this->dom->createElement('a', $title);
		$a->setAttribute('href', $link);
		$element->appendChild($a);
		$this->dom->getElementById('primaryNav')->appendChild($element);
	}

	/**
	 * adds a secondary nav element to the output html
	 *
	 * @param $title
	 * @param $link
	 */
	public function addSecondaryNav($title, $link){
		$this->prepareSecNav();
		$element = $this->dom->createElement('li');
		$a = $this->dom->createElement('a', $title);
		$a->setAttribute('href', $link);
		$element->appendChild($a);
		$this->dom->getElementById('secondaryNav')->appendChild($element);
	}
	
	public function setCompany($title, $link){
		$t = new \DOMText($title);
		$a = $this->dom->createElement('a');
		$a->appendChild($t);
		$a->setAttribute('href', $link);
		
		$this->dom->getElementById('companyItem')->appendChild($a);
	}
	
	public function addCompanyList($title, $link){
		$element = $this->dom->createElement('li');

		$t = new \DOMText($title);
		$a = $this->dom->createElement('a');
		$a->appendChild($t);

		$a->setAttribute('href', $link);
		$element->appendChild($a);
		
		$this->dom->getElementById('otherTrees')->insertBefore(
			$element,
			$this->dom->getElementById('otherTreeBefore'));
	}

	private function prepareSecNav(){
		//if not elements, we don't wan't the holder to be there
		if(!$this->dom->getElementById('secondaryNav')){
			//add the stuff here

			$navEleA = $this->dom->createElement('a');
			$navEleA->setAttribute('class', 'dropdown-toggle');
			$navEleA->setAttribute('id', 'secondaryNavTitle');
			$navEleA->setAttribute('data-toggle', 'dropdown');
			$navEleA->setAttribute('href', '#');
			$c = $this->dom->createElement('b');
			$c->setAttribute('class', 'caret');
			$navEleA->appendChild($c);

			$navEleB = $this->dom->createElement('ul');
			$navEleB->setAttribute('id', 'secondaryNav');
			$navEleB->setAttribute('class', 'dropdown-menu');

			$this->dom->getElementById('secNavHolder')->appendChild($navEleA);
			$this->dom->getElementById('secNavHolder')->appendChild($navEleB);
		}
	}
}
