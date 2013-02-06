<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 2/5/13
 * Time: 12:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\accounting\layout\finance\reports;

class BalanceStatement extends \helper\layout\LayoutBlock
{
	private $report;
	function __construct($statement){
		$this->report = $statement;
	}


	function generate()
	{
		return \helper\layout\Element::heading(__('Balance repport'), __('Money woop :D'));
	}
}
