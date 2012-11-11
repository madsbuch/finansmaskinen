<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class TaxSubtotal extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'TaxableAmount' => array('\model\ext\ubl2\field\Amount', false),
		'TaxAmount' => array('\model\ext\ubl2\field\Amount', false),
		'CalculationSequenceNumeric' => array('\model\ext\ubl2\field\Numeric', false),
		'TransactionCurrencyTaxAmount' => array('\model\ext\ubl2\field\Amount', false),

		'TaxCategory' => array('\model\ext\ubl2\TaxCategory', false),
	);
	
	protected $TaxableAmount;//	AfgiftsGrundlag	Amount	Ja	1
	protected $TaxAmount;//	AfgiftsBeløb	Amount	Ja	1
	protected $CalculationSequenceNumeric;//	BeregningsRækkefølgeNumerisk	Numeric	Ja	0..1
	protected $TransactionCurrencyTaxAmount;//	TransaktionsValutaAfgiftsBeløb	Amount	Ja	0..1

	protected $TaxCategory;//	AfgiftsKategori	Ja	1	Bibliotek, 3.101	 
}

?>
