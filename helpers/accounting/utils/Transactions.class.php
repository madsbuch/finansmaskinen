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
			    $this->nonTransactionalInsert($t, $pdo);
		    }
	    }catch(\Exception $e){
		    $pdo->rollback();
		    throw $e;
	    }

	    //commit the rows, if everything went well
	    if (!$pdo->commit()){
		    $pdo->rollback();
		    throw new \exception\UserException(__('Insertion of transaction did not succeed'));
	    }
    }

    /**
     * inserts a transaction non transactional
     *
     * this should only be used, if transactions are handled elsewhere
     *
     * @param $transaction the transaction to insert
     * @param $pdo a pro object to insert on
     */
    function nonTransactionalInsert($t, $pdo){
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

	/**
	 * @param $ref
	 * @param null $pdo instance of pdo object, this is used if the function is a part og a transaction
	 * @throws \exception\UserException
	 * @return array
	 */
	function getTransactionByRef($ref, $pdo = null){
		$pdo = isset($pdo) ? $pdo : $this->srv->db->dbh;

		$sth = $pdo->prepare($this->srv->queries->getTransactionByReference());

		$sth->execute(array(
			'referenceText' => $ref,
			'accounting_id' => $this->srv->accounting
		));

		$transaction = null;
		$i = 0;

		foreach ($sth->fetchAll() as $t) {
			if(is_null($transaction)){
				$transaction = new \model\finance\accounting\DaybookTransaction(array(
					'_id'           => $t['t_id'],
					'referenceText' => $t['t_reference'],
					'postings'      => array(),
					'date'          => $t['t_date'],
					'approved'      => $t['t_approved']
				));
			}

			$transaction->postings->$i = new \model\finance\accounting\Posting(array(
				'_id'           => $t['p_id'],
				'account'       => $t['p_account_id'],
				'amount'        => abs($t['p_amount_in'] - $t['p_amount_out']),
				'positive'      => $t['p_amount_in'] - $t['p_amount_out'] > 0,
				'description'   => ''
			));

			$i++;
		}

		if(is_null($transaction))
			throw new \exception\UserException(__('No transaction for reference: %s', $ref));

		return $transaction;
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
			throw new \exception\UserException(__('ReferenceText, %s,  already exist in this accounting.', $transaction->referenceText));

		$throw = false;
		try{
			$this->getTransactionByRef($transaction->referenceText);
			$throw= true;
		}catch(\exception\UserException $e){}
		if($throw)
			throw new \exception\UserException(__('The referencetext already exists in the accounting'));
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
