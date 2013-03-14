<?php
/**
 * User: Mads Buch
 * Date: 1/16/13
 * Time: 1:43 AM
 */

namespace app\accounting\strategies\transactions;

/**
 * this returns a single dayBook transaction based on an array of category objects
 */
class ProductCategory implements Transactions
{

	private $categories;

	/**
	 * @var \helper\accounting
	 */
	private $ah;
	private $options;

	/**
	 * Takes some input data, and returns a transaction object
	 *
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function getDaybookTransaction()
	{
		$returnTransaction = new \model\finance\accounting\DaybookTransaction(array(
			'referenceText' => $this->options->referenceText,
			'date'          => date('c'),
            'approved'      => true,
		));
		foreach($this->categories as $cat){
			//if vat should be added
			$vat = isset($cat->vatAmount) ? false : true;

			//dispatch the the accounting helper
			$transaction = $this->ah->automatedTransaction(
				$cat->amount,
				($vat ? $cat->accountInclVat : $cat->accountExclVat),
				$cat->accountLiability,
				$cat->accountAssert,
				$this->options->referenceText,
				$vat,
				$cat->vatAmount
			);

			$returnTransaction = $this->ah->mergeTransactions($returnTransaction, $transaction);
		}

		$this->categories = null;

		return $returnTransaction;
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
		//returning if result is returned
		return !is_null($this->categories);
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
