<?php
/**
* products are modelled in catagories
*
* a catagory hasan group associated, which makes it possible to only give access
* to a certain group of products.
*
* on the other hans, it makes it possible to put a lot of data, that the products
* share in this object
*
*/

namespace model\finance\products;

class Catagory extends \model\AbstractModel{
	protected $_id;
	
	/**
	* make it easy to track products of a vertain cat
	*/
	protected $group;

	/**** All the accounts ****/
	protected $accountInclVat; //account to bookkeep to, for invoice inkl vat
	protected $accountExclVat; // and excl vat

	/**
	* the balance accounts for this catagory
	*
	* the assert account is not filled by default.
	*
	* when an invoice is filled and sent, assert account is provided,
	* liability account is filled
	*/
	protected $accountAssert;
	protected $accountLiability;
	
	/**
	* taxcatagories for creation of invoices
	*
	* those are populated from the vat accounts.
	*/
	protected $TaxCategoryInclVat;
	protected $TaxCategoryExclVat;

	/**** Some other stuff ****/

	protected $name;

	protected $description;
	
	/**** for automation of transactions ****/
	
	//the accounting system has a way, to insert automaically, then this has to be filled
	
	//amount to this catagory, excl vat
	protected $amount;
	
	//whether to post to the vat accounts
	protected $vat;
	
	//is this needed when posting a bill? they do not use vat
	//it overrides the calculation og vat, and adds some absolute value.
	protected $vatAmount;
}


?>
