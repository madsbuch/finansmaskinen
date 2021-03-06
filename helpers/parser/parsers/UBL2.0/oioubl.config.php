<?php
/**
* configuration file for OIOUBL (the danish subset of UBL)
*/
namespace helper_parser\ubl\Config;


class oioubl{
	/*************************** SPECIFICATIONS *******************************/
	
	/**
	* Namespaces:
	*/
	public static $xmlns= array(
		'invoice' => "urn:oasis:names:specification:ubl:schema:xsd:Invoice-2",
		
		'cac'  =>	"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2",
		'cbc'  =>	"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2",
		'ccts' =>	"urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2",
		'sdt'  =>	"urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2",
		'udt'  =>	"urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2",
		'xsi'  =>	"http://www.w3.org/2001/XMLSchema-instance",
		'schemaLocation' => "urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 UBL-Invoice-2.0.xsd"
	);
	
	
	/************************** CLASSES SCHEMES *******************************/
	
	//some constants:
	const MINOCCUR 	= 0;
	const MAXOCCUR 	= 1;
	const TYPE	 	= 2;
	const VALUE		= 3;
	
	/**
	* specification for 
	*/
	
	/**
	* elements for schemes
	*/
	public static $elements = array(
		'CustomerParty' => array(
		
		),
		
		
		'Invoice' => array(
			//type = array(minOccur, maxOccur, classname, value (null fir single
			//value, array for multiple)) (- = infinite)
			'UBLVersionID'					=> array(1, 1, 'Type\Identifier', null),
			'CustomizationID'				=> array(1, 1, 'Type\Identifier', null),
			'ProfileID'						=> array(1, 1, 'Type\Identifier', null),
			'ID'							=> array(1, 1, 'Type\Identifier', null),
			'CopyIndicator'					=> array(0, 1, 'Type\Identifier', null),
			'UUID'							=> array(0, 1, 'Type\Identifier', null),
			'IssueDate'						=> array(1, 1, 'Type\Date', null),
			'IssueTime'						=> array(0, 1, 'Type\Time', null),
			'InvoiceTypeCode'				=> array(1, 1, 'Type\Code', null),
			'Note'							=> array(0, -1, 'Type\Text', null),
			'TaxPointDate'					=> array(0, 1, 'Type\Date', null),
			'DocumentCurrencyCode'			=> array(1, 1, 'Type\Code', null),
			'TaxCurrencyCode'				=> array(0, 1, 'Type\Code', null),
			'PricingCurrencyCode'			=> array(0, 1, 'Type\Code', null),
			'PaymentCurrencyCode'			=> array(0, 1, 'Type\Code', null),
			'PaymentAlternativeCurrencyCode'=> array(0, 1, 'Type\Code', null),
			'AccountingCostCode'			=> array(0, 1, 'Type\Code', null),
			'AccountingCost'				=> array(0, 1, 'Type\Text', null),
			'LineCountNumeric'				=> array(0, 1, 'Type\Numeric', null),
			//subclasses => array(minOccur, maxOccur, class) //class == null, not validated
			'UBLExtensions'					=> array(0, 1, 'UBLExtensions', null),
			'InvoicePeriod'					=> array(0, 1, 'Period', null),
			'OrderReference'				=> array(0, 1, 'OrderReference', null),
			'BillingReference'				=> array(0, -1, 'BillingReference', array()),
			'DespatchDocumentReference'		=> array(0, -1, 'DocumentReference', array()),
			'ReceiptDocumentReference'		=> array(0, -1, 'DocumentReference', array()),
			'OriginatorDocumentReference'	=> array(0, -1, 'DocumentReference', array()),
			'ContractDocumentReference'		=> array(0, 1, 'DocumentReference', null),
			'AdditionalDocumentReference'	=> array(0, -1, 'DocumentReference', array()),
			'Signature'						=> array(0, -1, 'Signature', array()),
			'AccountingSupplierParty'		=> array(1, 1, 'SupplierParty', null),
			'AccountingCustomerParty'		=> array(1, 1, 'CustomerParty', null),
			'PayeeParty'					=> array(0, 1, 'Party', null),
			'BuyerCustomerParty'			=> array(0, 1, 'CustomerParty', null),
			'SellerSupplierParty '			=> array(0, 1, 'SupplierParty', null),
			'Delivery'						=> array(0, -1, 'Delivery', array()),
			'DeliveryTerms'					=> array(0, 1, 'DeliveryTerms', null),
			'PaymentMeans'					=> array(0, -1, 'PaymentMeans', array()),
			'PaymentTerms'					=> array(0, -1, 'PaymentTerms', array()),
			'PrepaidPayment	 '				=> array(0, -1, 'Payment', array()),
			'AllowanceCharge'				=> array(0, -1, 'AllowanceCharge', array()),
			'TaxExchangeRate'				=> array(0, 1, 'ExchangeRate', null),
			'PricingExchangeRate'			=> array(0, 1, 'ExchangeRate', null),
			'PaymentExchangeRate'			=> array(0, 1, 'ExchangeRate', null),
			'PaymentAlternativeExchangeRate'=> array(0, 1, 'ExchangeRate', null),
			'TaxTotal'						=> array(1, -1, 'TaxTotal', array()),
			'LegalMonetaryTotal'			=> array(1, 1, 'MonetaryTotal', null),
			'InvoiceLine'					=> array(1, -1, 'InvoiceLine', array())
		),
		
		'InvoiceLine' => array(
			'ID'					=> array(1, 1, 'Type\Identifier', null),
			'UUID'					=> array(0, 1, 'Type\Identifier', null),
			'Note'					=> array(0, 1, 'Type\Text', null),
			'InvoicedQuantity'		=> array(1, 1, 'Type\Quantity', null),
			'LineExtensionAmount'	=> array(1, 1, 'Type\Amount', null),
			'TaxPointDate'			=> array(0, 1, 'Type\Date', null),
			'AccountingCostCode'	=> array(0, 1, 'Type\Code', null),
			'AccountingCos'			=> array(0, 1, 'Type\Text', null),
			'FreeOfChargeIndicator'	=> array(0, 1, 'Type\Indicator', null),
			
			'OrderLineReference'	=> null,
			'DespatchLineReference'	=> null,
			'ReceiptLineReference'	=> null,
			'BillingReference'		=> null,
			'PricingReference'		=> null,
			'DocumentReference'		=> null,
			'OriginatorParty'		=> null,
			'Delivery'				=> null,
			'AllowanceCharge'		=> null,
			'TaxTotal'				=> null,
			'Item'					=> null,
			'Price'					=> null
		),
		
		'MonetaryTotal' => array(),
		
		'Party' => array(
			'WebsiteURI'					=> array(0, 1, 'Type\Identifier', null),
			'LogoReferenceID'				=> array(0, 1, 'Type\Identifier', null),
			'EndpointID'					=> array(1, 1, 'Type\Identifier', null),

			'PartyIdentification'			=> array(0, -1, 'PartyIdentification', array()),
			'PartyName'						=> array(0, -1, 'PartyName', array()),
			'Language'						=> array(0, 1, 'Language', null),
			'PostalAddress'					=> array(0, 1, 'Address', null),
			'PhysicalLocation'				=> array(0, 1, 'Location', null),
			'PartyTaxScheme'				=> array(0, -1, 'PartyTaxScheme', array()),
			'PartyLegalEntity'				=> array(1, 1, 'PartyLegalEntity', null),
			'Contact'						=> array(0, 1, 'Contact', null),
			'Person'						=> array(0, 1, 'Person', null),
		),
		
		'SupplierParty' => array(
			//'CustomerAssignedAccountID'		=> array(0, 1, 'Type\Identifier', null),
			//'AdditionalAccountID'			=> array(0, -1, 'Type\Identifier', null),

		//	'Party'							=> array(1, 1, 'Party', null),
		//	'DespatchContact'				=> array(0, 1, 'Contact', null),
		//	'AccountingContact'				=> array(0, 1, 'Contact', null),
		//	'SellerContact'					=> array(0, 1, 'Contact', null),
		),
		
		'TaxTotal' => array(
		
		)
	);
	
	
	/**
	* needed elements for schemes
	*/
	
	/**
	* subclass types (for now: for future use)
	*/
	
	/*********************** TYPE SPECIFICATIONS ******************************/


	/**************************** DEFAULT VALUES ******************************/
	public static $default = array(
		//output format
		'customizationID'	=> 'OIOUBL-2.02',
		'ublVersion'		=> '2.02',
		'ProfileID' 		=> array(
			'schemeAgencyID' => '320',
			'schemeID' => 'urn:oioubl:id:profileid-1.2',
			'content' => 'Procurement-OrdSimR-BilSim-1.0'
		),
		
		//language settings
		'currency'			=> 'DKK',
		'legalScheme'		=> 'DK:CVR',
		'VAT'				=> 0.25,
	);
	
}

?>
