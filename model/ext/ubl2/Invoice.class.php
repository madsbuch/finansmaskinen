<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class Invoice extends \model\AbstractModel{
	
	/**
	* important for creating XML
	*/
	protected $_tag = 'Invoice';
	protected $_fieldvarAsAttr = false;
	protected $_namespace = array('inv', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
	
	protected $_autoassign = array(
		'UBLVersionID' => 		array('\model\ext\ubl2\field\Identifier', false),
		'CustomizationID' => 	array('\model\ext\ubl2\field\Identifier', false),
		'ProfileID' => 			array('\model\ext\ubl2\field\Identifier', false),
		'ID' => 				array('\model\ext\ubl2\field\Identifier', false),
		'CopyIndicator' => 		array('\model\ext\ubl2\field\Identifier', false),
		'UUID' => 				array('\model\ext\ubl2\field\Identifier', false),
		'IssueDate' => 			array('\model\ext\ubl2\field\Date', false),
		'IssueTime' => 			array('\model\ext\ubl2\field\Time', false),
		'InvoiceTypeCode' => 	array('\model\ext\ubl2\field\Code', false),
		'Note' => 				array('\model\ext\ubl2\field\Text', false),
		'TaxPointDate' => 		array('\model\ext\ubl2\field\Date', false),
		'DocumentCurrencyCode' => array('\model\ext\ubl2\field\Code', false),
		'TaxCurrencyCode' => 	array('\model\ext\ubl2\field\Code', false),
		'PricingCurrencyCode' => array('\model\ext\ubl2\field\Code', false),
		'PaymentCurrencyCode' => array('\model\ext\ubl2\field\Code', false),
		'PaymentAlternativeCurrencyCode' => array('\model\ext\ubl2\field\Code', false),
		'AccountingCostCode' => array('\model\ext\ubl2\field\Code', false),
		'AccountingCost' => 	array('\model\ext\ubl2\field\Text', false),
		'LineCountNumeric' => 	array('\model\ext\ubl2\field\Numeric', false),
		
		
		'AccountingSupplierParty' => array('\model\ext\ubl2\SupplierParty', false),
		'AccountingCustomerParty' => array('\model\ext\ubl2\CustomerParty', false),
		'PayeeParty' => array('\model\ext\ubl2\Party', false),
		'BuyerCustomerParty' => array('\model\ext\ubl2\CustomerParty', false),
		'SellerSupplierParty' => array('\model\ext\ubl2\SupplierParty', false),
		'Delivery' => array('\model\ext\ubl2\Delivery', false),
		'DeliveryTerms' => array('\model\ext\ubl2\DeliveryTerms', false),
		'PaymentMeans' => array('\model\ext\ubl2\PaymentMeans', true),
		'PaymentTerms' => array('\model\ext\ubl2\PaymentTerms', true),
		'PrepaidPayment' => array('\model\ext\ubl2\Payment', true),
		'AllowanceCharge' => array('\model\ext\ubl2\AllowanceCharge', true),
		'TaxExchangeRate' => array('\model\ext\ubl2\ExchangeRate', false),
		'PricingExchangeRate' => array('\model\ext\ubl2\ExchangeRate', false),
		'PaymentExchangeRate' => array('\model\ext\ubl2\ExchangeRate', false),
		'PaymentAlternativeExchangeRate' => array('\model\ext\ubl2\ExchangeRate', false),
		'TaxTotal' => array('\model\ext\ubl2\TaxTotal', true),
		'LegalMonetaryTotal' => array('\model\ext\ubl2\MonetaryTotal', false),
		'InvoiceLine' => array('\model\ext\ubl2\InvoiceLine', true),
	);

	//UBL-Navn	DK-Navn	Datatype	Afløftes	Brug
	protected $UBLVersionID;//	UBLVersionID	Identifier	Ja	1
	protected $CustomizationID;//	SpecialtilpasningsID	Identifier	Ja	1
	protected $ProfileID;//	ProfilID	Identifier	Ja	1
	protected $ID;//	ID	Identifier	Ja	1
	protected $CopyIndicator;//	KopiIndikator	Indicator	Ja	0..1
	protected $UUID;//	UniversaltUnikID	Identifier	Ja	0..1
	protected $IssueDate;//	UdstedelsesDato	Date	Ja	1
	protected $IssueTime;//	UdstedelsesTid	Time	Aftales	0..1
	protected $InvoiceTypeCode;//	FakturaTypeKode	Code	Ja	0..1
	protected $Note;//	Note	Text	Ja	0..n
	protected $TaxPointDate;//	AfgiftsDato	Date	Aftales	0..1
	protected $DocumentCurrencyCode;//	DokumentValutaKode	Code	Ja	1
	protected $TaxCurrencyCode;//	AfgiftsValutaKode	Code	Ja	0..1
	protected $PricingCurrencyCode;//	PrisValutaKode	Code	Ja	0..1
	protected $PaymentCurrencyCode;//	BetalingsValutaKode	Code	Ja	0..1
	protected $PaymentAlternativeCurrencyCode;//	AlternativBetalingsValutaKode	Code	Ja	0..1
	protected $AccountingCostCode;//	FinansKontoNummerKode	Code	Aftales	0..1
	protected $AccountingCost;//	FinansKontoNummer	Text	Ja	0..1
	protected $LineCountNumeric;//	LinjeAntal	Numeric	Aftales	0..1

	//UBL-Navn	DK-Navn	Afløftes	Brug	Reference til printet dokumentation	Se i øvrigt
	protected $UBLExtensions;//	UBLExtensions	Aftales	0..1	 	OIOUBL_GUIDE_UDVIDELSER
	protected $InvoicePeriod;//	FakturaPeriode	Ja	0..1	Bibliotek, 3.78	 
	protected $OrderReference;//	OrdreReference	Ja	0..1	3.2	 
	protected $BillingReference;//	AfregningsReference	Ja	0..n	3.3	 
	protected $DespatchDocumentReference;//	AfsendelsesDokumentReference	Aftales	0..n	3.4	OIOUBL_GUIDE_DOKUMENTREF
	protected $ReceiptDocumentReference;//	ModtagelsesDokumentReference	Aftales	0..n	3.5	OIOUBL_GUIDE_DOKUMENTREF
	protected $OriginatorDocumentReference;//	InitierendeDokumentReference	Aftales	0..n	3.6	OIOUBL_GUIDE_DOKUMENTREF
	protected $ContractDocumentReference;//	KontraktDokumentReference	Ja	0..1	3.7	OIOUBL_GUIDE_DOKUMENTREF
	protected $AdditionalDocumentReference;//	SupplerendeDokumentReference	Ja	0..n	Bibliotek, 3.36	OIOUBL_GUIDE_DOKUMENTREF
	protected $Signature;//	Signatur	Ja	0..n	Bibliotek, 3.96	OIOUBL_GUIDE_SIGNATUR
	protected $AccountingSupplierParty;//	Kreditor	Ja	1	3.8	 
	protected $AccountingCustomerParty;//	Debitor	Ja	1	3.9	 
	protected $PayeeParty;//	Betalingsmodtager	Ja	0..1	3.10	OIOUBL_GUIDE_PART
	protected $BuyerCustomerParty;//	Køber	Ja	0..1	3.11	 
	protected $SellerSupplierParty;//	Sælger	Ja	0..1	3.12	 
	protected $Delivery;//	Levering	Ja	0..n	3.13	OIOUBL_GUIDE_LEVERING
	protected $DeliveryTerms;//	LeveringsBetingelser	Ja	0..1	3.14	OIOUBL_GUIDE_LEVBETING
	protected $PaymentMeans;//	BetalingsMåde	Ja	0..n	Bibliotek, 3.76	OIOUBL_GUIDE_BETALING
	protected $PaymentTerms;//	BetalingsBetingelser	Ja	0..n	Bibliotek, 3.77	OIOUBL_GUIDE_BETALING
	protected $PrepaidPayment;//	ForudBetaling	Ja	0..n	Bibliotek, 3.75	 
	protected $AllowanceCharge;//	RabatGebyr	Ja	0..n	Bibliotek, 3.4	OIOUBL_GUIDE_RABAT
	protected $TaxExchangeRate;//	AfgiftsVekselKurs	Ja	0..1	3.15	OIOUBL_GUIDE_VALUTA
	protected $PricingExchangeRate;//	PrisVekselKurs	Ja	0..1	3.16	OIOUBL_GUIDE_VALUTA
	protected $PaymentExchangeRate;//	BetalingsVekselKurs	Ja	0..1	3.17	OIOUBL_GUIDE_VALUTA
	protected $PaymentAlternativeExchangeRate;//	AlternativBetalingsVekselKurs	Ja	0..1	3.18	OIOUBL_GUIDE_VALUTA
	protected $TaxTotal;//	AfgiftsTotal	Ja	1..n	Bibliotek, 3.104	OIOUBL_GUIDE_SKAT
	protected $LegalMonetaryTotal;//	Total	Ja	1	Bibliotek, 3.64	OIOUBL_GUIDE_TOTALER
	protected $InvoiceLine;//	FakturaLinje	Ja	1..n	3.19	 

	//Navn	DK-Navn	Type
	protected $TaxRepresentativeParty;//
}

?>
