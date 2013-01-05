<?
namespace app;

use helper\local as l;

class companyProfile extends \core\app {
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* whether to get all grps or display a selector, and only get one grp
	*/
	static public $grpSelector = false;
	
	/**
	* construction
	*/
	function __construct(){
		$this->header = new \helper\header();
	}
	
	/**
	* shows the index page
	*/
	function index($ajax = false){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Dine virksomhed',
			'Administrer din virksomhed'));
		
		$input = new \helper\parser\Post('\model\finance\Company');
		$company = $input->getObj();
		if($company){
			\api\companyProfile::update($company);
		}
		
		$obj = \api\companyProfile::retrieve($company);
		$settings = $this->callAll('getAppSettings', array($obj));
		$page = new companyProfile\layout\finance\Page($obj, $settings);
		$html->appendContent($page);
		
		
		if($ajax){
			$this->header->setMime('json');
			$this->output_header = $this->header->generate();
			$this->output_content = 'null';
		}
		else{
			$this->output_header = $this->header->generate();
			$this->output_content = $html->generate();
		}
	}
	
	/**
	* shows latest transactions
	*/
	function transactions(){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Hændelser',
			'Seneste strømninger af penge.'));

		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* shows page for adding more apps to this company
	*/
	function modules($app = null){
		$html = $this->getOutTpl();
		
		$modules = companyProfile\Subscribe::$apps;
		
		if(!isset($modules[$app])){
			$html->appendContent(\helper\layout\Element::heading(__('Administrate modules'),
				__('Add or remove subscription for modules.')));
			$html->appendContent(new \app\companyProfile\layout\finance\Modules($modules));
		}
		else{
			$a = new $modules[$app];
			$html->appendContent(\helper\layout\Element::heading(
				__('Administrate %s', $a->getDescription()->title),
				__('Add or remove subscription for modules.')));
		}
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* shows page to add money to account
	*/
	function credit(){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Indsæt penge',
			'Indsæt penge på din konto'));
		
		$html->appendContent(new \app\companyProfile\layout\finance\MoneyInsert(null));
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* shows the page for paying an invoice
	*/
	function pay($id = null){
		$input = new \helper\parser\Post('\model\Base');
		
		if($input = $input->getObj()){
			if(empty($input->toInsert))
				;
			else //using DKK, @TODO, move default valuta to config
				\api\companyProfile::doInvoice(l::readValuta($input->toInsert, 'DKK'), 'DKK');
		}
	}
	
	/**** SOME AJAX FUNCTIONS ****/
	
	/**
	* creates an invoice, and redirects to the pay page
	*/
	function invoice(){

	}

    /**
     * updates some settings
     *
     * @param null $for
     */
    function updateSettings($for = null){
        if($for){
            $input = new \helper\parser\Post('\model\Base');
            $input = $input->getObj();
            \api\companyProfile::updateSettings((string) $for, $input);
        }
        $this->header->redirect('/companyProfile/index');
        $this->output_header = $this->header->generate();
        $this->output_content = '';
    }
	
	
	/**
	* Required functions
	*/
	
	function setup($done=false){
		$input = new \helper\parser\Post('\model\finance\Company');
		$company = $input->getObj();
		if($company){
			\api\companyProfile::update($company);
			$this->getSiteAPI()->finishSetup('companyProfile');
			$this->header->redirect('/index');
		}
	
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Din virksomhed', 
			'Vi mangler lidt flere detaljer'));
			
		$setup = new companyProfile\layout\finance\Setup();
		$html->appendContent($setup);
		
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
		$tpl->setSecondaryTitle(__('Company'));
		$tpl->addSecondaryNav(__('Add modules'), '/companyProfile');
		$tpl->addSecondaryNav(__('Transfer credit'), '/companyProfile/credit');
		return $tpl;
	}
}
