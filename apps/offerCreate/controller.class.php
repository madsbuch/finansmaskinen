<?
/**
* Company contacts
*
* yeah, this is company contacts, personal contacts have to have another name :S
*/
namespace app;
class offerCreate extends \core\app{
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
		$html->appendContent(\helper\layout\Element::heading('Regnskabshjælp',
			'Kommer snart!'));

		//TODO kommenter ind når denne er klar
		//$html->appendContent(new offerCreate\layout\finance\Form());
		
		$this->output_header = $this->header->getHeader();
		$this->output_content =$html->generate();
	}
	
	/**
	* view details for one entry
	*/
	function view(){
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Revisorbørs',
			'Dine opgaver.'));
		
		$offers = \api\offerCreate::retrive();
		
		$html->appendContent(new offerCreate\layout\finance\Listing($offers));
		
		$this->output_header = $this->header->getHeader();
		$this->output_content =$html->generate();
	}
	
	function details($id = null){
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Revisorbørs',
			'Detaljer om opgave.'));
		
		$offer = \api\offerCreate::getOne($id);
		
		$html->appendContent(new offerCreate\layout\finance\View($offer));
		
		$this->output_header = $this->header->getHeader();
		$this->output_content =$html->generate();
	}
	
	function setup($done=false){
		if($done){
			$this->getSiteAPI()->finishSetup('offerCreate');
			$this->header->redirect('/index');
		}
	
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Revisorbørsen', 
			'En lille introduktion'));
			
		$html->appendContent(new \app\offerCreate\layout\finance\Setup());
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/***************************** AJAX FUNCTION ******************************/
	

	function create(){
		$input = new \helper\parser\Post('\model\finance\Offer');
		$obj=$input->getObj();

		if($obj){
			//insertion of new offer
			$obj = \api\offerCreate::create($obj);
			$this->header->redirect('/offerCreate/details/' . (string) $obj->_id);
		}
		else{
			$this->header->redirect('/offerCreate/');
		}
		$this->output_header = $this->header->generate();
		$this->output_content = '';
	}

	
	/**** private functions ****/
	private function getTpl(){
		$html = $this->getSiteAPI()->getTemplate();

		/* TODO kommentar ind når klar
		$html->setSecondaryTitle('Regnskabshjælp');
		$html->addSecondaryNav(__('Create job'), '/offerCreate');
		$html->addSecondaryNav(__('Your jobs'), '/offerCreate/view');
		*/

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
