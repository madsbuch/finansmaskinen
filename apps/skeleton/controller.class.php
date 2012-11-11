<?

class app_skeleton extends core_app{
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* requireLogin
	*/
	static public $grpSelector = false;
	
	/**
	* construction
	*/
	function __construct(){
		$this->page = new helper_page();
		$this->header = new helper_header();
		
		$this->html = new helper_html($this->page);
		$this->html->addTopNavItem("Dine apps", "apps", "index");
		$this->html->addTopNavItem("Dine apps", "apps", "index");
		$this->html->addTopNavItem("Dine apps", "apps", "index");
		$this->html->addTopNavItem("Dine apps", "apps", "index");
	}
	
	function index(){
		$this->output_header = $this->page->getHeader();
		$this->output_content = $this->page->getContent();
	}
	
	/**
	* Required functions
	*/
	function getOutputHeader(){
		return $this->output_header;
	}
	
	function getOutputContent(){
		return $this->output_content;
	}
}
