<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance;

class Bill extends \model\AbstractModel{
	protected $_autoassign = array(
		'Invoice' => array('\model\ext\ubl2\Invoice', false),
	);
	
	/**
	* version
	*/
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model   = 'model\finance\Bill';
	
	/**
	* internal ID and subsystem for lodo
	*/
	protected $_id;
	protected $_subsystem;
	
	protected $_external = array();
	
	/**
	* sender of this bill
	*/
	protected $contactID;
	
	/**
	 * productdetails
	 *
     * This includes:
	 *
	 */
	protected $products;
	
	/**
	* instance of
	*
	* everything that logically goes in here, goes in here.
	*/
	protected $Invoice;

	
	/**
	* whether it is finalized
	*/
	protected $draft;
	
	/**
	* whether the bill is payed
	*/
	protected $isPayed;
	
	/**
	* the accounting this bill is posted to. null if none.
	* if this values is null, the bill is not accounted
	*/
	protected $accounting;
	
	/**
	* ref from the accounting
	*/
	protected $ref;
}

?>
