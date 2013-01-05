<?php
/**
 * the accounting app uses a lot og MySQL, this is very big, and therefor,
 * this class was made for abstraction
 *
 * no multigroup on accounte... :/ bah
 */


namespace helper;

class accounting
{

	private $accounting;
	private $grp;

	/**
	 * @var \helper\accounting\Queries
	 */
	private $queries;

	const ATABLE = 'accounting_accounts';
	const TRANST = 'accounting_transactions';

	//transactionable accounts
	const ASSET = 1;
	const LIBALITY = 2;
	const EXPENSES = 3;
	const INCOME = 4;

	//some administrative accounts
	const HEADING = 11; //just a heading
	const INTERVAL = 13; //interval of sums from other accounts, a single accounts
	//may be choosed, then it is used as a sum from account

	//flags for account, using bitmask
	const PAYABLE = 1;
	const EQUITY = 2;

	/**
	 * this variable is incremented and decremented every time a transactions i added
	 *
	 * on commit this have to be 0.
	 */
	protected $balance;

	/**
	 * //TODO refactor everything to use daybookTransaction and postings
	 *
	 * @var \model\finance\accounting\Transaction
	 * @deprecated
	 */
	private $transactions = array();

	/**
	 * a daybooktransaction object without the postings
	 * @var \model\finance\accounting\DaybookTransaction
	 */
	private $transactionInfo;

	/**
	 * array of \model\finance\accounting\Posting
	 * @var array
	 */
	private $postings;

	private $db;

	/**
	 * instanciate with details of the group and the accounting
	 *
	 * @param $accounting    the id of the accounting related to
	 * @param $grp            the groups the account are related to
	 */
	function __construct($accounting, $grp = null)
	{
		$this->accounting = (string) $accounting;
		$core = new core('accounting');

		$this->queries = new accounting\MysqlQueries();

		$this->grp = $grp ? $grp : (int)$core->getMainGroup();

		$this->db = $core->getDB();

		$this->accCheck = $this->db->dbh->prepare('SELECT * FROM ' . self::ATABLE . '
			WHERE grp_id = ' . $this->grp . ' AND code = ?;');
		$this->refCheck = $this->db->dbh->prepare('SELECT * FROM ' . self::TRANST . '
			WHERE ref = ? AND code = \'' . $accounting . '\';');
	}

	/**** different add functions ****/

	/**
	 * add transaction
	 *
	 * takes on transaction
	 * returns false if ref already exists in the database.
	 *
	 * @deprecated
	 */
	function addTransaction($transaction)
	{
		//some validation
		if ($transaction->value <= 0)
			throw new \exception\UserException(__('Value of transaction has to be more than 0 on account: %s', $transaction->account));

		if (!isset($transaction->date))
			throw new \exception\UserException('No transaction date set');

        //this function is deprecated, but for backwardscompatability:
        if(!isset($this->transactionInfo)){
            $this->transactionInfo = new \model\finance\accounting\DaybookTransaction(array(
                'data' => $transaction->date,
                'referenceText' => $transaction->ref,
                'approved' => $transaction->approved
            ));
        }


		//get account type:
		$this->accCheck->execute(array($transaction->account));
		$rArr = $this->accCheck->fetchAll();
		if (count($rArr) != 1)
			throw new \exception\UserException(__('Account "%s" does not exist.', $transaction->account));

		$rArr = $rArr[0];

		//check if refere already exists
		$this->refCheck->execute(array($transaction->ref));
		$refArr = $this->refCheck->fetchAll();

		if (!empty($refArr))
			throw new \Exception('Ref already exist in this accounting.');

		$transaction = $transaction->toArray();

		//set the values
		if ($transaction['positive']) {
			$transaction['value_positive'] = $transaction['value'];
			$transaction['value_negative'] = 0;
		} else {
			$transaction['value_positive'] = 0;
			$transaction['value_negative'] = $transaction['value'];
		}


		//set some stats to check validity of all transactions
		if ($rArr['type'] == self::ASSET)
			$this->balance += $transaction['positive'] ? $transaction['value'] : -1 * $transaction['value'];
		elseif ($rArr['type'] == self::LIBALITY)
			$this->balance -= $transaction['positive'] ? $transaction['value'] : -1 * $transaction['value'];

		$this->transactions[] = $transaction;
	}

	/**** daybook transactions ****/

	/**
	 * adds all postings from daybooktransaction object
	 *
	 * @param $transaction \model\finance\accounting\DaybookTransaction
	 */
	function addDaybookTransaction(\model\finance\accounting\DaybookTransaction $transaction){
		$this->postings = $transaction->postings;
		$this->transactionInfo = $transaction;

		if(empty($transaction->postings) || $transaction->postings->count() < 1)
			throw new \exception\UserException('Transaction must have postings');

        //TODO refactor so this isn't needed
		foreach($transaction->postings as $posting){
			$this->addTransaction(new \model\finance\accounting\Transaction(array(
				'value' => $posting->amount,
				'positive' => $posting->positive,
				'account' => $posting->account,

				'ref' => $transaction->referenceText,
				'date' => $transaction->date,
				'approved' => $transaction->approved
			)));
		}
	}

	function getDaybookTransaction($ref){

	}

	/**** helper methods ****/

	/**
	 * automatic add of transactions for a standard double accounting registration
	 *
	 * @param $amount    amount to insert to account
	 * @param $acc        the actual account
	 * ...
	 * @param $vat        whether to add $vat (only if an vataccount is ass. with acc)
	 * @param $vatAmount    Override calculation of vat, and use the specific amount
	 */
	function automatedTransaction(
		$amount, //amount to insert, exl vat
		$acc, //operating account
		$liabilityAccount, //libility account
		$assertAccount, //assert account
		$ref = null, //set some reference, it is ecpected to be unique!
		$vat = false, //add vat
		$vatAmount = null //override calcalated vat amount
	)
	{
		//retrieve vat data
		$vatObj = $this->getVatCodeForAccount($acc);
		$vat = is_int($vatAmount) ? $vatAmount : $amount * $vatObj->percentage / 100;

		$ref = $ref ? $ref : uniqid();
		$date = time();

		$lAmount = $aAmount = $amount;

		//so, if vat is a liability we should post the same amount to assert and visa versa
		$vatAcc = $this->getAccount($vatObj->account);
		if ($vatAcc->type == self::ASSET) {
			$lAmount = $amount + $vat;
		} elseif ($vatAcc->type == self::LIBALITY) {
			$aAmount = $amount + $vat;
		}

        $transaction = new \model\finance\accounting\DaybookTransaction(array(
            'referenceText' => $ref,
            'date' => $date,
            'approved' => true,
            'postings' => array(
                array(
                    'amount' => $amount,
                    'positive' => true,
                    'account' => $acc,
                ),
                array(
                    'amount' => $vat,
                    'positive' => true,
                    'account' => $vatObj->account,
                ),
                array(
                    'amount' => $lAmount,
                    'positive' => true,
                    'account' => $liabilityAccount,
                ),
                array(
                    'amount' => $aAmount,
                    'positive' => true,
                    'account' => $assertAccount,
                )
            )
        ));

		$this->addDaybookTransaction($transaction);
	}

	/**** aux function adding functionality and transforming objects ****/

	/**
	 * calculates and adds postings for VAT.
	 *
	 * @param $dbTrans \model\finance\accounting\DaybookTransaction
	 * @param $liabilityAccount int account to reflect salesVat
	 * @param $assetAccount int account to reflect bourght vat
	 */
	function vatCalculate(\model\finance\accounting\DaybookTransaction $dbTrans,
	                      $liabilityAccount, $assetAccount){

		//contains vat amount, and the vat object:
		//  'amount' th
		$vatCodes = array();

		//go through all postings and fetch VAT codes
		foreach($dbTrans->postings as $post){
			$vat = isset($post->overrideVat) ? $this->getVatCode($post->overrideVat) : $this->getVatCodeForAccount($post->account);
			//check that there is an vat object
			if(is_null($vat))
				continue;
			//cannot post negative values to expense accounts
			if(!$post->positive)
				throw new \exception\UserException(__('A posting cannot be negative'));

			//initialize if not set
			if(!isset($vatCodes[$vat->code])){
				$vatCodes[$vat->code]['amount'] = 0;
				$vatCodes[$vat->code]['obj'] = $vat;
			}

			//increment amount taking percentage in account
			$vatCodes[$vat->code]['amount'] += $post->amount * ($vat->percentage / 100);
		}

		//add postings for vatcodes
		$counterSales = new \model\finance\accounting\Posting();
		$counterBought = new \model\finance\accounting\Posting();
		$counterSales->amount = 0;
		$counterBought->amount = 0;

		$postings = array();

		foreach($vatCodes as $entry){
			if(!isset($postings[$entry['obj']->code])){
				$postings[$entry['obj']->code] = new \model\finance\accounting\Posting();
				$postings[$entry['obj']->code]->account = $vat->account;
				$postings[$entry['obj']->code]->positive = true;
				$postings[$entry['obj']->code]->amount = 0;
			}
			$postings[$entry['obj']->code]->amount =
				$postings[$entry['obj']->code]->amount + $entry['amount'];

			if($entry['obj']->type == 1){//sales vat
				$counterBought->amount = $counterBought->amount + $entry['amount'];
			}
			else{//bought vat
				$counterSales->amount = $counterSales->amount + $entry['amount'];
			}
		}

		//adding to some counter account for sales vat
		if($counterSales->amount > 0){

			$counterSales->account = $assetAccount;
			$counterSales->positive = false;
			$dbTrans->postings->counterSales = $counterSales;
		}

		//adding to some counter account for bought vat
		if($counterBought->amount > 0){
			$counterBought->account = $assetAccount;//$liabilityAccount;
			$counterBought->positive = false;
			$dbTrans->postings->counterBought = $counterBought;
		}

		//adding the rest of the postings
		foreach($postings as $name =>$p)
			$dbTrans->postings->$name = $p;

		//var_dump($dbTrans->toArray());

		return $dbTrans;
	}

	/**
	 * takes a daybookTransaction, and calculates the balance accounts (liability and asset) from the
	 * operation accounts (be aware, that no balance accounts are reset, so if some accounts
	 * are filled, duplication may accour)
	 *
	 * only two postings are added, one for the liability account and one for the asset account
	 *
	 * not validation is performed, as it will fail to insert is not used properly
	 *
	 * @param \model\finance\accounting\DaybookTransaction $dbTrans
	 * @param $liabilityAccount
	 * @param $assetAccount
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function balanceCalculate(\model\finance\accounting\DaybookTransaction $dbTrans,
	                          $liabilityAccount, $assetAccount){

		$liability = new \model\finance\accounting\Posting(array(
			'account' => $liabilityAccount,
			'amount' => 0,
			'positive' => true,
		));
		$asset = new \model\finance\accounting\Posting(array(
			'account' => $assetAccount,
			'amount' => 0,
			'positive' => true,
		));

		foreach($dbTrans->postings as $posting){
			$account = $this->getAccount($posting->account);
			//make sure we use an income or expense account

			if($account->type == 3) { //expense, we withdraw
				$asset->amount = $asset->amount - $posting->amount;
				$liability->amount = $liability->amount - $posting->amount;
			}
			elseif( $account->type == 4 ) { //income we add
				$asset->amount = $asset->amount + $posting->amount;
				$liability->amount = $liability->amount + $posting->amount;
			}
		}

		if($liability->amount < 0){
			$liability->amount = $liability->amount * -1;
			$liability->positive = false;
			$asset->amount = $asset->amount * -1;
			$asset->positive = false;
		}

		if($liability->amount != 0) {
			$dbTrans->postings->liabilityPosting = $liability;
			$dbTrans->postings->assetPosting = $asset;
		}

		return $dbTrans;
	}

	/**
	 * commits changes to the database
	 *
	 * this will return false, if assets and liabilities not equaĺs up to 0
	 */
	function commit()
	{

		if ($this->balance != 0)
			throw new \exception\UserException(__('assets and libilities should equal up to 0. The difference is %s', abs($this->balance)));

		if (!is_string($this->accounting))
			throw new \Exception('Accounting is not set properly');

        if(empty($this->transactionInfo))
            throw new \Exception('no transaction info set');

		$pdo = $this->db->dbh;
		$pdo->beginTransaction();
        $sthTrans = $pdo->prepare($this->queries->insertTransaction());
        $sthTrans->execute(array(
            'date' => $this->transactionInfo->date,
            'referenceText' => $this->transactionInfo->referenceText,
            'approved' => $this->transactionInfo->approved,
            'accounting_id' => $this->accounting,
        ));

        $transID = $pdo->lastInsertId();

        $sth = $pdo->prepare($this->queries->insertPosting());

        if(!$sth){
            $pdo->rollback();
            throw new \Exception('Some error happended? ' . implode($pdo->errorInfo()));
        }

        foreach ($this->transactions as $t) {
            $sth->execute( array(
                'amount_in' => $t['value_positive'],
                'amount_out' => $t['value_negative'],
                'grp' => $this->grp,
                'transaction_id' => $transID,
                'account' => $t['account']));
        }
        //commit the rows, if everything wen't well
        if ($pdo->commit())
            return true;
        else
            $pdo->rollback();
	}

	/**** VAT ****/
	function createVatCode($vatCode)
	{
		if (is_null($this->grp))
			throw new \Exception('Action not possible, insufficient permissions');

		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('INSERT INTO `accounting_vat_codes`
			(`grp_id`, `vat_code`, `name`, `type`, `percentage`, `account`, `counter_account`, `ubl_taxCatagory`)
			VALUES
			(?, ?, ?, ?, ?, ?, ?, ?);');

		if (!is_array($vatCode))
			$vatCode = array($vatCode);
		foreach ($vatCode as $v)
			if (!$sth->execute(array(
				$this->grp,
				$v->code,
				$v->name,
				$v->type,
				$v->percentage,
				$v->account,
				$v->counterAccount,
				$v->taxcatagoryID,))
			) {
				if (DEBUG)
					throw new \Exception(var_dump($sth->errorInfo()));
				throw new \Exception('account code is already used:');
			}
		return true;
	}

	/**
	 * returns all vatcodes
	 */
	function getVatCodes()
	{
		$pdo = $this->db->dbh;

		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE grp_id = ' . $this->grp);

		$ret = array();
		$sth->execute(array($this->accounting));

		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\VatCode(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['vat_code'],
				'type' => $t['type'],
				'account' => $t['account'],
				'counterAccount' => $t['counter_account'],
				'net' => $t['netto'],
				'taxcatagoryID' => $t['ubl_taxCatagory']
			));
		}
		return $ret;
	}

	/**
	 * returns a single vatcode object
	 *
	 * @return \model\finance\accounting\VatCode
	 */
	function getVatCode($code)
	{
		$pdo = $this->db->dbh;

		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE vat_code = ? AND grp_id = ?');

		$ret = array();
		$sth->execute(array($code, $this->grp));

		foreach ($sth->fetchAll() as $t) {
			return new \model\finance\accounting\VatCode(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['vat_code'],
				'type' => $t['type'],
				'account' => $t['account'],
				'percentage' => $t['percentage'],
				'counterAccount' => $t['counter_account'],
				'net' => $t['netto'],
				'taxcatagoryID' => $t['ubl_taxCatagory']
			));
		}
		return false;
	}

	/**
	 * returns a single vatcode object based on accountnumber
	 *
	 * @return \model\finance\accounting\VatCode
	 */
	function getVatCodeForAccount($acc)
	{
		$acc = $this->getAccount($acc);
		return isset($acc->vatCode) ? $this->getVatCode($acc->vatCode) : null;
	}

	/**
	 * returns vat code by type
	 */
	function getVatCodeByType($type)
	{
		$pdo = $this->db->dbh;

		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE type = ? AND grp_id = ?');

		$ret = array();
		$sth->execute(array($type, $this->grp));

		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\VatCode(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['vat_code'],
				'type' => $t['type'],
				'account' => $t['account'],
				'percentage' => $t['percentage'],
				'counterAccount' => $t['counter_account'],
				'net' => $t['netto'],
				'taxcatagoryID' => $t['ubl_taxCatagory']
			));
		}
		return $ret;
	}

	/**
	 * transfer money from vat account to a holder account
	 */
	function resetVatAccounting($holderAccount){
        $pdo = $this->db->dbh;


        $accounts = array();
        //get all accounts for vat, and their amounts
        $vats = $this->getVatCodes();
        foreach($vats as $v)
            $accounts[$v->account] = true;
        $accounts = array_keys($accounts);

        //add new amounts to the accounts, so they'll 0 up
        $accounts = $this->getAccounts(0, $accounts);


        //add the differences to the holderAccount

	}

	/**
	 * reset the vat holder account and and some asset payable account
	 */
	function vatPayed($payedFrom){

	}

	/**** ACCOUNTS ****/

	/**
	 * returns some account objects
	 *
	 * @param $flags int, binary representation of flags. see constants, used for quereing
	 * @param $account array, array of accounts search is limited to
	 * @param $globalTotal bool if true, accounting will not be used, and all posts ever are summed up
	 */
	function getAccounts($flags = 0, $accounts = array())
	{
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->getAllAccounts($this->grp, $flags, $accounts));

		//var_dump($sth);

		$ret = array();
		$sth->execute(array($this->accounting));
		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\Account(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['code'],
				'vatCode' => $t['vat'],
				'type' => $t['type'],
				'allowPayments' => $t['flags'] & self::PAYABLE == self::PAYABLE ? true : false,
				'isEquity' => $t['flags'] & self::EQUITY == self::EQUITY ? true : false,
				'income' => $t['amount_in'] ? $t['amount_in'] : 0,
				'outgoing' => $t['amount_out'] ? $t['amount_out'] : 0
			));
		}

		return $ret;
	}

	function getAccount($id)
	{
		$accounts = $this->getAccounts(0, array($id));

		if(count($accounts) < 1)
			throw new \exception\UserException(__('Account %s doesn\'t exist.', $id));
		return $accounts[0];
	}

	/**
	 * add a new account to the system
	 *
	 * @param $account a single or array of account objects
	 * @return bool
	 * @throws \exception\UserException
	 * @throws \exception\PermissionException
	 */
	function createAccount($account)
	{
		//validation
		if ($account->type < 1 || $account->type > 4)
			throw new \exception\UserException(__('Account type "%s" is not valid', $account->type));

		if (is_null($this->grp))
			throw new \exception\PermissionException('Insufficient permissions for creating account');

		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->insertAccount());

		if (!is_array($account))
			$account = array($account);

		foreach ($account as $a) {
			$flag = 0;
			$flag = $a->allowPayments ? $flag | self::PAYABLE : $flag;
			$flag = $a->isEquity ? $flag | self::EQUITY : $flag;
			if (!$sth->execute(array(
				'grp_id' => $this->grp,
				'code' => $a->code,
				'dfa' => $a->defaultReflection,
				'name' => $a->name,
				'type' => $a->type,
				'vat' => $a->vatCode,
				'flags' => $flag,))
			) {
				throw new \exception\UserException(__('Unable to create account: %s', $a->code));
			}
		}
		return true;
	}

	/**
	 * try to delete an account
	 *
	 * @param $id string
	 */
	function deleteAccount($id)
	{
		if (count($this->getTransactionsAccount($id, 0, 1, false)) > 0)
			throw new \exception\UserException(__('account %s cannot be deleted, as there is associated postings.'));

		if (is_null($this->grp))
			throw new \exception\PermissionException('Insufficient permissions for deleting account');


		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->deleteAccount());

		//get grp id

		$sth->execute(array('code' => $id, 'grp_id' => $this->grp));
		return true;
	}

	/** TRANSACTION FUNCTIONS **/

	/**
	 * get transactions
     *
     * @return array of \model\finance\accounting\DaybookTransaction
	 */
	function getTransactions($start = 0, $num = 1000)
	{
        $pdo = $this->db->dbh;
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$sth = $pdo->prepare($this->queries->getTransactions());
		$ret = array();
		if(!$sth)
            throw new  \Exception('Unable to perform query: ' . implode('; ', $pdo->errorInfo()));

        $sth->execute(array(
            'accounting' => $this->accounting,
            'start' => $start,
            'num' => $num
        ));

		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\DaybookTransaction(array(
                'referenceText' => $t['reference'],
                'date' => $t['date'],
                'approved' => $t['approved'],
                '_id' => $t['id']
            ));
            //@TODO fetch postings for the transaction
		}
		return $ret;
	}

	/**
	 * returns transactions for an account
	 *
	 * @param $accCode
	 * @param int $start
	 * @param int $num
	 * @param bool whether to limit to current accounting
	 *
	 * //TODO refactor so that getTransactions and this use same code
	 */
	function getTransactionsAccount($accCode, $start = 0, $num = 1000, $accountinggLimit=true)
	{
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('SELECT * FROM ' . self::TRANST . ' WHERE
			account_id = ? AND account = ?
			ORDER BY date DESC LIMIT ' . $start . ', ' . $num . '');
		$ret = array();
		$sth->execute(array($this->accounting, $accCode));
		foreach ($sth->fetchAll() as $t) {
			$obj = array(
				'_id' => $t['id'],
				'v_positive' => $t['v_income'],
				'v_negative' => $t['v_outgoing'],
				'account' => $t['account'],
				'date' => $t['date'],
				'approved' => $t['approved'],
				'ref' => $t['ref']
			);

			if ($obj['v_positive']) {
				$obj['value'] = $obj['v_positive'];
				$obj['positive'] = true;
				unset($obj['v_positive'], $obj['v_negative']);
			} else {
				$obj['value'] = $obj['v_negative'];
				$obj['positive'] = false;
				unset($obj['v_positive'], $obj['v_negative']);
			}

			$ret[] = new \model\finance\accounting\Transaction($obj);
		}

		return $ret;
	}

	/**
	 * get transactions from ref
	 */
	function getTransactionsRef($ref)
	{

	}

	/**** RAPPORTS ****/
	function getVatStatement()
	{
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->getSumsOnVatAccounts());
		if(!$sth->execute(array('accounting' => $this->accounting, 'grp' => $this->grp)))
            throw new \Exception("Was not abl to execute query.");

		$ret = new \model\finance\accounting\VatStatement;

		foreach ($sth->fetchAll() as $r) {
			switch ($r['type']) {
				case 1:
					$ret->sales = $r['amount_in'] - $r['amount_out'];
					break;
				case 2:
					$ret->bought = $r['amount_in'] - $r['amount_out'];
					break;
			}
		}

		$ret->total = $ret->sales - $ret->bought;

		return $ret;
	}
}

?>
