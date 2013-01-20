<?php
/**
 * User: Mads Buch
 * Date: 1/19/13
 * Time: 9:44 PM
 */

namespace app\accounting\layout\finance;

class ViewAccount extends \helper\layout\LayoutBlock
{
	private $account;

	function __construct($account){
		$this->account = $account;
	}


	function generate()
	{
		// TODO: Implement generate() method.
	}
}
