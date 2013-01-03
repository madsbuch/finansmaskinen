<?
/**
* Company contacts
*
* yeah, this is company contacts, personal contacts have to have another name :S
*/
namespace app;
class contacts extends \core\app{
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* note, address book
	*
	* holder for helper_lodo object
	*/
	private $lodo;
	
	/**
	* construction
	*/
	function __construct($r){
		$this->header = new \helper\header();
		parent::__construct($r);
	}
	
	function index(){
		$this->entries();
	}
	
	
	function add(){
		$html = $this->getTpl();
		
		$html->appendContent('
	<header class="jumbotron subhead" id="overview">
		<h1>Kontakter</h1>
		<p class="lead">Opret ny</p>
	</header>');
		
		//@TODO awareness of profile
		//doing output
		$form = new contacts\layout\finance\Form();
		$html->add2content($form);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/**
	* list all entries
	*/
	function entries(){
		$html = $this->getTpl();
		
		$html->appendContent(\helper\layout\Element::heading('Kontakter',
			'Alle kontaker'));
		
		//create the addlink
		$html->appendContent(\helper\layout\Element::primaryButton(
			'/contacts/add', 
			'<i class="icon-plus" /> '.__('Create Contact')));

		//@TODO awareness of profile
		$list = new contacts\layout\finance\Listing();
		
		$html->appendContent($list);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/**
	* view details for one entry
	*/
	function view($id = null){
		$core = new \helper\core('contacts');
		$obj = \api\contacts::getContact($id);
		
		$html = $this->getTpl();
		
		//was there any result?
		if(!$obj){
			$html->appendContent(\helper\layout\Element::heading('Kontakter',
			'Detaljer for kontakter'));
			$html->add2content('<div class="alert alert-info">'.__('Contact doesn\'t exist').'</div>');
		}
		else{
			$html->appendContent(\helper\layout\Element::heading('Kontakter',
				'Detaljer for ' . (isset($obj->Party->PartyName) ? $obj->Party->PartyName : 'kontakt')));
			$widgets = $this->callAll('contactGetLatest', array($obj));
			$tpl = new contacts\layout\finance\View($obj, $widgets);
			$html->appendContent($tpl);
		}
				
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	function edit($id = null){
		$core = new \helper\core('contacts');
		$obj = \api\contacts::getContact($id);
		
		$html = $this->getTpl();
		
		//was there any result?
		if(!$obj){
			$html->appendContent(\helper\layout\Element::heading('Kontakter',
			'Detaljer for kontakter'));
			$html->add2content('<div class="alert alert-info">'.__('Contact doesn\'t exist').'</div>');
		}
		else{
			$html->appendContent(\helper\layout\Element::heading('Kontakter',
				'Detaljer for ' . (isset($obj->Party->PartyName) ? $obj->Party->PartyName : 'kontakt')));
			$widgets = $this->callAll('contactGetLatest', array($obj));
			$tpl = new contacts\layout\finance\Form($obj, $widgets);
			$html->appendContent($tpl);
		}
				
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/***************************** AJAX FUNCTION ******************************/
	
	/**
	* inserts a new contact
	*/
	function create($json = null){
		//@TODO awareness of profile
		$contact = new \helper\parser\Post('\model\finance\Contact');
		
		$cObj = $contact->getObj();
		
		if(($cObj = \api\contacts::create($cObj)) !== null)
			$this->setUserMsg('contact_create', "Kontakten er indsat");
		else
			$this->setUserMsg('contact_create', "Der skete en fejl");
		
		if(!$json){
			$c = "kontakten indsat";
			$this->header->redirect('/contacts/view/'.$cObj->_id);
		}
		else{
			$this->header->setMime('json');
			$c = json_encode($cObj->toArray());
		}
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $c;
	}
	
	/**
	* updates an object into the contact, sent as formdata
	*/
	function update(){
		$contact = new \helper\parser\Post('\model\finance\Contact');
		
		$cObj = $contact->getObj();
		
		$id = $cObj->_id;
		
		\api\contacts::update($cObj);
		
		$this->header->redirect('/contacts/view/'.$id);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}
	
	/**
	* fetches entries as JSON, for use with some AJAX tables
	*/
	public function fetchEntries(){
		//iterator has to be json
		$iterator = \api\contacts::getLodo();
		$list = new contacts\layout\finance\Listing($iterator, $this->param);
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = $list->generate('json');
	}
	
	/**
	* triggers retrieval of external information
	*/
	public function extRetrive($id = null){
		$this->header->redirect('/contacts/view/'.$id);
		
		\api\contacts::retrieveExternal($id);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}
	
	/**
	* returns list based on a term
	*/
	public function autocomplete($term=''){
		//we'll take the 10 first that mach
		$objects = \api\contacts::search($term, array('Party.PartyName.Name._content' => 1), 10);
		
		$ret = array();
		
		//convert to the right form
		foreach($objects as $o){
			$ret[] = array(
				'id' => (string) $o->_id,
				'label' => $o->Party->PartyName->Name->_content,
				'category' => strtoupper(substr($o->Party->PartyName, 0, 1)));
		}
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($ret);
	}
	
	/**
	* returns json object from id
	*/
	public function getContact($id=null){
		$obj = \api\contacts::getContact($id);
		
		//some formatting
		$obj = $obj->toArray();
		$obj['contactID'] = (string) $obj['_id'];
		
		foreach($obj as $k => $v){
			if(substr($k, 0, 1) == '_')
				unset($obj[$k]);
		}
		
		$obj = array_key_implode('-', $obj);
		
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($obj);
	}
	
	/**** private functions ****/
	private function getTpl(){
		$html = $this->getSiteAPI()->getTemplate();
		
		$html->setSecondaryTitle(__('Contacts'));
		$html->addSecondaryNav(__('View Contact'), '/contacts');
		$html->addSecondaryNav(__('Create Contact'), '/contacts/add');
		
		return $html;
	}
	
	
	/**
	* Required functions
	*/
	
	function setup($done=false){
		if($done){
			$this->getSiteAPI()->finishSetup('contacts');
			$this->header->redirect('/index');
		}
	
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Kontaker', 
			'En lille introduktion'));
		
		$html->appendContent(new contacts\layout\finance\Setup());
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	function getOutputHeader(){
		return $this->output_header;
	}
	
	function getOutputContent(){
		return $this->output_content;
	}
}
