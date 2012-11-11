<?
namespace app;
class economySupport extends \core\app{
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
		$this->header = new \helper\header();
	}
	
	function index(){
		$html = $this->getOutTpl();
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	function newTicket(){
		$html = $this->getOutTpl();
		$this->output_header = $this->header->getHeader();
		$this->output_content = $$html->generate();
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
	
	/**** Private functions ****/
	private function getOutTpl(){
		$tpl = $this->getSiteAPI()->getTemplate();
		$tpl->setSecondaryTitle(__('Support'));
		$tpl->addSecondaryNav(__('List tickets'), '/economySupport');
		$tpl->addSecondaryNav(__('Create mew ticket'), '/economySupport');
		return $tpl;
	}
}
