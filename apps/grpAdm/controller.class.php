<?
namespace app;
class grpAdm{
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
		$this->html->addTopNavItem("Dine Værktøjer", "apps", "index");
		$this->html->addTopNavItem("Fakturaer", "index", "invoice");
		$this->html->addTopNavItem("Klienter", "index", "clients");
		$this->html->addTopNavItem("Regnskab", "index", "accounting");
	}
	
	function index(){
		$this->html->setTitle("Administrer grupper");
		
		$core = new helper_core();
		
		$grps = $core->getGrp();
		foreach($grps as $grp){
			$toRet[] = array('tag' => 'li', 'attr' => array(), 'content' => $grp);
		}
		
		$toRet['tag'] = 'div';
		$toRet['attr'] = array();
		
		$this->html->add2content($toRet);
		
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
