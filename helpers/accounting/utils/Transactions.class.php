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

        //take all transactions
        foreach($this->transactions as $t){
            if(!is_null($err = $this->nonTransactionalInsert($t, $pdo))){
                $pdo->rollBack();
                throw new \exception\UserException(__('Error in some transaction: %s', $err));
            }
        }

	    //commit the rows, if everything went well
	    if (!$pdo->commit()){
		    $pdo->rollBack();
		    throw new \exception\UserException(__('Insertion of transaction did not succeed'));
	    }
    }

    /**
     * inserts a transaction non transactional
     *
     * this should only be used, if transactions are handled elsewhere
     *
     * @param $t
     * @param $pdo a pro object to insert on
     * @throws \Exception
     * @return null|string null on success, error string on fail
     */
    function nonTransactionalInsert($t, \PDO $pdo){
        $this->validateTransaction($t);

        //insert the transaction
        $sthTrans = $pdo->prepare($this->queries->insertTransaction());

        if(!$sthTrans->execute(array(
            'date' => $t->date,
            'referenceText' => $t->referenceText,
            'approved' => $t->approved,
            'accounting_id' => $this->accounting,
        )))
            return 'error in transaction data';

        //get transaction id for the postings
        $transID = $pdo->lastInsertId();

        $sth = $pdo->prepare($this->queries->insertPosting());
        //stop if error
        if(!$sth){
            return 'error in insert posting query';
        }

        //insert all the postings
        foreach ($t->postings as $p) {
            if(!$sth->execute( array(
                'amount_in' => $p->positive ? $p->amount : 0,
                'amount_out' => $p->positive ? 0 : $p->amount,
                'grp' => $this->grp,
                'transaction_id' => $transID,
                'account' => $p->account)))
                return 'error in posting data';
        }

        return null;
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
	 * @return \model\finance\accounting\DaybookTransaction
	 * @throws \Exception
	 */
	function getTransactionByID($id){
		$pdo = $this->db->dbh;

		//trnsaction data
		$ret = null;
		//following is because emulation of prepares recognizes limit :start, :num as strings, which makes an syntax error
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$sth = $pdo->prepare($this->queries->getSingleTransaction());
		if(!$sth)
			throw new  \Exception('Unable to perform query: ' . implode('; ', $pdo->errorInfo()));

		$sth->execute(array(
			'accounting' => $this->accounting,
			'id' => $id,
		));

		foreach ($sth->fetchAll() as $t) {
			$ret = new \model\finance\accounting\DaybookTransaction(array(
				'referenceText' => $t['reference'],
				'date' => $t['date'],
				'approved' => $t['approved'],
				'_id' => $t['id']
			));
		}

		if(empty($ret->_id))
			throw new \exception\UserException(__('Transaction was not to find in this accounting'));

		//postings
		$ret->postings = $this->srv->controller->postings()->getPostingsForTransaction($ret->_id);

		return $ret;
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
					'_id'           => $t['id'],
					'referenceText' => $t['reference'],
					'postings'      => array(),
					'date'          => $t['date'],
					'approved'      => $t['approved']
				));
			}
		}

		if(is_null($transaction))
			throw new \exception\UserException(__('No transaction for reference "%s" in this accounting', $ref));

		$transaction->postings = $this->srv->controller->postings()->getPostingsForTransaction($transaction->_id);

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

        if(is_null($transaction->approved))
            throw new \exception\UserException(__('Approved must not be null'));

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
			throw new \exception\UserException(__('Balace does not add up to 0, difference was: %s', abs($balance)));

        //test for referencetext
		$throw = false;
		try{
			$this->getTransactionByRef($transaction->referenceText);
			$throw = true;
		}catch(\exception\UserException $e){}
		if($throw)
			throw new \exception\UserException(__('ReferenceText, %s,  already exist in this accounting.', $transaction->referenceText));
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
