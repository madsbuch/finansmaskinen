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

	/**
	 * updated to new names
	 * @var string
	 */
	//protected $_version = 'v1';

	protected $_id;

	/**
	* make it easy to track products of a vertain cat
	*/
	protected $group;

	/**
	 * old stuff, was meant for a default payment account
	 *
	 * @var
	 */
	protected $accountAssert;
	
	/**
	* taxcatagories for creation of invoices
	*
	* those are populated from the vat accounts.
	*/
	protected $TaxCategoryInclVat;
	protected $TaxCategoryExclVat;


	/**
	 * storage account, an account that holds the asset while on storage, asset
	 * equity account the same amount is posted on
	 *
	 * income account  account salesprice is posted on when a unit is sold
	 * expense account account costprice is posted on when a unit is sold
	 *
	 * stockAccount - an account the total costprice is posted to upon buy
	 * equity account - an account the makes equity
	 *
	 * (deposit account - the value that is posted to upon sale)
	 *
	 * useStock
	 *
	 * a buy implies following:
	 *  stock account is incremented by the total costprice  if(useStock)
	 *  deposit VAT from expense account to vat account      if(useStock)
	 *  equity is incremented by the same.                   if(useStock)
	 *  deposit account is decremented by costprice
	 *  deposit to expense account                          if(!useStock)
	 *
	 *
	 * a sale implies following:
	 *  stock account should be decremented by total costprise of sold items    if(useStock)
	 *  deposit account should be incremented total salesprice
	 *  equity is adjusted
	 *  total cost is posted to expense account                                 if(useStock)
	 *  total sales is posted to income account
	 *
	 *
	 */
	protected $accountInclVat; //income account, to be refactored later
	protected $accountExclVat; //income account, to be refactored

	protected $expenseAccountInclVat;
	protected $expenseAccountExclVat;

	protected $accountLiability; //equity account, to be refactored

	protected $stockAccount;

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
