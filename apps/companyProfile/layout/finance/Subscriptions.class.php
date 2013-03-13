<?php
/**
 * User: Mads Buch
 * Date: 3/13/13
 * Time: 11:21 AM
 */

namespace app\companyProfile\layout\finance;

class Subscriptions extends \helper\layout\LayoutBlock
{

	/**
	 * subsriptions
	 *
	 * @var
	 */
	private $subs;

	function __construct($subscriptions){
		parent::__construct();
		$this->subs = $subscriptions;
	}

	function generate()
	{

	}
}
