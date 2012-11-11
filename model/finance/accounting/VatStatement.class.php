<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance\accounting;

class VatStatement extends \model\AbstractModel{
	/**
	* version
	*/
	protected $_version = '1.0';
	protected $_model   = 'finance\VatStatement';
	
	//vat of sold items
	protected $sales;
	
	//international sales tax
	protected $internationalSales;
	
	//international sales with inverted duty
	protected $internationalSalesCounter;
	
	/**** deduction ****/
	protected $bought;
	
	/**** deductions that properly only apply to denmark... ****/
	protected $oil;
	protected $electricity;
	protected $naturalAndCityGas;
	protected $carbon;
	protected $co2;
	protected $water;
	
	
	//total to pay
	protected $total;


}

?>
