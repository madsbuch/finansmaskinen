<?php
/**
* API class for use
*/
namespace api;
class companyProfile{
	/*************************** INTERNAL API FUNCTIONS ***********************/
	
	/**
	* getThumbnail
	*
	* returns link to thumbnail
	*/
	static function getThumbnail(){
		
	}
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle($getName = true){
		$company = self::retrieve();
		return isset($company->Public->Party->PartyName->Name->_content) ? 
			$company->Public->Party->PartyName->Name->_content : 
			__('Your company');
	}

	static function on_getAppSettings($companyObject){
		$rets = array();

		$cp = self::retrieve();

		if(!empty($cp->settings))
			foreach($cp->settings as $s){
                $htmlID = $s->title . time();
	            $o = new \model\finance\company\AppSetting(array(
			            'title' => $s->title,
			            'settingsModal' => new \app\companyProfile\layout\finance\SettingsObject($s, $htmlID),
			            'modalID' => '#'.$htmlID
		            ));
				$rets[] = $o;
			}

		return $rets;
	}
	
	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	/**** some of all those money functions ****/
	
	const ACCOUNTCREDIT			= 1;
	const ACCOUNTWITHDRAWABLE	= 2;
	const ACCOUNTRESERVED		= 3;
	
	/**
	* inserts money on main credit account
	*
	* this is not approved untill moneyApply is called
	* account is either accountCredit or accountWithdrawable
	*/
	static function moneyInsert($amount, $ref, $account){
		//get a database object
		$pdo = new \helper\core('companyProfile');
		$pdo = $pdo->getDB()->dbh;
		//alot of validation
		if(!($account == self::ACCOUNTCREDIT || $account == self::ACCOUNTWITHDRAWABLE))
			throw new \Exception('account was not recognized');	
		if(!is_int($amount) && $amount > 0)
			throw new \Exception('amount has to be integer >0');
		$obj = self::retrieve(false);
		if(!$obj)
			throw new \Exception('company has to be initialized');
		$id = $obj->_id;
		
		//and finally, the actual query
		$sth = $pdo->prepare("INSERT INTO companyProfile_transactions
			(value, account, company_id, ref, approved) VALUES (?, ?, ?, ?, ?)");
		
		return $sth->execute(array($amount, $account, $id, $ref, false));
	}

	/**
	* not approved untill moneyApply
	* account is either accountCredit or accountWithdrawable
	*/
	static function moneyWithdraw($amount, $ref, $account){
		//get a database object
		$pdo = new \helper\core('companyProfile');
		$pdo = $pdo->getDB()->dbh;
		//alot of validation
		if(!($account == self::ACCOUNTCREDIT || $account == self::ACCOUNTWITHDRAWABLE))
			throw new \Exception('account was not recognized');	
		if(!is_int($amount) && $amount > 0)
			throw new \Exception('amount has to be integer >0');
		$obj = self::retrieve(false);
		if(!$obj)
			throw new \Exception('company has to be initialized');
		$id = $obj->_id;
		
		$amount = -1 * $amount;
		
		//and finally, the actual query
		$sth = $pdo->prepare("INSERT INTO companyProfile_transactions (value, account, company_id, ref, approved) VALUES (?, ?, ?, ?, ?)");
		
		return $sth->execute(array($amount, $account, $id, $ref, false));
	}
	/**
	* fails if there is not enough money on credit account
	*
	* not approve untill moneyApply
	*/
	static function moneyReserve($amount, $ref){
	
	}
	
	
	
	/**
	* deletes UNAPPROVED transactions
	*
	* this is equal to rollback
	*/
	static function moneyDelete($ref){
		//get a database
		$pdo = new \helper\core('companyProfile');
		$pdo = $pdo->getDB()->dbh;
		
		//some object fetch and validation
		$obj = self::retrieve(false);
		if(!$obj)
			throw new \Exception('company has to be initialized');
		
		$id = $obj->_id;
		
		//and execute
		$sth = $pdo->prepare("DELETE FROM companyProfile_transactions WHERE company_id = ? AND ref = ? AND approved = 0");
		
		return $sth->execute(array($id, $ref));
	}
	
	/**
	* applies all transactions on a ref
	*
	* this is equal to commit
	*/
	static function moneyApply($ref){
		//get a database
		$pdo = new \helper\core('companyProfile');
		$pdo = $pdo->getDB()->dbh;
		
		//some object fetch and validation
		$obj = self::retrieve(false);
		if(!$obj)
			throw new \Exception('company has to be initialized');
		
		$id = $obj->_id;
		
		//and execute
		$sth = $pdo->prepare("UPDATE companyProfile_transactions SET approved = 1 WHERE company_id = ? AND ref = ?");
		
		return $sth->execute(array($id, $ref));
	}
	
	/**
	* returns result on an account
	*/
	static function moneyResult($account){
		//get a database
		$pdo = new \helper\core('companyProfile');
		$pdo = $pdo->getDB()->dbh;
		
		//some object fetch and validation
		$obj = self::retrieve(false);
		if(!$obj)
			throw new \Exception('company has to be initialized');
		
		$id = $obj->_id;
		
		//and execute
		$sth = $pdo->prepare("SELECT SUM(value) FROM companyProfile_transactions WHERE company_id = ? AND account = ? AND approved = 1");
		
		$sth->execute(array($id, $account));
		
		$res = $sth->fetchAll();
		
		return (int) $res[0][0];
	}
	/**
	* initialize company for this tree
	*
	* if a company already exists, this function will fail
	*/
	static function initialize($company){
		$cp = new \helper\lodo('companyProfiles', 'companyProfile');
		$o = $cp->getObjects();
		
		if($o)
			return false;
		
		$core = new \helper\core('companyProfile');
		$company->treeID = $core->getTreeID();
		
		return $cp->insert($company);
	}
	
	/**
	* get details about this company
	*
	* $fetchall populates the object with details from MySQL database and stuff
	*/
	static function retrieve($fetchAll=true){
		$cp = new \helper\lodo('companyProfiles', 'companyProfile');
		$o = $cp->getObjects('\model\finance\Company');
		
		if(count($o) < 1)
			return null;
		
		$o = $o[0];
		//merge some transaction from db in, and set account values
		$o->accountReserved = 0;
		$o->accountCredit = 0;
		$o->accountWithdrawable = 0;
		
		return $o;
	}
	
	/**
	* a delta object that is merged in to the old one
	*
	* @TODO Too fucking bloated
	*/
	static function update($dObj){
		$o = self::retrieve();
		
		//initializing company, if none excists
		if(is_null($o))
			return self::initialize($dObj);
		
		//we don't wanna save this in the db;
		unset($o->transactions);
		unset($o->accountReserved);
		unset($o->accountCredit);
		unset($o->accountWithdrawable);
		
		//imploding the keys
		$a1 = array_key_implode('.', $o->toArray());
		$a2 = array_key_implode('.', $dObj->toArray());
		
		//merging
		$f = array_merge($a1, $a2);
		
		//to save
		$ts = array_key_explode('.', $f);
		$ts = new \model\finance\Company($ts);
		
		//saving
		$cp = new \helper\lodo('companyProfiles', 'companyProfile');
		return $cp->save($ts);
	}
	
	/**
	* gets public details on a company
	*
	* the returns the Party object of the contact.
	* @TODO remember to index the grp id field in the collection
	*/
	static function getPublic($treeID){
		$core = new \helper\core('companyProfile');
		$db = $core->getDB('mongo');
		$ret = $db->getCollection('companyProfiles')->findOne(array('treeID' => (string) $treeID));
		
		$ret = new \model\finance\Company($ret);
		
		if(isset($ret->Public))
			$ret = $ret->Public;
		else
			$ret = null;
		return $ret;
	}

	/**** module leves services ****/

	/**
	 * returns and increments value given by v
	 *
	 * e.g:
	 * invoiceNumber
	 *
	 * @param $val string key to increment
	 * @return int
	 */
	static function increment($val){
		$o = self::retrieve();
		
		if(!isset($o->counters->$val))
			throw new \Exception('no accounting to retrieve (lack of permissions)');
		
		//save number to return
		$ret = $o->counters->$val;
		
		//increment
		$o->counters->$val++;
		
		//return, if we succeed in updating the object
		if(self::update($o))
			return $ret;
		throw new \Exception('It wasn\'t possible to update accounting (lack of permissions)');
	}

	/**
	 * @param $for string settings object for retrieval
	 */
	static function getSettings($for){
		$o = self::retrieve();
		if(!isset($o->settings->$for))
			return null;
		return $o->settings->$for->settings;
	}

	/**
	 * save some settings object
	 *
	 * this is editable by the user
	 *
	 * @param $for
	 * @param $obj
	 */
	static function saveSettings($for, $obj){
		$o = self::retrieve();

		$s = new \model\finance\company\SettingsObj(array(
			'title' => $obj->getSettingsTitle(),
			'fields' =>$obj->getDescriptions(),
			'settings' => $obj
		));

        if(!isset($o->settings))
            $o->settings = array(
                $for => $s
            );
        else
		    $o->settings->$for = $s;

		self::update($o);
	}
	
	/**** functions for invoicing the customer ****/
	
	/**
	* creates an invoice at finansmaskinen.dk and creates a bill for this customer
	*
	* the bill is unpaied
	*/
	static function doInvoice($credit, $valuta){
		//invoice to create
		$invoice = array();
		$invoice['AccountingCustomerParty'] = array();
		
		$invoice['AccountingSupplierParty'] = array();
		
		$invoice['InvoiceLine'] = array();
		
		//create invoice
		
		//insert the invoice as bill
	}
	
	/**
	* if the invoice was successfully paied, call this function
	*/
	static function payInvoice($id){
	
	}
	
	/**
	* returns lodo of the invoices
	*/
	static function getInvoices(){
	
	}
}
?>
