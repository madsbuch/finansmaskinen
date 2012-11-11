<?php
/**
* this files contains the classes
*/

namespace helper_parser\ubl;

class Invoice extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'invoice';
	function init(){
		//setting namespace used in this document
		$cnf = $this->config;
		$this->returnElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', $cnf::$xmlns['cbc']);
		$this->returnElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', $cnf::$xmlns['cac']);
		$this->returnElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ccts', $cnf::$xmlns['ccts']);
		$this->returnElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sdt', $cnf::$xmlns['sdt']);
		$this->returnElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:udt', $cnf::$xmlns['udt']);
		
		$cnf = $this->config;
		
		//setting default values
		$vid = $this->setField('UBLVersionID');
		$vid->setContent($cnf::$default['ublVersion']);
		
		$cid = $this->setField('CustomizationID');
		$cid->setContent($cnf::$default['customizationID']);
		
		$pid = $this->setField('ProfileID');
		$cid->setContent($cnf::$default['ProfileID']['content']);
		$cid->setAttribute('schemeAgencyID', $cnf::$default['ProfileID']['schemeAgencyID']);
		$cid->setAttribute('schemeID', $cnf::$default['ProfileID']['schemeID']);
		
		
		$issueDate = $this->setField('IssueDate');
		$issueDate->setDate(time());
		
		$typecode = $this->setField('InvoiceTypeCode');
		
		
		$currency = $this->setField('DocumentCurrencyCode');
		$currency->setContent($cnf::$default['currency']);
		
	}
}
class AllowanceCharge extends AbstractClass{
	protected $scheme = 'AllowanceCharge';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class BillingReference extends AbstractClass{
	protected $scheme = 'BillingReference';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
}
class Contact extends AbstractClass{
	protected $scheme = 'CustomerParty';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
}
class CustomerParty extends AbstractClass{
	protected $scheme = 'CustomerParty';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class Delivery extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class DeliveryTerms extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class DocumentReference extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class ExchangeRate extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class InvoiceLine extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class MonetaryTotal extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class OrderReference extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class Party extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class Payment extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class PaymentMeans extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class PaymentTerms extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class Period extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class Signature extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class SupplierParty extends AbstractClass{
	protected $scheme = 'SupplierParty';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class TaxTotal extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 
class UBLExtensions extends AbstractClass{
	protected $scheme = 'Invoice';
	protected $xmlns = 'cac';
	function init(){
		//setting default values
	}
} 

?>
