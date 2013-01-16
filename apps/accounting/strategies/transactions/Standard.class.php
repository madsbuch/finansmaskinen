<?php
/**
 * User: Mads Buch
 * Date: 1/16/13
 * Time: 1:58 AM
 */

namespace app\accounting\strategies\transactions;

/**
 * this class is a pure wrapper class for normal transaction inserts
 */
class Standard implements Transactions
{

	private $transaction;

	/**
	 * sets data to be processed
	 *
	 * @param $input
	 * @param $accountingHelper
	 * @return void
	 */
	function setData($input, $accountingHelper, $options)
	{
		$this->transaction = $input;
	}

	/**
	 * Takes some input data, and returns a transaction object
	 *
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function getDaybookTransaction()
	{
		$t = $this->transaction;
		$this->transaction = null;
		return $t;
	}

	/**
	 * returns whether there is more transactions
	 *
	 * if a source creates more than a single transaction, we might wanna insert it all
	 *
	 * @return bool
	 */
	function hasMore()
	{
		return !is_null($this->transaction);
	}
}
