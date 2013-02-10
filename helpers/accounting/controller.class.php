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
	 * @var \helper\accounting\ObjectServer
	 */
	private $objSrv;

	/**
	 * @var \helper\accounting\Queries
	 */
	private $queries;

	const ATABLE = 'accounting_accounts';
	const TRANST = 'accounting_transactions';

	//transactionable accounts
	const ASSET = 1;
	const LIABILITY = 2;
	const EXPENSES = 3;
	const INCOME = 4;

	//some administrative accounts
	const HEADING = 11; //just a heading
	const INTERVAL = 13; //interval of sums from other accounts, a single accounts
	//may be choosed, then it is used as a sum from account

	//flags for account, using bitmask
	const PAYABLE = 1;
	const EQUITY = 2;

	/**** the future from fown here, we has to make it possible to
	                access from object ref ****/

	//transactionable accounts
	public $ASSET = 1;
	public $LIABILITY = 2;
	public $EXPENSES = 3;
	public $INCOME = 4;

	//some administrative accounts
	public $HEADING = 11; //just a heading
	public $INTERVAL = 13; //interval of sums from other accounts, a single accounts
	//may be choosed, then it is used as a sum from account

	//flags for account, using bitmask
	public $PAYABLE = 1;
	public $EQUITY = 2;

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
	 * objects to hold the utils
	 */
	private $transactionUtil;
	private $accountsUtil;
	private $vatUtil;
	private $postingUtil;

	/**
	 * instanciate with details of the group and the accounting
	 *
	 * @param $accounting string    the id of the accounting related to
	 * @param $grp int            the group the account are related to
	 */
	function __construct($accounting, $grp = null)
	{

		$this->accounting = (string) $accounting;
		$core = new core('accounting');

		$this->queries = new accounting\MysqlQueries();

		$this->grp = $grp ? $grp : (int)$core->getMainGroup();

		$this->db = $core->getDB();


		$this->objSrv = new \helper\accounting\ObjectServer();
		$this->objSrv->controller = $this;
		$this->objSrv->db = $this->db;
		$this->objSrv->accounting = $this->accounting;
		$this->objSrv->grp = $this->grp;
		$this->objSrv->queries = $this->queries;

		$this->accCheck = $this->db->dbh->prepare('SELECT * FROM ' . self::ATABLE . '
			WHERE grp_id = ' . $this->grp . ' AND code = ?;');

	}

	/**** access to classes, that handles the stuff ****/

	/**
	 * access transactions subsystem
	 *
	 * @return accounting\utils\Transactions
	 */
	public function transaction(){
		if(!isset($this->transactionUtil))
			$this->transactionUtil = new \helper\accounting\utils\Transactions($this->objSrv);
		return $this->transactionUtil;
	}

	/**
	 * @return accounting\utils\Accounts
	 */
	public function accounts(){
		if(!isset($this->accountsUtil))
			$this->accountsUtil = new \helper\accounting\utils\Accounts($this->objSrv);
		return $this->accountsUtil;
	}

	/**
	 * @return accounting\utils\Vat
	 */
	public function vat(){
		if(!isset($this->vatUtil))
			$this->vatUtil = new \helper\accounting\utils\Vat($this->objSrv);
		return $this->vatUtil;
	}

	/**
	 * @return accounting\utils\Postings
	 */
	public function postings(){
		if(!isset($this->postingUtil))
			$this->postingUtil = new accounting\utils\Postings($this->objSrv);
		return $this->postingUtil;
	}

	/**** different add functions ****/

	/**
	 * add transaction
	 *
	 * takes on transaction
	 * returns false if ref already exists in the database.
	 *
	 * @deprecated see insertTransaction in the transaction util
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
		elseif ($rArr['type'] == self::LIABILITY)
			$this->balance -= $transaction['positive'] ? $transaction['value'] : -1 * $transaction['value'];

		$this->transactions[] = $transaction;
	}

	/**** daybook transactions ****/

	/**
	 * adds all postings from daybooktransaction object
	 *
	 * @param $transaction \model\finance\accounting\DaybookTransaction
	 *
	 * @throws \exception\UserException
	 * @return void
	 * @deprecated moved to transaction util
	 */
	function addDaybookTransaction(\model\finance\accounting\DaybookTransaction $transaction){
        return $this->transaction()->insertTransaction($transaction);
	}

	/**** helper methods ****/

    /**
     * automatic add of transactions for a standard double accounting registration
     *
     * @param $amount    amount to insert to account
     * @param $acc        the actual account
     * ...
     * @param $liabilityAccount
     * @param $assertAccount
     * @param null $ref
     * @param bool| $vat whether to add $vat (only if an vataccount is ass. with acc)
     * @param $vatAmount    Override calculation of vat, and use the specific amount
     * @return \model\finance\accounting\DaybookTransaction
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
		$date = date('c');

		$lAmount = $aAmount = $amount;

		//so, if vat is a liability we should post the same amount to assert and visa versa
		$vatAcc = $this->getAccount($vatObj->account);
		if ($vatAcc->type == self::ASSET) {
			$lAmount = $amount + $vat;
		} elseif ($vatAcc->type == self::LIABILITY) {
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

		return $transaction;
	}

	/**** aux function adding functionality and transforming objects ****/

	/**
	 * calculates and adds postings for VAT.
	 *
	 * @param $dbTrans \model\finance\accounting\DaybookTransaction
	 * @param $liabilityAccount int account to reflect salesVat
	 * @param $assetAccount int account to reflect bourght vat
	 * @throws \exception\UserException
	 * @return \model\finance\accounting\DaybookTransaction
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
	 * takes an transaction (mergee) and merges them into main
	 *
	 * @param \model\finance\accounting\DaybookTransaction $main
	 * @param \model\finance\accounting\DaybookTransaction $mergee
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function mergeTransactions(\model\finance\accounting\DaybookTransaction $main, \model\finance\accounting\DaybookTransaction $mergee){
		if(!isset($main->postings))
			$main->postings = array();

		$index = 100;//stort from 100 to avoid to many collisions

		if(isset($mergee->postings)){
			//go through all mergee's transaction
			foreach($mergee->postings as $p){
				//run untill we find a free spot
				while(true){
					$index++;
					if(!isset($main->postings->$index)){
						$main->postings->$index = $p;
						break;
					}
				}
			}
		}
		return $main;
	}

	/**
	 * commits changes to the database
	 *
	 * this will return false, if assets and liabilities not equaĺs up to 0
	 *
	 * @deprecated moved to insertTransaction in transaction util
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
	 *
	 * @deprecated
	 */
	function getVatCodes()
	{
		return $this->vat()->getVatCodes();
	}

	/**
	 * returns a single vatcode object
	 *
	 * @param $code
	 * @return \model\finance\accounting\VatCode
	 */
	function getVatCode($code)
	{
		return $this->vat()->getVatCode($code);
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
     * @deprecated
	 */
	function getVatCodeByType($type)
	{
        return $this->vat()->getVatByType($type);
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

	/**** ACCOUNTS ****/

	/**
	 * @deprecated
	 */
	function getAccounts($flags = 0, $accounts = array())
	{
		return $this->accounts()->getAccounts($flags, $accounts);
	}

	/**
	 * @deprecated
	 */
	function getAccount($id)
	{
		return $this->accounts()->getAccountByCode($id);
	}

	/**
	 * @deprecated use the one in util
	 */
	function createAccount($account)
	{
		return $this->accounts()->createAccount($account);
	}

	/**
	 * @deprecated moved to utils/Accounts
	 */
	function deleteAccount($id)
	{
		return $this->accounts()->deleteAccount($id);
	}

	/** TRANSACTION FUNCTIONS **/

	/**
	 * @deprecated use those functions in util/Transactions
	 */
	function getTransactions($start = 0, $num = 1000)
	{
        return $this->transaction()->getTransactions($start, $num);
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
     * @return array
     * @deprecated, it doesn't make sense to get transactions for account, maybe postings
	 */
	function getTransactionsAccount($accCode, $start = 0, $num = 1000, $accountinggLimit=true)
	{
		throw new \Exception('deprecated');
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

	/**** RAPPORTS ****/

	/**
	 * dispatch the call to a specific report strategy
	 */
	function report($report){
		$className = '\helper\accounting\reports\\' . $report;
		$object = new $className($this->objSrv);
		return $object->generateReport();
	}

	/**
	 * @return \model\finance\accounting\VatStatement
	 * @throws \Exception
	 * @deprecated use report(...) instead
	 */
	function getVatStatement()
	{
		return $this->report('DKVatSettlement');
	}
}

?>
