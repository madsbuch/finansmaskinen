<?php
/**
* a companyes public stuff
*
*/

namespace model\finance\company;

class PublicProperties extends \model\AbstractModel{
	protected $_autoassign = array(
		'Party' => array('\model\ext\ubl2\Party', false),
		'PaymentMeans' => array('\model\ext\ubl2\PaymentMeans', false)
	);
	
	protected $_version = '1.0';
	protected $_model   = 'finance\company\PublicProperties';
	
	/**
	* not the wild one, but the UBL one ;)
	*/
	protected $Party;
	
	/**
	* paymentmeans
	*
	* one default paymentmeans
	*/
	protected $PaymentMeans;
	
	/**
	* for creating invoices
	*
	* number of days till due date
	*/
	protected $dueDays;
}

?>
