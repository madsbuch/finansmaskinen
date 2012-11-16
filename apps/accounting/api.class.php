<?php
/**
* API class for use
*/
namespace api;
class accounting{
	/*************************** INTERNAL API FUNCTIONS ***********************/
	
	/**
	* getThumbnail
	*
	* returns link to thumbnail
	*/
	static function getThumbnail(){
	
	}
	
	/**
	* get accepted filetypes
	*
	* if the app handles files, these are the fileendings appepted
	*/
	static function getAcceptetFiletypes(){
		
	}
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return "Regnskab";
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return "Se og administrer dit regnskab og moms";
	}
	
	/**** SOME HOOKS ****/
	/**
	* returns widget for frontpage
	*/
	static function on_getWidget(){
		$accounts = self::getAccounts(true);
		return new \app\accounting\layout\finance\widgets\Accounts($accounts);
	}
	
	/*************************** ACTUAL API FUNCTIONS ***********************/
	/**
	* Anatomy:
	*
	* accounting methods:
	* initiate, create, updates ect...
	*
	* transactions
	* CRUD ect.
	*
	* accounts...
	*
	*/
	
	
	/**
	*initiating the system
	*
	* options:
	*	preset:     name of the preset object to create from (setup folder)
	*	vatQuater:  sets vat reminders for every 3 month if true, otherwise every 6th (optional)
	*	startdate:  start date of the accounting (getAccountstimestamp)
	*	enddate:    end date of the accounting (timestamp)
	*/
	static function initiate($options){
		$accounting = new \model\finance\Accounting();
		$accounting->periodStart = $options->startdate;
		$accounting->periodEnd = $options->enddate;
		$accounting->reminders = new \model\Base();
		//@TODO, this doesn't work on not calender years
		$accounting->reminders->jan = new \model\finance\accounting\Reminder(array(
			'message' => 'Julen er ovre, momsen skal indberettes.',
			'time' => mktime(0,0,0,1,1,date('Y', $options->startdate)),
			'isSent' => false
		));
		if($options->vatQuater)
			$accounting->reminders->apr = new \model\finance\accounting\Reminder(array(
				'message' => 'Så skal der indberettes moms.',
				'time' => mktime(0,0,0,4,1,date('Y', $options->startdate)),
				'isSent' => false
			));
		$accounting->reminders->apr = new \model\finance\accounting\Reminder(array(
			'message' => 'Så skal der indberettes moms.',
			'time' => mktime(0,0,0,7,1,date('Y', $options->startdate)),
			'isSent' => false
		));
		if($options->vatQuater)
			$accounting->reminders->apr = new \model\finance\accounting\Reminder(array(
				'message' => 'Så skal der indberettes moms.',
				'time' => mktime(0,0,0,10,1,date('Y', $options->startdate)),
				'isSent' => false
			));

		//set the accounting title
		if(date('Y', $options->startdate) == date('Y', $options->enddate))
			$accounting->title = 'Regnskab ' . date('Y', $options->enddate);
		else
			$accounting->title = 'Regnskab ' . date('Y', $options->startdate) . 
				'/' . date('Y', $options->enddate);
		
		$accObj = self::create($accounting, true);
		
		//add preset data
		$preset = '\app\accounting\setups\\'.$options->preset;
		$preset = new $preset();
		
		$accHelper = new \helper\accounting((string) $accObj->_id);
		foreach($preset::$vatCodes as $vc)//add vatcodes
			$accHelper->createVatCode(new \model\finance\accounting\VatCode($vc));
		
		foreach($preset::$accounts as $acc)//add accounts
			$accHelper->createAccount(new \model\finance\accounting\Account($acc));
		
		//and products (if there is access)
		foreach($preset::$productCatagories as $pcs)
			\api\products::createCat(new \model\finance\products\Catagory($pcs));
		
		return $accObj;
	}
	
	/**
	* creates a new accounting
	*/
	static function create($accounting, $setCurrent=false){
		//create new subgroup of the main accounting group
		$core = new \helper\core('accounting');
		$mainGrp = $core->getMainGroup();
		$newGroup = $core->createGroup($mainGrp);
		$core->setMeta($newGroup, 'name', $accounting->title);
		$core->reFetch();//otherwise the other operations can not be done
		
		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setGroups(array($newGroup));
		
		$accounting->current = false;
		$obj = $lodo->insert($accounting);
		
		if($setCurrent)
			self::setCurrent($obj->_id);
		return $obj;
	}
	
	/**
	* sets this one as current, and removes other as current
	*/
	static function setCurrent($accounting_id){
		//collection, add ;)
		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setReturnType('\model\finance\Accounting');
		
		$todo = $lodo->getFromId($accounting_id);
		
		//if the object is nonexisting, or closed, it cannot be primary
		if(!$todo || $todo->closed == true)
			return false;
		
		//making sure, that if an error has happened, all objects are set to false
		$lodo->addCondition(array('current' => true));
		$objs = $lodo->getObjects();
		
		foreach($objs as $o){
			$o->current = false;
			$lodo->save($o);	
		}
		
		$todo->current = true;
		$lodo->save($todo);
		return true;
	}
	
	/**
	* retrieves an accounting
	*
	* be aware, not all transactions are returned by default
	* if $id = null, current accounting is retrived.
	*
	*
	* @param $ts, populate with transactions?
	* @param $accounts, the return object is populated with details from MySQL
	*/
	static function retrieve($id=null, $ts=false, $as=false){
		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setReturnType('\model\finance\Accounting');
		
		$obj = null;
		
		if($id)//retrive by this id
			$obj = $lodo->findOne(array('_id' => $id));
		else
			$obj = $lodo->findOne(array('current' => true));
		
		if(is_null($obj->_id))
			return null;
		
		if($ts){
			$transactions = self::getTransactions((string) $obj->_id);
			$obj->transactions = $transactions;
		}
		if($ts){
			$transactions = self::getTransactions((string) $obj->_id);
			$obj->transactions = $transactions;
		}
		
		return $obj;
	}
	
	/**
	* returns all accountings, no populations from MySQL
	*/
	static function getAll(){
		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setReturnType('\model\finance\Accounting');
		return $lodo->getObjects();
	}
	
	/**
	* update some details
	*
	* it is not possible to overwrite transactions, only additions can be made
	* to those
	*/
	static function update($obj){
	
	}
	
	/**
	* delete an accounting
	*
	* aaaaand it's gone ;)
	*/
	static function delete($id){
	
	}
	
	/**** TRANSACTIONS ****/
	
	/**
	* add a transaction to an accounting
	*
	* if no accounting is specified, then it's added to current accounting
	*
	* @param $transaction array of transactions, this sytem makes sure, that
	* every transaction is applied
	*/
	static function addTransactions($transactions, $accounting=null){
		$accObj = self::retrieve($accounting, false);
		
		if(!$accObj)
			throw new \Exception('No accounting available at adding transactions');
		
		$acc = new \helper\accounting($accObj->_id);
		
		if(!is_array($transactions) && 
			!(is_object($transactions) && is_a($transactions, '\model\Iterator'))){
			$transactions = array($transactions);
		}
		
		foreach($transactions as $t){
			$acc->addTransaction($t);
		}
		
		$acc->commit();
	}
	
	/**
	* returns a list of transactions
	*
	* compression: only refs are extracted
	*
	* @param $account id of the account to retrieve to
	*
	* @return array of \model\finance\accounting\Transaction
	*/
	static function getTransactions($accountID = null, $from = 0, $num = 10, $compress = false){
		$acc = self::retrieve($accountID);
		if(is_null($acc))
			throw new \Exception('No accounting available');
		$acc = (string) $acc->_id;
		$acc = new \helper\accounting($acc);
		
		return $acc->getTransactions($from, $num, $compress);
	}
	
	/**
	* approves transactions of given refere
	*
	* this is similar to commit in databases
	*/
	static function applyTransactions($ref){
	
	}
	
	/**
	* deletes transactions of given ref
	*
	* this can only be done, if they are not approved
	* this is similar to roolback in databases
	*/
	static function deleteTransactions($ref){
	
	}
	
	/**
	* this is kind of an magic function.
	*
	* here interfacing to this system is defined:
	*
	* @param $trans array of objects, they are expected to be homogeneous.
	*				behaviour is not documentet otherwise
	* @param $type exåæicitly tell what the transactions is of type
	*/
	static function importTransactions($trans, $type = null, $ref = null){
		//this is used, when registering products.
		
		$ah = new \helper\accounting((string)self::retrieve()->_id);
		reset($trans);
		$f = current($trans);
		if(is_a($f, 'model\finance\products\Catagory')){
			foreach($trans as $cat){
				$vat = isset($cat->vatAmount) ? false : true;
				$ah->automatedTransaction(
					$cat->amount,
					($vat ? $cat->accountInclVat : $cat->accountExclVat),
					$cat->accountLiability,
					$cat->accountAssert,
					$ref,
					$vat,
					$cat->vatAmount
				);
				
			}
			$ah->commit();
		}
	}
	
	/**** ACCOUNTS ****/
	
	/**
	* creates new account
	*/
	static function createAccount($account){
		$acc = new \helper\accounting(null);
		return $acc->createAccount($account);
	}
	
	/**
	* returns a list of given accounts
	*/
	static function getAccounts($onlyPayable=false, $onlyEquity=false){
		$acc = self::retrieve();
		$acc = new \helper\accounting((string) $acc->_id);
		
		$flag = 0;
		$flag = $onlyPayable ? $flag | 1 : $flag;
		$flag = $onlyEquity ? $flag | 2 : $flag;
		return $acc->getAccounts($flag);
	}
	
	/**
	* return a single account
	*/
	static function getAccount($id){
		$acc = self::retrieve();
		$acc = new \helper\accounting((string) $acc->_id);
		return $acc->getAccount($id);
	}
	
	/**** VAT CODES ****/
	
	/**
	* returns all vatcodes
	*/
	static function getVatCodes(){
		$acc = self::retrieve();
		$acc = new \helper\accounting((string) $acc->_id);
		return $acc->getVatCodes();
	}
	
	/**
	* returns vatcode for given account
	*/
	static function getVatCodeForAccount($account){
		$acc = self::retrieve();
		$acc = new \helper\accounting((string) $acc->_id);
		return $acc->getVatCodeForAccount($account);
	}
	
	static function createVatCode($vatCode){
	
	}
	
	/**** RAPPORTS ****/
	
	static function getRapport($type){
		$acc = new \helper\accounting((string) self::retrieve()->_id);
		switch(strtolower($type)){
			case 'vatstatement':
				return $acc->getVatStatement();
			break;
		}
		throw new \Exception(__('rapport doesn\'t exist: %s', $type));
	}
	
	
	/**
	* a lot of default settings are needed when easing accountance.
	*
	* they are global to this treeID
	*/
	static function getSettings(){
	
	}
	
	static function export(){}
	static function import(){}
	static function backup(){}
}

?>
