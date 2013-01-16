<?php
/**
 * User: Mads Buch
 * Date: 1/16/13
 * Time: 1:43 AM
 */

namespace app\accounting\strategies\transactions;

class ProductCategory implements Transactions
{

	private $categories;
	private $ah;
	private $options;

	/**
	 * Takes some input data, and returns a transaction object
	 *
	 * @internal param \app\accounting\strategies\transactions\the $input input data
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function getDaybookTransaction()
	{
		//shift an element
		$cat = array_shift($this->categories);

		//if vat should be added
		$vat = isset($cat->vatAmount) ? false : true;

		//dispatch the the accounting helper
		$transaction = $this->ah->automatedTransaction(
			$cat->amount,
			($vat ? $cat->accountInclVat : $cat->accountExclVat),
			$cat->accountLiability,
			$cat->accountAssert,
			$this->options['referenceText'],
			$vat,
			$cat->vatAmount
		);

		return $transaction;
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
		//every element is just shiftet, so when the list is empty, we don't have more.
		return !empty($this->categories);
	}

	/**
	 * sets data to be processed
	 *
	 * @param $input
	 * @param $accountingHelper
	 * @param $options
	 * @return void
	 */
	function setData($input, $accountingHelper, $options)
	{
		$this->categories = $input;
		$this->ah = $accountingHelper;
		$this->options = $options;
	}
}
