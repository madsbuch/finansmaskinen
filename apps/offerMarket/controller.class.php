<?
/**
* Company contacts
*
* yeah, this is company contacts, personal contacts have to have another name :S
*/
namespace app;
class offerMarket extends \core\app{
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* requireLogin
	*/
	static public $grpSelector = false;
	
	/**
	* note, address book
	*
	* holder for helper_lodo object
	*/
	private $lodo;
	
	/**
	* construction
	*/
	function __construct(){
		$this->header = new \helper\header();
	}
	
	function index(){
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Regnskabsbørs',
			'Hjælp til dine problemer, lige her'));
		
		$this->output_header = $this->header->getHeader();
		$this->output_content =$html->generate();
	}
	
	
	function add(){

	}
	
	/**
	* view details for one entry
	*/
	function view(){

	}
	
	function setup($done=false){
		if($done){
			$this->getSiteAPI()->finishSetup('offerMarket');
			$this->header->redirect('/index');
		}
	
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Revisorbørsen', 
			'En lille introduktion'));
			
		$html->appendContent(new \app\offerMarket\layout\finance\Setup());
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/***************************** AJAX FUNCTION ******************************/
	

	function create(){}

	
	/**** private functions ****/
	private function getTpl(){
		$html = $this->getSiteAPI()->getTemplate();
		
		$html->setSecondaryTitle(__('Offer market'));
		$html->addSecondaryNav(__('All offers'), '/contacts');
		$html->addSecondaryNav(__('Your bids'), '/contacts/add');
		
		return $html;
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
