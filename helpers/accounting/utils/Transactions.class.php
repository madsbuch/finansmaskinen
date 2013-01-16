<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 7:19 PM
 */

namespace helper\accounting\utils;

class Transactions
{

	/**
	 * holder for various variables
	 */
	private $accounting;
	private $grp;
	private $db;
	private $queries;
	private $controller;

	/**
	 * the object server
	 *
	 * @var \helper\accounting\ObjectServer
	 */
	private $srv;


	/**
	 * array of transactions, when inserting multiple
	 *
	 * @var array
	 */
	private $transactions = array();

	function __construct(\helper\accounting\ObjectServer $srv){
		$this->srv = $srv;

		//todo remove dependencies on following
		$this->accounting = $srv->accounting;
		$this->grp = $srv->grp;
		$this->db = $srv->db;
		$this->queries = $srv->queries;
		$this->controller = $srv->controller;


	}

	/**** SETTERS ****/

    /**
     * takes an transaction object, and insert to the accounting
     *
     * this method auto commits
     *
     * @param \model\finance\accounting\DaybookTransaction $transaction
     * @throws \exception\UserException
     * @throws \Exception
     * @internal param bool $autocommit
     * @internal param bool $autocommit
     */
    function insertTransaction(\model\finance\accounting\DaybookTransaction $transaction){

	    $this->addTransaction($transaction);
	    $this->commit();

		/*//throw an exception if anything is wrong
		$this->validateTransaction($transaction);
		$pdo = $this->db->dbh;
		$pdo->beginTransaction();


		//insert the transaction
		$sthTrans = $pdo->prepare($this->queries->insertTransaction());
		$sthTrans->execute(array(
			'date' => $transaction->date,
			'referenceText' => $transaction->referenceText,
			'approved' => $transaction->approved,
			'accounting_id' => $this->accounting,
		));

		//get transaction id for the postings
		$transID = $pdo->lastInsertId();

		$sth = $pdo->prepare($this->queries->insertPosting());
		//stop if error
		if(!$sth){
			$pdo->rollback();
			throw new \Exception('Some error happended? ' . implode($pdo->errorInfo()));
		}

		//insert all the postings
		foreach ($transaction->postings as $p) {
			$sth->execute( array(
				'amount_in' => $p->positive ? $p->amount : 0,
				'amount_out' => $p->positive ? 0 : $p->amount,
				'grp' => $this->grp,
				'transaction_id' => $transID,
				'account' => $p->account));
		}
		//commit the rows, if everything wen't well
		if (!$pdo->commit()){
			$pdo->rollback();
			throw new \exception\UserException(__('Insertion of transaction did not succeed'));
		}*/
	}

    /**
     * adds a transaction to queue, for insertion with commit
     *
     * @param \model\finance\accounting\DaybookTransaction $t
     */
    function addTransaction(\model\finance\accounting\DaybookTransaction $t){
		$this->transactions[] = $t;
    }

    /**
     * commits all queued transactions
     */
    function commit(){
	    $pdo = $this->db->dbh;
	    $pdo->beginTransaction();

	    try{
		    //take all transactions
		    foreach($this->transactions as $t){
			    $this->validateTransaction($t);

			    //insert the transaction
			    $sthTrans = $pdo->prepare($this->queries->insertTransaction());
			    $sthTrans->execute(array(
				    'date' => $t->date,
				    'referenceText' => $t->referenceText,
				    'approved' => $t->approved,
				    'accounting_id' => $this->accounting,
			    ));

			    //get transaction id for the postings
			    $transID = $pdo->lastInsertId();

			    $sth = $pdo->prepare($this->queries->insertPosting());
			    //stop if error
			    if(!$sth){
				    $pdo->rollback();
				    throw new \Exception('Some error happended? ' . implode($pdo->errorInfo()));
			    }

			    //insert all the postings
			    foreach ($t->postings as $p) {
				    $sth->execute( array(
					    'amount_in' => $p->positive ? $p->amount : 0,
					    'amount_out' => $p->positive ? 0 : $p->amount,
					    'grp' => $this->grp,
					    'transaction_id' => $transID,
					    'account' => $p->account));
			    }

		    }
	    }catch(\Exception $e){
		    $pdo->rollback();
		    throw $e;
	    }

	    //commit the rows, if everything wen't well
	    if (!$pdo->commit()){
		    $pdo->rollback();
		    throw new \exception\UserException(__('Insertion of transaction did not succeed'));
	    }

    }

	/**
	 * approves already inserted transaction
	 *
	 * @param $transactionID
	 */
	function approveTransaction($transactionID){

	}

	/**
	 * offsets a transaction by inserting a new one with opposite postings
	 */
	function transactionOffset(){

	}

	/**** GETTERS ****/

	/**
	 * returns collection
	 *
	 * @param $start
	 * @param $num
	 * @param array $order
	 * @param array $search
	 * @throws \Exception
	 * @return array
	 * @internal param $limit
	 */
	function getTransactions($start, $num, $order = array(), $search = array()){
		$pdo = $this->db->dbh;

		//following is because emulation of prepares recognizes limit :start, :num as strings, which makes an syntax error
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
	 * returns transaction by transactionID
	 *
	 * @param $id
	 */
	function getTransactionByID($id){

	}

	function getTransactionByRef($ref){
        null;
	}

	/**** private functions ****/

	/**
	 * validates a transaction object, and throws an error, if it does not pass.
	 *
	 * @param \model\finance\accounting\DaybookTransaction $transaction
	 * @throws \exception\UserException
	 */
	private function validateTransaction(\model\finance\accounting\DaybookTransaction  $transaction){
		//validate the datastructures
		if(($errs = $transaction->validate($transaction::STRICT)))
			throw new \exception\UserException(__('Transaction was not validated: %s', implode(', ', $errs)));

		if(!isset($transaction->postings))
			throw new \exception\UserException(__('Transaction must have at least one posting'));

		if(!strtotime($transaction->date))
			throw new \exception\UserException(__('Date was not validated, was: "%s"', $transaction->date));


		//test that the different postings add correctly up
		$balance = 0;
		foreach($transaction->postings as $posting){
			if($posting->amount <= 0)
				throw new \exception\UserException(__('Posting amount should be more than 0, amount was %s', $posting->amount));

			//get the account of the posting
			$acc = $this->controller->accounts()->getAccountByCode($posting->account);

			if ($acc->type == $this->controller->ASSET)
				$balance += $posting->positive ? $posting->amount : -1 * $posting->amount;
			elseif ($acc->type == $this->srv->controller->LIABILITY)
				$balance -= $posting->positive ? $posting->amount : -1 * $posting->amount;
		}
		if($balance != 0)
			throw new \exception\UserException(__('assets and liabilities should equal up to 0. The difference is %s', abs($balance)));

		if(!is_null($this->getTransactionByRef($transaction->referenceText)))
			throw new \exception\UserException(__('ReferenceText, %s,  already exist in this accounting.', $transaction->referenceText));

	}

    private function addTransactionNoCommit($pdo, $queries){

    }

	/**
	 * creates obfuscated id
	 *
	 * @param $num
	 */
	private function encodeID($num){

	}

	/**
	 * deobfuscatetes a string to id.
	 *
	 * @param $id
	 */
	private function decodeID($id){

	}

}
