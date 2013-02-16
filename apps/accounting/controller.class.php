<?
namespace app;

use \helper\local as l;

class accounting extends \core\app{
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
	function __construct($request){
		parent::__construct($request);
		$this->header = new \helper\header();
	}
	
	function index(){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Regnskab',
			'Dit nuværende regnskab ser ud dom følger:'));
		
		$payableAcc = \api\accounting::getAccounts(true);
		
		$w = array(
			new accounting\layout\finance\widgets\Accounts($payableAcc, false),
			new accounting\layout\finance\widgets\Shortcuts(),
		);
		
		$accounting = new accounting\layout\finance\Statistics($w);
		$html->appendContent($accounting);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content =$html->generate();
	}
	
	/**
	* shows page with transactions for accounting
	*
	* if no accounting provided, current accounting is used
	*/
	function transactions($accounting=null){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading(__('Transactions'),
			__('Latest transactions for accounting')));
		
		$html->appendContent(new \app\accounting\layout\finance\quick\Insert());
		$html->appendContent(new \app\accounting\layout\finance\quick\Withdraw());
		
		$html->appendContent('<a href="/accounting/addTransaction" class="btn">Manuel postering</a>');

		$ts = \api\accounting::getTransactions($accounting, 0, 100000);
		
		$accounting = new accounting\layout\finance\ViewTransactions($ts);
		$html->appendContent($accounting);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * shows details on a single transaction
	 *
	 * @param null $id int
	 */
	function transaction($id = null){
		$html = $this->getOutTpl();

		$html->appendContent(new \app\accounting\layout\finance\ViewSingleTransaction(null));

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	function addTransaction($accounting=null, $special=null){
		
		if(!$accounting)
			$accounting = 'dit nuværrende regnskab';
		
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Tilføj postering',
			'Opret ny postering i ' . $accounting));
		
		if($special){
			$c = 'app\accounting\layout\finance\quick\\'.$special;
			if(class_exists($c))
				$html->appendContent(new $c());
			else
				$html->appendContent(new \helper\layout\UserMsg('Metoden er ikke tilgængelig'));
		}
		else
			$html->appendContent(new accounting\layout\finance\TransactionForm());
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/**
	* shows list of all accountings, or current in null given
	*/
	function accountings($id=null){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading('Dine Regnskaber',
			'-'));
		
		$html->appendContent(\helper\layout\Element::primaryButton(
			'#', 
			'<i class="icon-plus" /> '.__('Create accounting')));
		
		$objs = \api\accounting::getAll();
		$accs = new accounting\layout\finance\ListAccountings($objs);
		$html->appendContent($accs);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 *
	 */
	function accounts(){
		$html = $this->getOutTpl();
		//check for input
		$input = new \helper\parser\Post('\model\finance\accounting\Account');
		$object = $input->getObj();
		
		// add account, if present (ajax enabled?)
		if($object){
			if(is_null($object->allowPayments))
				$object->allowPayments = false;
			else
				$object->allowPayments = true;
			try{
				if(\api\accounting::createAccount($object)){
					$c = new \helper\layout\UserMsg('
						<p>Din konto er nu oprettet og klar til brug :-D</p>');
					$c->setTitle('Konto oprettet');
				}
				else{
					$c = new \helper\layout\UserMsg('
						<p>Kontoen blev ikke oprettet, du kan prøve igen senere?</p>');
					$c->setTitle('Vi fejlede');
				}
			}
			catch(\Exception $e){
				if(DEBUG)
					var_dump($e);
				$c = new \helper\layout\UserMsg('
					<p>Koden er enten brugt, moms er måske ikke rigtig? prøv igen.</p>');
				$c->setTitle('Kunne ikke lade sig gøre');
			}
			$html->appendContent($c);
		}
		
		$html->appendContent(\helper\layout\Element::heading('Dine Konti',
			'Virksomhedens kontoplan'));
		
		
		$objs = \api\accounting::getAccounts();
		
		$accs = new accounting\layout\finance\ListAccounts($objs);
		$html->appendContent($accs);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * show current vat statement
	 */
	function vat(){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading(__('Vatstatement'),
			'Nedenfor ser du de felter du skal indgive til Skat'));


		$statement = \api\accounting::getRapport('vatStatement');
		
		$html->appendContent(new accounting\layout\finance\Vat($statement));
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * show vatCodes and make it possible to create new ones
	 */
	function vatCodes(){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading(__('Moms koder'),
			'Se, rediger og opret momskoder'));

		$html->appendContent(new accounting\layout\finance\vatCodes(\api\accounting::getVatCodes()));

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 *
	 * @param $code
	 */
	function viewVatCode($code){
		$html = $this->getOutTpl();
		$html->appendContent(\helper\layout\Element::heading(__('Moms kode'),
			'Rediger ' . $code));

		$html->appendContent(new accounting\layout\finance\EditVatCode(\api\accounting::getVatCode($code)));

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/**
	* creates and show repports
	*/
	function repport($report = null, $id = null){
		$html = $this->getOutTpl();

		$statement = null;
		$ah = new \helper\accounting(\api\accounting::retrieve($id));

		switch($report){
			case 'incomeStatement' :
				$statement = new \app\accounting\layout\finance\reports\IncomeStatement($ah->report('DKIncomeStatement'));
				break;
			case 'balanceStatement' :
				$statement = new \app\accounting\layout\finance\reports\BalanceStatement($ah->report('DKBalance'));
				break;
			default:
				throw new \exception\PageNotFoundException(__('Raport not found'));
		}

		$html->appendContent($statement);

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * alias
	 * @param null $report
	 * @param null $id
	 */
	function report($report = null, $id = null){
		return self::repport($report, $id);
	}
	
	/************************************ AJAX ********************************/
	/**
	* at some time, all those will be migrated to some java servlet, therefor we
	* use these type of callbacks
	*/
	
	/*
	* very low priority, 
	*/
	function createAccounting(){
	
	}
	
	function createTransaction($jsonRet = false){
		$input = new \helper\parser\Post('model\finance\accounting\DaybookTransaction');
		$input->alterArray(function($arr){
			if(!isset($arr['date']))
				$arr['date'] = time();//creating
			else
				$arr['date'] = \DateTime::createFromFormat('d/m/Y', $arr['date'])->getTimestamp();//parsing
			foreach($arr['postings'] as &$p){
				$p['amount'] = l::readValuta($p['amount']);

				if(isset($p['positive']))
					$p['positive'] = true;
				else
					$p['positive'] = false;
			}

			if(isset($arr['approved']))
				$arr['approved'] = true;
			else
				$arr['approved'] = false;

			unset($arr['showbox']);

			return $arr;
		});
		
		$objs = $input->getObj();
		
		
		//var_dump($objs->toArray());
		//die();
		
		$err = false;

		\api\accounting::importTransactions($objs);
		
		//if json return is requested
		if($jsonRet){
			$this->header->setMime('json');
			$this->output_header = $this->header->generate();
			if($err)
				$this->output_content = json_encode(__($err));
			else
				$this->output_content = json_encode($objs->toArray());
			return;
		}
		
		$this->header->redirect("/accounting/transactions");
		$this->output_header = $this->header->generate();
		$this->output_content = '';
	}
	
	/**
	* right now inlined, move to here
	*/
	function createAccount(){
	
	}

	/**
	 * if filter = true, then search is a filter, and term is a search, otherwise
	 * search is the search
	 *
	 * @param $search string
	 * @param bool $filter
	 * @param $term string
	 * @return void
	 * @internal param string $filer
	 */
	function autocompleteAccounts($search = null, $filter = false, $term = null){
		
		$e = false; //equity only
		$p = false; //payable only
		$type = null;
		
		if($filter){
			if($search == 'equity')
				$e = true;
			elseif($search == 'payable')
				$p = true;
			elseif($search == 'expense')
				$type = 3;
			elseif($search == 'costs')
				$type = 4;
			$search = $term;
		}
		
		$objs = \api\accounting::getAccounts($p, $e, $type);
		
		//format for the autocompleter
		$ret = array();
		foreach($objs as $o){
			$o = $o->toArray();
			$r['id'] = $o['code'];
			$r['label'] = $o['code'] .' - '. $o['name'];
			$r['category'] = (string) (floor($o['code']/1000) * 1000);
			$ret[] = (object) $r;
		}
		
		$ret = json_encode($ret);
		
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = $ret;
	}
	
	/**
	* returns a specific account
	*/
	function getAccount($id = null){
		if(!is_null($id)){
			$obj = \api\accounting::getAccount($id);
			$ret = json_encode($obj->toArray());
		}
		else{
			$ret = json_encode(null);
		}
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = $ret;
	}

	/**
	 * takes a vatcode object and saves, or creates it
	 */
	function updateVatCode(){
		$input = new \helper\parser\Post('model\finance\accounting\VatCode');
		$obj = $input->getObj();

		\api\accounting::updateVatCode($obj);

		$this->header->redirect('/accounting/vatCodes/');

		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}

	/**
	 * creates and returns available vatcodes
	 *
	 * @param null $term the term used for searching
	 */
	function autocompleteVatCode($term = null){
		$objs = \api\accounting::getVatCodes();
		
		//format for the autocompleter
		$ret = array();
		foreach($objs as $o){
			$o = $o->toArray();
			$r['id'] = $o['code'];
			$r['label'] = $o['code'];
			$r['desc'] = $o['name'];
			$r['category'] = '';
			$ret[] = (object) $r;
		}
		
		$ret = json_encode($ret);
		
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = $ret;
	}

    /**
     * returns an object representing requested vatCode
     *
     * @param string $code the code to return object from
     */
    function getVatCode($code = null){
        $obj = \api\accounting::getVatCode($code);
        $ret = json_encode($obj->toArray());

        $this->header->setMime('json');
        $this->output_header = $this->header->getHeader();
        $this->output_content = $ret;
    }

	function resetVat(){
		\api\accounting::resetVat();

		$this->header->redirect('/');
		$this->output_header = $this->header->getHeader();
		$this->output_content = '';

	}

	/**
	 * @return void
	 */
	function vatPayed(){

        $input = new \helper\parser\Post('model\Base');
        $input->alterArray(function ($arr) {
            $arr['AccountCode'] = (int)$arr['AccountCode'];
            return $arr;
        });

        $input = $input->getObj();

	    \api\accounting::payVat($input->AccountCode);

	    $this->header->redirect('/');
	    $this->output_header = $this->header->getHeader();
	    $this->output_content = '';
    }
	
	/**
	* Required functions
	*/
	
	function setup($done=false){
		if($done){
			$input = new \helper\parser\Post('\model\Base');
			$input->alterArray(function($arr){
				if(!isset($arr['vatQuater']))
					$arr['vatQuater'] = false;
				else
					$arr['vatQuater'] = true;
				
				$arr['startdate'] = \DateTime::createFromFormat('d/m/Y', $arr['startdate'])->getTimestamp();
				$arr['enddate'] = \DateTime::createFromFormat('d/m/Y' ,$arr['enddate'])->getTimestamp();
				
				return $arr;
			});
			$setup = $input->getObj();
			\api\accounting::initiate($setup);
			$this->getSiteAPI()->finishSetup('accounting');
			$this->header->redirect('/index');
		}
	
		$html = $this->getOutTpl();
		
		$setup = new accounting\layout\finance\Setup();
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

	/**
	 * @return \helper\template
	 */
	private function getOutTpl(){
		$tpl = $this->getSiteAPI()->getTemplate();
		$tpl->setSecondaryTitle('Regnskab');
		$tpl->addSecondaryNav('Alle Regnskaber', '/accounting/accountings');
		$tpl->addSecondaryNav('Kontoplan', '/accounting/accounts');
		$tpl->addSecondaryNav('Transaktioner', '/accounting/transactions');
		$tpl->addSecondaryNav('Moms', '/accounting/vat');
		$tpl->addSecondaryNav('Balance', '/accounting/repport/balanceStatement');
		$tpl->addSecondaryNav('Resultatopgørelse', '/accounting/repport/incomeStatement');

		$tpl->setMsg($this->getUserMsg());
		$this->clearUserMsg();
		return $tpl;
	}
}
