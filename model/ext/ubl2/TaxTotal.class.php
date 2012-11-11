<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class TaxTotal extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'TaxAmount' => array('\model\ext\ubl2\field\Amount', false),
		'RoundingAmount' => array('\model\ext\ubl2\field\Amount', false),
		'TaxEvidenceIndicator' => array('\model\ext\ubl2\field\Indicator', false),


		'TaxSubtotal' => array('\model\ext\ubl2\TaxSubtotal', false),
	);
	
	protected $TaxAmount;//	AfgiftsBeløb	Amount	Ja	1
	protected $RoundingAmount;//	Afrundingsbeløb	Amount	Ja	0..1
	protected $TaxEvidenceIndicator;//	AfgiftsGyldighedIndikator	Indicator	Aftales	0..1

	protected $TaxSubtotal;//
}

?>
