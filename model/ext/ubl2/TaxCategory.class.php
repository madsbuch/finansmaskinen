<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class TaxCategory extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'ID' => array('\model\ext\ubl2\field\Identifier', false),
		'Name' => array('\model\ext\ubl2\field\Name', false),
		'Percent' => array('\model\ext\ubl2\field\Percent', false),
		'BaseUnitMeasure' => array('\model\ext\ubl2\field\Measure', false),
		'PerUnitAmount' => array('\model\ext\ubl2\field\Amount', false),
		'TaxExemptionReasonCode' => array('\model\ext\ubl2\field\Code', false),
		'TaxExemptionReason' => array('\model\ext\ubl2\field\Text', false),
		//'TierRange'
		//'TierRatePercent'

		'TaxScheme' => array('\model\ext\ubl2\TaxScheme', false),
	);
	
	protected $ID;//	ID	Identifier	Ja	1
	protected $Name;//	Navn	Name	Ja	0..1
	protected $Percent;//	Procent	Percent	Ja	0..1
	protected $BaseUnitMeasure;//	BasisEnhed	Measure	Ja	0..1
	protected $PerUnitAmount;//	PerEnhedBeløb	Amount	Ja	0..1
	protected $TaxExemptionReasonCode;//	AfgiftUndtagelseÅrsagsKode AfgiftUndtagelseÅrsag	Code	Ja	0..1
	protected $TaxExemptionReason;//	AfgiftUndtagelseÅrsag
	protected $TierRange;//	LagRækkevide	Felt
	protected $TierRatePercent;//	LagRækkeviddeProcent

	protected $TaxScheme;//	AfgiftsSkema	Ja
	
	/**** Some preperation functions ****/
	
	
	/**** Functions updating model revisions ****/
}

?>
