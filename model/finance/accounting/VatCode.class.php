<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance\accounting;

class VatCode extends \model\AbstractModel{
	/**
	* version
	*/
	protected $_version = '1.0';
	protected $_model   = 'finance\VatCode';
	
	/**
	* 
	*/
	protected $code;
	
	
	/**
	* type of this vatcode
	* this is used for f.eks. calculating vat statement
	*
	* types:
	* 1: sales vat
	* 2: bought vat
	*
	*/
	protected $type;
	
	/**
	*
	*/
	protected $name;
	
	/**
	*
	*/
	protected $description;
	
	/**
	*
	*/
	protected $percentage;
	
	protected $account;
	
	protected $counterAccount;
	
	/**
	* whether the foundation of comsutation is netto or brutto.
	* for vat = 25%
	*
	* netto:  the vat was not added, vat = 25% of transaction value
	* brutto: the vat was added, vat = 20% of transaction value
	*
	*/
	protected $net;
	
	/**
	* ubl stuff
	*
	* the taxcatagory id is used to fetch the taxcatagory.
	*/
	protected $taxcatagoryID;

}

?>
