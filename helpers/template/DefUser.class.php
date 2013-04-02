<?php

namespace helper\template;

class DefUser extends \helper\template\base\HTML{
	
	protected $addJsIncludes = array(
		//language,
		'/js/language/da_DK.js',

		//framework
        '/js/libs/jquery-1.7.2.min.js',
		'/bootstrap/js/bootstrap.js',

		//plugins
		'/js/plugins/jquery.placeholder.min.js',//IE placeholders, 
		'/js/plugins/iphone-style-checkboxes.js',

        //some plugins
		'/js/libs/jquery-ui-1.10.0.custom.min.js',

		'/js/plugins/jquery.dataTables.min.js',
		'/js/plugins/jquery.dataTables.paging.js',
		'/js/plugins/picker.jquery.js',
		'/js/plugins/jquery.pageguide.js',
		'/js/plugins/jquery.sheepItPlugin-1.0.0.min.js',
		'/js/plugins/bootstrap-datepicker.js',
		'/js/plugins/iButton/jquery.ibutton.min.js',
		'/js/plugins/jquery.form.js',
		'/js/plugins/bootstrap-datepicker.js',
		'/js/plugins/jquery.metadata.js',

		//and customization
		'/templates/finance/js/application.js',
		'/templates/finance/js/init.js',

		'/templates/finance/framework/theme/scripts/less-1.3.3.min.js'
	);
	
	protected $addCSSIncludes = array(
		'/templates/finance/framework/bootstrap/css/bootstrap.min.css',

		//some framework
		'/templates/finance/framework/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css',
		'/templates/finance/framework/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css',
		'/templates/finance/framework/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css',

		'/templates/finance/framework/theme/scripts/select2/select2.css',

		'/templates/finance/framework/theme/css/style.min.css',

		'/css/plugins/jquery.pageguide.css',
		'/css/plugins/jquery.ibutton.min.css',
		'/css/plugins/bootstrap-datepicker.css',
		'/templates/finance/css/app.css',
	);
	
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


	public function setCompany($title, $link, $freeTickets = null, $buyLink = null){
		$t = new \DOMText($title);
		$a = $this->dom->createElement('a');
		$a->appendChild($t);
		$a->setAttribute('href', $link);
		
		$this->dom->getElementById('companyItem')->appendChild($a);

		if(!is_null($freeTickets)){
			$t = new \DOMText(__('( %s )', $freeTickets));
			$a = $this->dom->createElement('a');
			$a->appendChild($t);
			$a->setAttribute('href', $buyLink);
			$a->setAttribute('title', __('Buy credit'));

			$this->dom->getElementById('companyItemCredit')->appendChild($a);
		}
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
