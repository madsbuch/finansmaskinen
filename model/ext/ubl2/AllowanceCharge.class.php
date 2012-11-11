<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class Address extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'ID' => array('\model\ext\ubl2\field\Identifier', false),
		'ChargeIndicator' => array('\model\ext\ubl2\field\Identifier', false),
		'AllowanceChargeReasonCode' => array('\model\ext\ubl2\field\Code', false),
		'AllowanceChargeReason' => array('\model\ext\ubl2\field\Text', false),
		'MultiplierFactorNumeric' => array('\model\ext\ubl2\field\Numeric', false),
		'PrepaidIndicator' => array('\model\ext\ubl2\field\Indicator', false),
		'SequenceNumeric' => array('\model\ext\ubl2\field\Numeric', false),
		'Amount' => array('\model\ext\ubl2\field\Amount', false),
		'BaseAmount' => array('\model\ext\ubl2\field\Amount', false),
		'AccountingCostCode' => array('\model\ext\ubl2\field\Code', false),
		'AccountingCost' => array('\model\ext\ubl2\field\Text', false),
		
		'TaxCategory' => array('\model\ext\ubl2\TaxCategory', false),
	);
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $Line;//	Adresse Punkt	Klasse
	protected $ID;//	ID	Identifier	Ja	0..1
	protected $ChargeIndicator;//	GebyrKategoriIndikator	Indicator	Ja	1
	protected $AllowanceChargeReasonCode;//	Årsagskode	Code	Ja	0..1
	protected $AllowanceChargeReason;//	Årsag	Text	Ja	0..1
	protected $MultiplierFactorNumeric;//	GebyrKategoriKvantitet	Numeric	Ja	0..1
	protected $PrepaidIndicator;//	ForudbetaltIndikator	Indicator	Aftales	0..1
	protected $SequenceNumeric;//	BeregningsSekvensNummer	Numeric	Ja	0..1
	protected $Amount;//	Beløb	Amount	Ja	1
	protected $BaseAmount;//	BeregningsGrundlagBeløb	Amount	Ja	0..1
	protected $AccountingCostCode;//	KontoKode	Code	Aftales	0..1
	protected $AccountingCost;//
	
	protected $TaxCategory;
}

?>
