<?php

namespace model\finance\accounting;

class Transaction extends \model\AbstractModel {
	
	
	/**
	* the mysql id
	*/
	protected $_id;
	
	/**
	* the value of transaction
	*
	* this is always positive, use $income to define positive or negative amounts
	* this is an integer. that means we are using lowest possible notation.
	* cent for dollars f.eks.
	*/
	protected $value;
	
	/**
	* whether we talk income, or outgoing.
	*
	* if true, $value is positive, if false, negative.
	*/
	protected $positive;
	
	/**
	* the virtual account, the transaction is posted to
	*
	* reference.
	*/
	protected $account;
	
	/**
	* refere
	*
	* if a bill, or invoice is posted, and have to be undone, counterpostages
	* can be done against the refere
	*/
	protected $ref;
	
	/**
	* the date of the transaction
	*/
	protected $date;
	
	/**
	* whether the transaction is approved
	*/
	protected $approved;
}

?>
