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
		try{
			$company = self::retrieve();
			return isset($company->Public->Party->PartyName->Name->_content) ?
				$company->Public->Party->PartyName->Name->_content :
				__('Your company');
		} catch(\exception\UserException $e){
			return __('Your company');
		}
	}

	static function on_getAppSettings($companyObject){
		$rets = array();

		$cp = self::retrieve();

		if(!empty($cp->settings))
			foreach($cp->settings as $for => $s){
                $htmlID = $s->title . time();
	            $o = new \model\finance\company\AppSetting(array(
			            'title' => $s->title,
			            'settingsModal' => new \app\companyProfile\layout\finance\SettingsObject(
                            $s,
                            $htmlID,
                            $for),
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
     *
     * @param $amount
     * @param $ref
     * @param $account
     * @return bool
     * @throws \Exception
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
	 *
	 * @param $amount
	 * @param $ref
	 * @param $account
	 * @param bool $allowSubZero
	 * @throws \exception\UserException
	 * @throws \Exception
	 * @return bool
	 */
    static function moneyWithdraw($amount, $ref, $account, $allowSubZero = false){
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
		
		if(!$sth->execute(array($amount, $account, $id, $ref, false)))
            throw new \exception\UserException('Withdrawal failed');
	}
	/**
	* fails if there is not enough money on credit account
	*
	* not approve untill moneyApply
	*/
	static function moneyReserve($amount, $ref){
	
	}


    /**
     * deletes unapproved transactions only
     *
     * @param $ref
     * @return bool
     * @throws \Exception
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
     * applies a transaction
     *
     * @param $ref
     * @return bool
     * @throws \Exception
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
     * returns account sum
     *
     * @param $account
     * @return int
     * @throws \Exception
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

    /**** some quick ticket functions ****/

    /**
     * attempts to decrement tickets by $num, if it results in subzero
     * nothing will be done and false is returned
     *
     * @param $num
     * @return bool
     */
    static function useTicket($num){
		/**
		 * @var $company \model\finance\Company
		 */
		$company = self::retrieve();

		//check if there is enough tickets
		if($company->freeTier < $num)
			return false;

		//decrement
		$company->freeTier = $company->freeTier - $num;

		//save
		self::update($company);

        return true;
    }

    /**
     * initilizes company for a given tree
     *
     * @param $company
     * @return bool|mixed
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
     * fetches current company object
     *
     * @param bool $fetchAll populate with detals from MySQL?
     * @throws \exception\UserException
     * @return \model\finance\Company
     */
    static function retrieve($fetchAll=true){
		$cp = new \helper\lodo('companyProfiles', 'companyProfile');

		/**
		 * @var $o \model\finance\Company
		 */
		$o = $cp->getObjects('\model\finance\Company');

		if(count($o) < 1)
		    return self::initialize(new \model\finance\Company());
		$o = $o[0];

		if($o->lastFreeTierReset + \config\finance::$settings['freeTierTime'] < time())
			$o = self::freeTierReset($o);

		//merge some transaction from db in, and set account values
		$o->accountReserved = 0;
		$o->accountCredit = 0;
		$o->accountWithdrawable = 0;
		
		return $o;
	}

	/**
	 * update company
	 *
	 * @param \model\finance\Company $obj
	 * @param bool $direct whether the object is directly put in to the DB, or merged
	 * @param bool $safe whether to unset illegal fields
	 * @return array|bool|mixed
	 */
    static function update(\model\finance\Company $obj, $direct = false, $safe=true){
		//@TODO check permissions

	    //unsets illegal fields
	    if($safe)
		    foreach(\model\finance\Company::$_blacklist as $b){
			    unset($obj->$b);
		    }

		$cp = new \helper\lodo('companyProfiles', 'companyProfile');
		if($direct)
			return $cp->save($obj);

		$o = self::retrieve();

		//we don't wanna save this in the db;
		unset($o->transactions);
		unset($o->accountReserved);
		unset($o->accountCredit);
		unset($o->accountWithdrawable);

		//imploding the keys
		$a1 = array_key_implode('.', $o->toArray());
		$a2 = array_key_implode('.', $obj->toArray());

		//merging
		$f = array_merge($a1, $a2);

		//to save
		$ts = array_key_explode('.', $f);
		$ts = new \model\finance\Company($ts);

	    //var_dump($ts);
	    //die();

		//saving
		return $cp->save($ts);
	}

	/**
	 * retrieves public details on company
	 *
	 * TODO remember to index the grp id field in the collection
	 *
	 * @param $treeID
	 * @throws \exception\UserException
	 * @return null
	 */
    static function getPublic($treeID){
		$lodo = new \helper\lodo('companyProfiles', 'companyProfile');
		$lodo->setReturnType('\model\finance\Company');
		$lodo->addCondition(array('treeID' => (string) $treeID));

		$ret = $lodo->getObjects();
		
		if(isset($ret[0]->Public))
			return $ret[0]->Public;

		throw new \exception\UserException(__('supplier was not found'));
	}

	/**
	 * @param $company
	 * @return \model\finance\Company
	 */
	public static function freeTierReset(\model\finance\Company $company){
		if($company->freeTier < \config\finance::$settings['freeTierSize'])
			$company->freeTier = \config\finance::$settings['freeTierSize'];

		$company->lastFreeTierReset = time();

		self::update($company, true, false);

		return $company;
	}

	/**** module level services ****/

	/**
	 * validates if $action is allowed, withdraws money and returns true on sucess
	 * false otherwise
	 *
	 * TODO remember to log
	 *
	 * @param $action string the action to perform
	 * @throws \exception\PaymentException
	 * @return bool
	 */
    static function doAction($action){
        $actionStrategy = '\app\companyProfile\strategies\onAction\\'.$action;
        /** @var $actionStrategy \app\companyProfile\strategies\onAction\OnAction */
        $actionStrategy = new $actionStrategy(self::retrieve());

        //checks if the action is covered by subscription
        if($actionStrategy->coveredBySubscription())
            return true;

        //check if the action cat be paid by ticket
        if(self::useTicket($actionStrategy->getTicketPrice()))
            return true;

        //and finally attempt to withdraw money
        if(self::moneyWithdraw($actionStrategy->getPrice(), '', self::ACCOUNTCREDIT))
            return true;

        throw new \exception\PaymentException(__('Upgrade subscription or insert money.'));
    }

    /**
     * @param $action
     * @return string
     */
    static function getMessageForAction($action){
        $actionStrategy = '\app\companyProfile\strategies\onAction\\'.$action;
        /** @var $actionStrategy \app\companyProfile\strategies\onAction\OnAction */
        $actionStrategy = new $actionStrategy(self::retrieve());
        return $actionStrategy->getMessage();
    }


    /**
     * returns and increments value given by v
     *
     * e.g:
     * invoiceNumber
     * billNumber
     *
     * @param $val string key to increment
     * @throws \Exception
     * @return int
     */
	static function increment($val){
		$o = self::retrieve();

		//TODO check write permisssions

		//if the value doesn't exist, initialize to 1
		if(!isset($o->counters->$val))
			$o->counters->$val = 1;
		
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
     * get settings for some application
     *
     * be aware that model details are stripped, so when object is returned, it is ass array
     *
	 * @param $for string settings object for retrieval
     * @return array array to create model from
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
     * @param $title string not translated shown title (single word, no special chars)
     * @param $fields
     * @internal param array $fiels array as follows: "property in settingsObj" => not translated desc
     */
	static function saveSettings($for, $obj, $title, $fields){
		$o = self::retrieve();

		$s = new \model\finance\company\SettingsObj(array(
			'title' => $title,
			'fields' => $fields,
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

    /**
     * updates, which save also does, this just fails on create
     * and doesn't require title and field
     *
     * @param $for
     * @param $settings
     * @throws \exception\UserException
     */
    static function updateSettings($for, $settings){
        $o = self::retrieve();

        if(!isset($o->settings->$for))
            throw new \exception\UserException('Tried to update not existing settings object');
        $o->settings->$for->settings = $settings;

        self::update($o);
    }
	
	/**** interacting with finansmaskinen ****/

    /**
     * create an invoice sent by finansmaskinen (rpc)
     * and added as bill to this company
     *
     * @param $credit
     * @param $valuta
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
     * called when an invoice was successfully paid
     *
     * @param $id
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
