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
	private $core;
	private $accounting;
	private $grp;
	private $db;
	private $queries;

	function __construct($accounting, $grp, $db, $queries){
		$this->accounting = $accounting;
		$this->grp = $grp;
		$this->db = $db;
		$this->queries = $queries;

	}

	/**** SETTERS ****/

	function insertTransaction(){

	}

	function approveTransaction(){

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
	 * @param $limit
	 */
	function getTransactions($start, $num, $order = array(), $search = array()){
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
	 * returns transaction by transactionID
	 *
	 * @param $id
	 */
	function getTransactionByID($id){

	}

	/**** private functions ****/

	/**
	 * creates obfuscated id
	 *
	 * @param $num
	 */
	function encodeID($num){

	}

	/**
	 * deobfuscatetes a string to id.
	 *
	 * @param $id
	 */
	function decodeID($id){

	}

}
