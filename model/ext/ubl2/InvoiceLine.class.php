<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class InvoiceLine extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $_autoassign = array(
		'ID' => 		array('\model\ext\ubl2\field\Identifier', false),
		'UUID' => 		array('\model\ext\ubl2\field\Identifier', false),
		'Note' => 		array('\model\ext\ubl2\field\Text', false),
		'InvoicedQuantity' => 	array('\model\ext\ubl2\field\Quantity', false),
		'LineExtensionAmount' =>array('\model\ext\ubl2\field\Amount', false),
		'TaxPointDate' => 		array('\model\ext\ubl2\field\Date', false),
		'AccountingCostCode' => array('\model\ext\ubl2\field\Code', false),
		'AccountingCost' => 	array('\model\ext\ubl2\field\Text', false),
		'FreeOfChargeIndicator' => array('\model\ext\ubl2\field\Indicator', false),
		
		'OrderLineReference' => array('\model\ext\ubl2\OrderLineReference', false),
		'DespatchLineReference' => array('\model\ext\ubl2\LineReference', false),
		'ReceiptLineReference' => array('\model\ext\ubl2\LineReference', false),
		'BillingReference' => array('\model\ext\ubl2\BillingReference', false),
		'PricingReference' => array('\model\ext\ubl2\PricingReference', false),
		'DocumentReference' => array('\model\ext\ubl2\DocumentReference', false),
		'OriginatorParty' => array('\model\ext\ubl2\Party', false),
		'Delivery' => array('\model\ext\ubl2\Delivery', false),
		'AllowanceCharge' => array('\model\ext\ubl2\AllowanceCharge', false),
		'TaxTotal' => array('\model\ext\ubl2\TaxTotal', false),
		'Item' => array('\model\ext\ubl2\Item', false),
		'Price' => array('\model\ext\ubl2\Price', false),
	);

	protected $ID;//	ID	Identifier	Ja	1
	protected $UUID;//	UniversalUnikID	Identifier	Aftales	0..1
	protected $Note;//	Note	Text	Ja	0..1
	protected $InvoicedQuantity;//	FaktureretMængde	Quantity	Ja	1
	protected $LineExtensionAmount;//	LinjeTotal	Amount	Ja	1
	protected $TaxPointDate;//	AfgiftsDato	Date	Aftales	0..1
	protected $AccountingCostCode;//	FinansKontoStrengKode	Code	Aftales	0..1
	protected $AccountingCost;//	FinansKontoStreng	Text	Ja	0..1
	protected $FreeOfChargeIndicator;//	GratisIndikator	Indicator	Ja	0..1


	//UBL-Navn	DK-Navn	Afløftes	Brug	Reference til printet dokumentation	Se i øvrigt
	protected $OrderLineReference;//	OrdreLinjeReference	Ja	0..1	3.19.1	 
	protected $DespatchLineReference;//	AfsendelsesLinjeReference	Aftales	0..n	Bibliotek, 3.58	 
	protected $ReceiptLineReference;//	ModtagelsesLinjeReference	Aftales	0..n	Bibliotek, 3.58	 
	protected $BillingReference;//	AfregningsReference	Aftales	0..n	3.19.2	 
	protected $PricingReference;//	PrisReference	Aftales	0..1	Bibliotek, 3.83	 
	protected $DocumentReference;//	DokumentReference	Ja	0..n	3.19.3	OIOUBL_GUIDE_DOKUMENTREF
	protected $OriginatorParty;//	InitierendePart	Aftales	0..1	Bibliotek, 3.70	 
	protected $Delivery;//	Levering	Ja	0..n	3.19.4	OIOUBL_GUIDE_LEVERING
	protected $AllowanceCharge;//	RabatGebyr	Ja	0..n	Bibliotek, 3.4	OIOUBL_GUIDE_RABAT
	protected $TaxTotal;//	AfgiftTotal	Ja	1..n	Bibliotek, 3.104	OIOUBL_GUIDE_SKAT
	protected $Item;//	Vare	Ja	1	3.19.5	 
	protected $Price;//	Pris	Ja	1	Bibliotek, 3.81	OIOUBL_GUIDE_PRISER


	//Navn	DK-Navn	Type
	protected $PaymentTerms;//	BetalingsBetingelser	Klasse
	protected $DeliveryTerms;//

}

?>
