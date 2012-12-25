<?php
/**
 * API class for use
 */
namespace api;
class accounting
{
	/*************************** INTERNAL API FUNCTIONS ***********************/

	/**
	 * getThumbnail
	 *
	 * returns link to thumbnail
	 */
	static function getThumbnail()
	{

	}

	/**
	 * get accepted filetypes
	 *
	 * if the app handles files, these are the fileendings appepted
	 */
	static function getAcceptetFiletypes()
	{

	}

	/**
	 * getTitle
	 *
	 * Returns user friendly name of app (in current language)
	 */
	static function getTitle()
	{
		return "Regnskab";
	}

	/**
	 * get description
	 *
	 * returns user readable description of app (in users language)
	 */
	static function getDescription()
	{
		return "Se og administrer dit regnskab og moms";
	}

	/**** SOME HOOKS ****/
	/**
	 * returns widget for frontpage
	 */
	static function on_getWidget()
	{
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
	 *    preset:     name of the preset object to create from (setup folder)
	 *    vatQuater:  sets vat reminders for every 3 month if true, otherwise every 6th (optional)
	 *    startdate:  start date of the accounting (getAccountstimestamp)
	 *    enddate:    end date of the accounting (timestamp)
	 */
	static function initiate($options)
	{
		$accounting = new \model\finance\Accounting();
		$accounting->periodStart = $options->startdate;
		$accounting->periodEnd = $options->enddate;
		$accounting->reminders = new \model\Base();
		//@TODO, this doesn't work on not calender years
		$accounting->reminders->jan = new \model\finance\accounting\Reminder(array(
			'message' => 'Julen er ovre, momsen skal indberettes.',
			'time' => mktime(0, 0, 0, 1, 1, date('Y', $options->startdate)),
			'isSent' => false
		));
		if ($options->vatQuater)
			$accounting->reminders->apr = new \model\finance\accounting\Reminder(array(
				'message' => 'Så skal der indberettes moms.',
				'time' => mktime(0, 0, 0, 4, 1, date('Y', $options->startdate)),
				'isSent' => false
			));
		$accounting->reminders->apr = new \model\finance\accounting\Reminder(array(
			'message' => 'Så skal der indberettes moms.',
			'time' => mktime(0, 0, 0, 7, 1, date('Y', $options->startdate)),
			'isSent' => false
		));
		if ($options->vatQuater)
			$accounting->reminders->apr = new \model\finance\accounting\Reminder(array(
				'message' => 'Så skal der indberettes moms.',
				'time' => mktime(0, 0, 0, 10, 1, date('Y', $options->startdate)),
				'isSent' => false
			));

		//set the accounting title
		if (date('Y', $options->startdate) == date('Y', $options->enddate))
			$accounting->title = 'Regnskab ' . date('Y', $options->enddate);
		else
			$accounting->title = 'Regnskab ' . date('Y', $options->startdate) .
				'/' . date('Y', $options->enddate);

		$accObj = self::create($accounting, true);

		//add preset data
		$preset = '\app\accounting\setups\\' . $options->preset;
		$preset = new $preset();

		$accHelper = new \helper\accounting((string)$accObj->_id);
		foreach ($preset::$vatCodes as $vc) //add vatcodes
			$accHelper->createVatCode(new \model\finance\accounting\VatCode($vc));

		foreach ($preset::$accounts as $acc) //add accounts
			$accHelper->createAccount(new \model\finance\accounting\Account($acc));

		//and products (if there is access)
		foreach ($preset::$productCatagories as $pcs)
			\api\products::createCat(new \model\finance\products\Catagory($pcs));

		return $accObj;
	}

	/**
	 * creates a new accounting
	 */
	static function create($accounting, $setCurrent = false)
	{
		//create new subgroup of the main accounting group
		$core = new \helper\core('accounting');
		$mainGrp = $core->getMainGroup();
		$newGroup = $core->createGroup($mainGrp);
		$core->setMeta($newGroup, 'name', $accounting->title);
		$core->reFetch(); //otherwise the other operations can not be done

		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setGroups(array($newGroup));

		$accounting->current = false;
		$obj = $lodo->insert($accounting);

		if ($setCurrent)
			self::setCurrent($obj->_id);
		return $obj;
	}

	/**
	 * sets this one as current, and removes other as current
	 */
	static function setCurrent($accounting_id)
	{
		//collection, add ;)
		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setReturnType('\model\finance\Accounting');

		$todo = $lodo->getFromId($accounting_id);

		//if the object is nonexisting, or closed, it cannot be primary
		if (!$todo || $todo->closed == true)
			return false;

		//making sure, that if an error has happened, all objects are set to false
		$lodo->addCondition(array('current' => true));
		$objs = $lodo->getObjects();

		foreach ($objs as $o) {
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
	 * @param $id string id of accounting
	 * @param $ts, populate with transactions?
	 * @param $accounts, the return object is populated with details from MySQL
	 */
	static function retrieve($id = null, $ts = false, $as = false)
	{
		$lodo = new \helper\lodo('accountings', 'accounting');
		$lodo->setReturnType('\model\finance\Accounting');

		$obj = null;

		if ($id) //retrive by this id
			$obj = $lodo->findOne(array('_id' => $id));
		else
			$obj = $lodo->findOne(array('current' => true));

		if (is_null($obj->_id))
			return null;

		if ($ts) {
			$transactions = self::getTransactions((string)$obj->_id);
			$obj->transactions = $transactions;
		}
		if ($ts) {
			$transactions = self::getTransactions((string)$obj->_id);
			$obj->transactions = $transactions;
		}

		return $obj;
	}

	/**
	 * returns all accountings, no populations from MySQL
	 */
	static function getAll()
	{
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
	static function update($obj)
	{

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
	static function addTransactions($transactions, $accounting = null)
	{
		$accObj = self::retrieve($accounting);

		if (!$accObj)
			throw new \exception\UserException(__('No valid accounting selected'));

		$acc = new \helper\accounting((string) $accObj->_id);

		//if a single transaction, wrap it into a collection type
		if (!is_array($transactions) &&
			!(is_object($transactions) && is_a($transactions, '\model\Iterator'))){
			$transactions = array($transactions);
		}

		//add transactions
		foreach ($transactions as $t) {
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
	static function getTransactions($accountID = null, $from = 0, $num = 10)
	{
		$acc = self::retrieve($accountID);
		if (is_null($acc))
			throw new \Exception('No accounting available');
		$acc = (string)$acc->_id;
		$acc = new \helper\accounting($acc);

		return $acc->getTransactions($from, $num);
	}

	/**
	 * approves transactions of given refere
	 *
	 * this is similar to commit in databases
	 */
	static function applyTransactions($ref)
	{

	}

	/**
	 * deletes transactions of given ref
	 *
	 * this can only be done, if they are not approved
	 * this is similar to roolback in databases
	 */
	static function deleteTransactions($ref)
	{

	}

	/**
	 * this is kind of an magic function.
	 *
	 * here interfacing to this system is defined:
	 *
	 * options:
	 *  type: the type of the transaction data
	 *  referenceText:  override the reference text
	 *
	 *  liability:  liability account, used when auto calculate vat and balance is used
	 *  asset:      asset account --------------------||--------------------------------
	 *
	 *  calculateVat:       auto calculate vat?
	 *  calculateBalance:   auto calculate balance
	 *
	 *
	 * @param $transaction mixed The data containing the actial transaction. This is of various types
	 * @param $options array because of the flexible nature on the functions, objects are defined here
	 */
	static function importTransactions($transaction, $options = array())
	{
		//getting the parameters
		$type = isset($options['type']) ? $options['type'] : null;
		$ref = isset($options['referenceText']) ? $options['referenceText'] : null;

		$addVat =     isset($options['calculateVat'])     ? $options['calculateVat']     : false;
		$addBalance = isset($options['calculateBalance']) ? $options['calculateBalance'] : false;

		$lAcc = isset($options['liability']) ? $options['liability'] : null;
		$aAcc = isset($options['asset']) ? $options['asset'] : null;

		//accounting heler
		$ah = new \helper\accounting((string) self::retrieve()->_id);

		//converts to a transaction object
		if(is_string($type)){
			$strategyName = 'app\accounting\strategies\transactions\\'.$type;
			$strategy = new $strategyName();
			$transaction = $strategy->getDaybookTransaction($transaction);
		}

		//test if it's is a daybookTransaction
		if(is_a($transaction, '\model\finance\accounting\DaybookTransaction')){
			//if $ref is set explicitly, set it
			if(empty($transaction->referenceText))
				$transaction->referenceText = $ref;

			//maybe calculate vat?
			if($addVat)
				$transaction = $ah->vatCalculate($transaction, $lAcc, $aAcc);
			//and calculate the balance
			if($addBalance)
				$transaction = $ah->balanceCalculate($transaction, $lAcc, $aAcc);

			//and finally add all th stuff
			$ah->addDaybookTransaction($transaction);
			$ah->commit();
			return;
		}

		//try to guess the type (deprecated). requires the transaction to be an array
		reset($transaction);
		$f = current($transaction);
		//collection of Catagories
		if (is_a($f, 'model\finance\products\Catagory')) {
			foreach ($transaction as $cat) {
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
            return true;
		}

		throw new \exception\UserException(__('Wasn\'t able to insert transaction'));
	}

	/**** ACCOUNTS ****/

	/**
	 * creates new account
	 */
	static function createAccount($account)
	{
		$acc = new \helper\accounting(null);
		return $acc->createAccount($account);
	}

	/**
	 * returns a list of given accounts
	 */
	static function getAccounts($onlyPayable = false, $onlyEquity = false)
	{
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);

		$flag = 0;
		$flag = $onlyPayable ? $flag | 1 : $flag;
		$flag = $onlyEquity ? $flag | 2 : $flag;
		return $acc->getAccounts($flag);
	}

	/**
	 * @param array $ids array of account id's (strings)
	 */
	static function getAccountsByIds($ids = array())
	{

	}

	/**
	 * return a single account
	 */
	static function getAccount($id)
	{
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		return $acc->getAccount($id);
	}

	/**
	 * tries to remove an account
	 *
	 * if the account is used, this will fail
	 *
	 * @param $id string id of the account
	 */
	static function deleteAccount($id)
	{
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		return $acc->deleteAccount((string)$id);
	}

	/**** VAT ****/

	/**
	 * returns all vatcodes
	 */
	static function getVatCodes()
	{
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		return $acc->getVatCodes();
	}

	/**
	 * returns a single abject for at vatcode
	 */
	static function getVatCode($code)
	{
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		return $acc->getVatCode($code);
	}

	/**
	 * returns vatcode for given account
	 *
	 * @return \model\finance\accounting\VatCode
	 */
	static function getVatCodeForAccount($account)
	{
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		return $acc->getVatCodeForAccount($account);
	}

	static function createVatCode($vatCode)
	{

	}

	/**
	 * marks vat as reset in the current accounting
	 */
	static function resetVat(){
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		$acc->resetVatAccounting();
	}

	/**
	 * adjust asset account and the vat holding account
	 */
	static function payVat($assetAccount){
		$acc = self::retrieve();
		$acc = new \helper\accounting((string)$acc->_id);
		$acc->vatPayed($assetAccount);
	}

	/**** RAPPORTS ****/

	static function getRapport($type)
	{
		$acc = new \helper\accounting((string)self::retrieve()->_id);
		switch (strtolower($type)) {
			case 'vatstatement':
				return $acc->getVatStatement();
				break;
		}
		throw new \Exception(__('rapport doesn\'t exist: %s', $type));
	}


	/**
	 * a lot of default settings are needed when easing accountance.
	 *
	 * they are saved in companyProfile module
	 */
	static function getSettings()
	{
		$settings = \api\companyProfile::getSettings('accounting');
		//this will not override as exceptions are thrown if permissions are not pressent
		if($settings = null){
			$settings = new \model\finance\accounting\Settings();
			\api\companyProfile::saveSettings('accounting', $settings);
		}
		return $settings;
	}

	static function export()
	{
	}

	static function import()
	{
	}

	static function backup()
	{
	}
}

?>
