<?

class app_clients extends core_app{
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
		$this->html->addTopNavItem("Regnskab", "apps", "index");
	}
	
	function index(){
		
		$html = $this->html;
	
		$html->setTitle("Klienter");
		
		$h = new helper_layout();
		$tiles = $h->blockHelper("tiles", $this->page);
		
		//new invoice
		$tiles->addTile(array(
			'title' => "Opret klient",
			'link' => "/clients/add",
			'callback' => "/clients/cb",
			'thumbnail' => array("static" => "/app/clients/images/icons/thumbnail.png"),
			'blocklink' => true,
			'content' => array('intet', 'at', 'vise')
		));
		
		//watch invoices
		$tiles->addTile(array(
			'title' => "Se dine klienter",
			'link' => "/clients/listC",
			'callback' => "/clients/cb",
			'thumbnail' => array("static" => "/app/clients/images/icons/thumbnail.png"),
			'blocklink' => true,
			'content' => array('intet', 'at', 'vise')
		));
		
		$this->html->add2content($tiles->getTiles());
		
	
	
		$this->output_header = $this->page->getHeader();
		$this->output_content = $this->page->getContent();
	}
	
	function listC(){
		
	}
	
	function add(){
		$html = $this->html;
		
		$html->setTitle("Klient - Tilføj");
		
		$form = $html->blockHelper("form", array("method" => "post"));

		//så skal der nogle virksomhedsoplysninger til
		$form->addContent($html->header("Virksomhedsoplysninger", 3));

		//CVR med mulighed for udtræk fra CVR database
		$form->addLabel("cvr", "CVR");
		$form->addInput(array("type" => "text", "name" => "cvr", "class" => "form", "style" => "width:225px;"));
		$form->addInput(array("type" => "button", "value" => "Hent", "class" => "form", "style" => "width:70px;", "title" => "Hent automatisk oplysninger baseret på CVR nummer."));
		$form->addContent($html->nl());
		//virksomhedsnavn
		$form->addLabel("company", "Virksomhedsnavn");
		$form->addInput(array("type" => "text", "name" => "company", "class" => "form", "style" => "width:300px;"));
		$form->addContent($html->nl());
		//adresse (faktureringsadresse)
		$form->addLabel("addr", "Adresse");
		$form->addInput(array("type" => "text", "name" => "addr", "class" => "form", "style" => "width:300px;"));
		$form->addContent($html->nl());
		//postnr og by
		$form->addLabel("zip", "Postnr. By.");
		$form->addInput(array("type" => "text", "name" => "zip", "class" => "form", "style" => "width:70px;"));
		$form->addInput(array("type" => "text", "name" => "city", "class" => "form", "style" => "width:225px;"));
		$form->addContent($html->nl());
		//og et lille submit felt til sidst :D
		$form->addContent($html->nl());
		$form->addInput(array("type" => "submit", "value" => "Opret", "class" => "form"));

		$html->add2content($form->getBlock());
		
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
