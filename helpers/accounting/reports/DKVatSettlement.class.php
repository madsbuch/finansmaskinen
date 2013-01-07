<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 1:15 AM
 *
 * generate an VAT settlement accoring to danish rules
 */

namespace helper\accounting\reports;

class DKVatSettlement implements Report
{

	/**
	 * used to retrieve data from accounting
	 *
	 * @var \helper\accounting
	 */
	private $accHelper;

	/**
	 * @param $accHelper \helper\accounting
	 */
	function __construct(\helper\accounting $accHelper){
		$this->accHelper = $accHelper;
	}


	/**
	 * generates some report object
	 *
	 * @return mixed
	 */
	function generateReport()
	{
		// TODO: Implement generateReport() method.
	}
}
