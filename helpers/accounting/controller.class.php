<?php
/**
* the accounting app uses a lot og MySQL, this is very big, and therefor, 
* this class was made for abstraction
*
* no multigroup on accounte... :/ bah
*/


namespace helper;

class accounting{
	
	private $accounting;
	private $grp;
	
	const ATABLE = 'accounting_accounts';
	const TRANST = 'accounting_transactions';
	
	//transactionable accounts
	const ASSET = 1;
	const LIBALITY = 2;
	const EXPENSES = 3;
	const INCOME = 4;
	
	//some administrative accounts
	const HEADING = 11; //just a heading
	const INTERVAL = 13;//interval of sums from other accounts, a single accounts
						//may be choosed, then it is used as a sum from account
	
	//flags for account, using bitmask
	const PAYABLE = 1;
	const EQUITY  = 2;
	
	/**
	* this variable is incremented and decremented every time a transactions i added
	*
	* on commit this have to be 0.
	*/
	protected $balance;
	
	/**
	* transactions that are ready to be applied to database.
	*/
	private $transactions = array();
	
	private $db;
	
	/**
	* instanciate with details of the group and the accounting
	*
	* @param $accounting	the id of the accounting related to
	* @param $grp			the groups the account are related to
	*/
	function __construct($accounting, $grp=null){
		$this->accounting = $accounting;
		$core = new core('accounting');
		
		$this->grp = $grp ? $grp : (int) $core->getMainGroup();
		
		$this->db = $core->getDB();
		
		$this->accCheck = $this->db->dbh->prepare('SELECT * FROM '.self::ATABLE.'
			WHERE grp_id = '.$this->grp.' AND account_id = ?;');
		$this->refCheck = $this->db->dbh->prepare('SELECT * FROM '.self::TRANST.'
			WHERE ref = ? AND account_id = \''.$accounting.'\';');
	}
	
	
	/**
	* add transaction
	* 
	* takes on transaction
	* returns false if ref already exists in the database.
	*/
	function addTransaction($transaction){
		//some validation
		if($transaction->value <= 0)
			throw new \Exception('Value of transaction has to be > 0');
		
		if(!isset($transaction->date))
			throw new \Exception('No transaction date set');
		
		//get account type:
		$this->accCheck->execute(array($transaction->account));
		$rArr = $this->accCheck->fetchAll();
		$rArr = $rArr[0];
		
		if(empty($rArr))
			throw new \Exception(__('Account "%s" does not exist.',$transaction->account) );
		
		//check if refere already exists
		$this->refCheck->execute(array($transaction->ref));
		$refArr = $this->refCheck->fetchAll();
		
		if(!empty($refArr))
			throw new \Exception('Ref already exist in this accounting.');
		
		$transaction = $transaction->toArray();
		
		//set the values
		if($transaction['positive']){
			$transaction['value_positive'] = $transaction['value'];
			$transaction['value_negative'] = 0;
		}
		else{
			$transaction['value_positive'] = 0;
			$transaction['value_negative'] = $transaction['value'];
		}
		
		
		//set some stats to check validity of all transactions
		if($rArr['type'] == self::ASSET)
			$this->balance += $transaction['value'];
		elseif($rArr['type'] == self::LIBALITY)
			$this->balance -= $transaction['value'];
		
		$this->transactions[] = $transaction;
	}
	
	/**
	* automatic add of transactions for a standard double accounting registration
	*
	* @param $amount	amount to insert to account
	* @param $acc		the actual account
	* ...
	* @param $vat		whether to add $vat (only if an vataccount is ass. with acc)
	* @param $vatAmount	Override calculation of vat, and use the specific amount 
	*/
	function automatedTransaction(
		$amount,			//amount to insert, exl vat
		$acc,				//operating account
		$libilityAccount,	//libility account
		$assertAccount,		//assert account
		$ref = null,		//set some reference, it is ecpected to be unique!	
		$vat = false,		//add vat
		$vatAmount = null	//override calcalated vat amount
		){
		//retrieve vat data
		$vatObj = $this->getVatCodeForAccount($acc);
		$vat = is_int($vatAmount) ? $vatAmount : $amount * $vatObj->percentage / 100;
		
		$ref = $ref ? $ref : uniqid();
		$date = time();
		
		$lAmount = $aAmount = $amount;
		
		//so, if vat is a liability we should post the same amount to assert and visa versa
		$vatAcc = $this->getAccount($vatObj->account);
		if($vatAcc->type == self::ASSET){
			$lAmount = $amount + $vat;
		}
		elseif($vatAcc->type == self::LIBALITY){
			$aAmount = $amount + $vat;
		}
		
		
		$oTrans = new \model\finance\accounting\Transaction(array(
			'value' => $amount,
			'positive' => true,
			'account' => $acc,
			'ref' => $ref,
			'approved' => true,
			'date' => $date
		));
		$vatTrans = new \model\finance\accounting\Transaction(array(
			'value' => $vat,
			'positive' => true,
			'account' => $vatObj->account,
			'ref' => $ref,
			'approved' => true,
			'date' => $date
		));
		
		$liaTrans = new \model\finance\accounting\Transaction(array(
			'value' => $lAmount,
			'positive' => true,
			'account' => $libilityAccount,
			'ref' => $ref,
			'approved' => true,
			'date' => $date
		));
		$assTrans = new \model\finance\accounting\Transaction(array(
			'value' => $aAmount,
			'positive' => true,
			'account' => $assertAccount,
			'ref' => $ref,
			'approved' => true,
			'date' => $date
		));
		
		$this->addTransaction($oTrans);
		$this->addTransaction($vatTrans);
		$this->addTransaction($liaTrans);
		$this->addTransaction($assTrans);
	}
	
	/**
	* commits changes to the database
	*
	* this will return false, if assets and liabilities not equaĺs up to 0
	*/
	function commit(){
		
		if($this->balance != 0)
			throw new \Exception('assets and libilities should equal up to 0');
		
		if(!is_string($this->accounting))
			throw new \Exception('Accounting is not set properly');
		
		$pdo = $this->db->dbh;
		$pdo->beginTransaction();
		try {
			$sth = $pdo->prepare('INSERT INTO accounting_transactions
				(`account_id`, `v_income`, `v_outgoing`, `ref`, `date`, `approved`, `account`)
				VALUES
				(\''.$this->accounting.'\', ?, ?, ?, ?, ?, ?);');
			
			foreach($this->transactions as $t){
				if(!$sth->execute(
						array(
							$t['value_positive'],
							$t['value_negative'],
							$t['ref'],
							$t['date'],
							$t['approved'],
							$t['account']))){
					if(DEBUG){
						var_dump($sth->errorInfo());
						die();
					}
					$pdo->rollback();
					throw new \Exception('Some error happended?');
				}
			}
			//commit the rows, if everything wen't well
			if($pdo->commit())
				return true;
			else
				$pdo->rollback();
			return false;
		} catch (PDOException $e) {
			$pdo->rollback();
			return false;
		}
	}
	
	/**** VAT CODES ***********************************************************/
	
	/**
	* resets the vat system
	*/
	function resetVat(){
		
	}
	
	function createVatCode($vatCode){
		if(is_null($this->grp))
			throw new \Exception('Action not possible, insufficient permissions');
		
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('INSERT INTO `accounting_vat_codes`
			(`grp_id`, `vat_code`, `name`, `type`, `percentage`, `account`, `counter_account`, `ubl_taxCatagory`)
			VALUES
			(?, ?, ?, ?, ?, ?, ?, ?);');
		
		if(!is_array($vatCode))
			$vatCode = array($vatCode);
		foreach($vatCode as $v)
			if(!$sth->execute(array(
				$this->grp,
				$v->code,
				$v->name,
				$v->type,
				$v->percentage,
				$v->account,
				$v->counterAccount,
				$v->taxcatagoryID,))){
				if(DEBUG)
					throw new \Exception(var_dump($sth->errorInfo()));
				throw new \Exception('account code is already used:');
			}
		return true;
	}
	
	/**
	* returns all vatcodes
	*/
	function getVatCodes(){
		$pdo = $this->db->dbh;
		
		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE grp_id = '. $this->grp);
		
		$ret = array();
		$sth->execute(array($this->accounting));
		
		foreach($sth->fetchAll() as $t){
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
	* returns a single vatcode
	*/
	function getVatCode($code){
		$pdo = $this->db->dbh;
		
		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE vat_code = ? AND grp_id = ?');
		
		$ret = array();
		$sth->execute(array($code, $this->grp));
		
		foreach($sth->fetchAll() as $t){
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
	}
	
	/**
	* returns a single vatcode based on accountnumber
	*/
	function getVatCodeForAccount($acc){
		$acc = $this->getAccount($acc);
		return isset($acc->vatCode) ? $this->getVatCode($acc->vatCode) : null;
	}
	
	/**
	* returns vat code by type
	*/
	function getVatCodeByType($type){
		$pdo = $this->db->dbh;
		
		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE type = ? AND grp_id = ?');
		
		$ret = array();
		$sth->execute(array($type, $this->grp));
		
		foreach($sth->fetchAll() as $t){
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
	
	/**** ACCOUNTS ************************************************************/
	
	/**
	* returns some account objects
	*
	* @param $flags, binary representation of flags. see constants
	*/
	function getAccounts($flags = 0){
		$pdo = $this->db->dbh;
		$add = ' AND flags & '.$flags.' = '.$flags.'';
		
		//$sth = $pdo->prepare('SELECT * FROM '.self::ATABLE.' WHERE grp_id = '. $this->grp . $add);
		
		$sth = $pdo->prepare(' SELECT acc.id, acc.name, acc.account_id, acc.vat,
			acc.type, acc.flags, acc.grp_id,
			SUM(t.v_income) as income,
			SUM(t.v_outgoing) as outgoing
			FROM accounting_accounts as acc LEFT JOIN accounting_transactions as t
			ON (t.account = acc.account_id AND t.account_id = ?)
			WHERE acc.grp_id = '. $this->grp . $add . '
			GROUP BY acc.account_id');
		
		$ret = array();
		$sth->execute(array($this->accounting));
		foreach($sth->fetchAll() as $t){
			$ret[] = new \model\finance\accounting\Account(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['account_id'],
				'vatCode' => $t['vat'],
				'type' => $t['type'],
				'allowPayments' => $t['flags'] & self::PAYABLE == self::PAYABLE ? true : false,
				'isEquity' => $t['flags'] & self::EQUITY == self::EQUITY ? true : false,
				'income' => $t['income'],
				'outgoing' => $t['outgoing']
			));
		}
		
		return $ret;
	}
	
	function getAccount($id){
		$id = (int) $id;
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('SELECT * FROM '.self::ATABLE.' WHERE 
			grp_id = '. $this->grp . ' AND account_id = ?');
			
		$sth->execute(array($id));
		foreach($sth->fetchAll() as $t){
			return new \model\finance\accounting\Account(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['account_id'],
				'vatCode' => $t['vat'],
				'type' => $t['type'],
				'allowPayments' => $t['flags'] & self::PAYABLE == self::PAYABLE ? true : false,
				'isEquity' => $t['flags'] & self::EQUITY == self::EQUITY ? true : false,
				
				//'approved' => $t['approved'],
				//'ref' => $t['ref']
			));
		}
		return null;
	}
	
	function createAccount($account){
		//validation
		if($account->type < 1 || $account->type > 4)
			throw new \Exception('type is not valid');
		
		if(is_null($this->grp))
			throw new \Exception('Action not possible, insufficient permissions');
		
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('INSERT INTO accounting_accounts
			(`grp_id`, `account_id`, `default_reflection_account`, `name`, `type`, `vat`, `flags`)
			VALUES
			(?, ?, ?, ?, ?, ?, ?);');
		
		if(!is_array($account))
			$account = array($account);
		foreach($account as $a){
			$flag = 0;
			$flag = $a->allowPayments ? $flag | self::PAYABLE : $flag;
			$flag = $a->isEquity ? $flag | self::EQUITY : $flag;
			if(!$sth->execute(array(
				$this->grp,
				$a->code,
				$a->defaultReflection,
				$a->name,
				$a->type,
				$a->vatCode,
				$flag,))){
				if(DEBUG)
					throw new \Exception(var_dump($sth->errorInfo()));
				throw new \Exception('account code is already used:');
			}
		}
		return true;
	}
	
	/**
	* get transactions
	*/
	function getTransactions($start = 0, $num = 1000, $compress=false){
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('SELECT * FROM '.self::TRANST.' WHERE account_id = ?
			ORDER BY date DESC LIMIT '.$start.', '.$num.'');
		$ret = array();
		$sth->execute(array($this->accounting));
		foreach($sth->fetchAll() as $t){
			$obj = array(
				'_id' => $t['id'],
				'v_positive' => $t['v_income'],
				'v_negative' => $t['v_outgoing'],
				'account' => $t['account'],
				'date' => $t['date'],
				'approved' => $t['approved'],
				'ref' => $t['ref']
			);
			
			if($obj['v_positive']){
				$obj['value'] = $obj['v_positive'];
				$obj['positive'] = true;
				unset($obj['v_positive'], $obj['v_negative']);
			}
			else{
				$obj['value'] = $obj['v_negative'];
				$obj['positive'] = false;
				unset($obj['v_positive'], $obj['v_negative']);
			}
			
			$ret[] = new \model\finance\accounting\Transaction($obj);
		}
		
		return $ret;
	}
	
	/**
	* get transactions from account
	*/
	function getTransactionsAccount($ref, $start = 0, $num = 1000){
	
	}
	
	/**
	* get transactions from ref
	*/
	function getTransactionsRef($ref){
	
	}
	
	/**** RAPPORTS ****/
	function getVatStatement(){
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare('
		Select
			SUM(v_income) as v_income,
			SUM(v_outgoing) as v_outgoing,
			vc.type as type
		from
			accounting_vat_codes as vc,
			accounting_transactions as t 
		WHERE
			vc.grp_id = :grp AND
			t.account_id = :accounting AND
			t.account = vc.account
			
		GROUP BY
			vc.type;');
		$sth->execute(array('accounting' => $this->accounting, 'grp' => $this->grp));
		
		$ret = new \model\finance\accounting\VatStatement;
		
		$total = 0;
		foreach($sth->fetchAll() as $r){
			switch($r['type']){
				case 1:
					$ret->sales = $r['v_income'] - $r['v_outgoing'];
					$total += $ret->sales ;
				break;
				case 2:
					$ret->bought = $r['v_income'] - $r['v_outgoing'];
					$total -= $ret->bought ;
				break;
			}
		}
		
		$ret->total = $total;
		
		return $ret;
	}
}

?>
