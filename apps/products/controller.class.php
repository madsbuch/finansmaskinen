<?
namespace app;

use \helper\local as l;

class products extends \core\app{
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
	function __construct($r){
		$this->header = new \helper\header();
		parent::__construct($r);
	}
	
	function index(){
		$html = $this->getOutTpl();
		
		$html->appendContent(\helper\layout\Element::heading('Produkter',
			'Alle produkter'));
		
	//create the addlink
		$html->appendContent(\helper\layout\Element::primaryButton(
			'/products/add', 
			'<i class="icon-plus" /> '.__('Create Product')));
		
		$products = \api\products::get();
		
		//the descriptor for making the table from the objects
		$table = new products\layout\finance\Listing();
		
		$html->appendContent($table);
		
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	function add(){
		$html = $this->getOutTpl();
		$html->appendContent('
	<header class="jumbotron subhead" id="overview">
		<h1>Produkter</h1>
		<p class="lead">Tilføj produkt</p>
	</header>');
		
		//@TODO make profile independent
		$form = new products\layout\finance\Form();
		
		$html->appendContent($form->generate());
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* view details for some product
	*/
	function view($id=null){
		$html = $this->getOutTpl();
		
		$html->appendContent(\helper\layout\Element::heading('Produkter',
			'Detaljer for produkt'));
		
		$prd = \api\products::getOne($id);
		
		if(!$prd)
			$html->appendContent('<div class="alert alert-info">'.__('Product doesn\'t exist').'</div>');
		else
			$html->appendContent(new \app\products\layout\finance\View($prd));
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* edit some products
	*/
	function edit($id){
		$html = $this->getOutTpl();
		
		$html->appendContent(\helper\layout\Element::heading('Produkter',
			'Rediger produkt'));
		
		$prd = \api\products::getOne($id);
		
		//prepare the model
		$prd->Price->PriceAmount->_content = l::writeValuta($prd->Price->PriceAmount->_content);
		
		$html->appendContent(new \app\products\layout\finance\Form($prd));
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* view a category
	*/
	function catagory($id = null){
		$html = $this->getOutTpl();
		
		$html->appendContent(\helper\layout\Element::heading('Produkter',
			'Se katagori'));
		
		$catObj = \api\products::getCatagory($id);
		
		$html->appendContent(new \app\products\layout\finance\CategoryView($catObj));
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	//make sure to have everything renamed
	function  category($id = null){
		return $this->catagory($id);
	}

	/**
	 * lists categories
	 */
	function categories(){
		$html = $this->getOutTpl();

		$html->appendContent(\helper\layout\Element::heading(__('Categories'),
			__('Administrate categories')));

		$html->appendContent(new \app\products\layout\finance\ListCategories(\api\products::getAllCategories()));

		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	
	/// SOME AJAX FUNCTIONS ///
	
	/**
	* fetches entries for table
	*/
	function fetchEntries(){
		//iterator has to be json
		$iterator = \api\products::getLodo();
		$list = new products\layout\finance\Listing($iterator, $this->param);
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = $list->generate('json');
	}
	
	/**
	* this function is for both adding ajax, and none ajax.
	*
	* if paramter ajax=true, it will return created object as JSON, otherwise
	* it redirects to /products/view/<id of the new product>
	*/
	function create($ajax = false){
		//@TODO awareness of profile
		$prod = new \helper\parser\Post('\model\finance\Product');
		
		$prod->alterArray(function($arr){
			if(!isset($arr['inCatalog']))
				$arr['inCatalog'] = false;
			else
				$arr['inCatalog'] = true;
			$arr['Price']['PriceAmount']['_content'] = l::readValuta($arr['Price']['PriceAmount']['_content']);
			
			return $arr;
		});
		
		$obj = $prod->getObj();
		
		if(($obj = \api\products::create($obj)) !== null)
			$c = "Kontakten er indsat";
		else
			$c = "Der skete en fejl";
		
		//if(!$ajax)
		//	$this->header->redirect('/products');
		//else{
			$this->header->setMime('json');
			$c = json_encode($obj ? $obj->toArray() : $c);
		//}
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $c;
	}
	
	/**
	* updates an object based on post.
	*
	* looks for the id in the form data, not the urld
	*/
	function update($ajax = false){
		$prod = new \helper\parser\Post('\model\finance\Product');
		
		$prod->alterArray(function($arr){
			if(!isset($arr['inCatalog']))
				$arr['inCatalog'] = false;
			else
				$arr['inCatalog'] = true;
			
			$arr['Price']['PriceAmount']['_content'] = l::readValuta($arr['Price']['PriceAmount']['_content']);
			
			return $arr;
		});
		
		$obj = $prod->getObj();
		
		$id = $obj->_id;
		
		if(($obj = \api\products::update($obj)) !== null)
			$c = "Kontakten er indsat";
		else
			$c = "Der skete en fejl";
		
		if(!$ajax)
			$this->header->redirect('/products/view/'. $id);
		else{
			$this->header->setMime('json');
			$c = json_encode($obj ? $obj : $c);
		}
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $c;
	}
	
	/**
	* autocomplete
	*/
	function autocomplete($term=''){
		$objects = \api\products::search($term, array('Item.Name._content' => 1), 10);
		
		$ret = array();
		
		foreach($objects as $o){
			$ret[] = array(
				'id' => (string) $o->_id,
				'label' => $o->Item->Name->_content,
				'desc' => isset($o->Item->Description->_content) ? $o->Item->Description->_content : '',
				'category' => strtoupper(substr($o->Item->Name->_content,0,1)),
				);
		}
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($ret);
	}
	
	/**
	* return a single jsonencoded product
	*/
	function getProduct($id = null){
		$obj = \api\products::getOne($id);
		
		//some formatting
		$obj = $obj->toArray();
		$obj['productID'] = (string) $obj['_id'];
		
		foreach($obj as $k => $v){
			if(substr($k, 0, 1) == '_')
				unset($obj[$k]);
		}
		$obj['Price']['PriceAmount']['_content'] = l::writeValuta((int)$obj['Price']['PriceAmount']['_content']);
		
		$obj = array_key_implode('-', $obj);
		
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($obj);
	}
	
	function autocompleteCatagory($term = null){
		$objects = \api\products::getCats($term);
		
		$ret = array();
		
		foreach($objects as $o){
			$ret[] = array(
				'id' => (string) $o->_id,
				'label' => $o->name,
				'desc' => isset($o->description) ? $o->description : '',
				'category' => strtoupper(substr($o->name,0,1)),
				);
		}
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($ret);
	}
	
	function getCatagory($id = null){
		$obj = \api\products::getCatagory($id);
		$ret = $obj->toArray();
		$ret['id'] = (string) $ret['_id'];
		unset($ret['_id'], $ret['_subsystem']);
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($ret);
	}
	
	/**
	* Required functions
	*/
	
	function setup($done=false){
		if($done){
			$this->getSiteAPI()->finishSetup('products');
			$this->header->redirect('/index');
		}
	
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Produkter', 
			'En lille introduktion'));
		
		$html->appendContent(new products\layout\finance\Setup());
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	function getOutputHeader(){
		return $this->output_header;
	}
	
	function getOutputContent(){
		return $this->output_content;
	}
	
	/**** Private functions ****/
	private function getOutTpl(){
		$tpl = $this->getSiteAPI()->getTemplate();
		$tpl->setSecondaryTitle(__('Products'));
		$tpl->addSecondaryNav(__('View Products'), '/products');
		$tpl->addSecondaryNav(__('Create Product'), '/products/add');
		$tpl->addSecondaryNav(__('Product categories'), '/products/categories');
		return $tpl;
	}
	
}
