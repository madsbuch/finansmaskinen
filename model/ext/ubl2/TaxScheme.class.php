<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class TaxScheme extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'ID' => array('\model\ext\ubl2\field\Identifier', false),
		'Name' => array('\model\ext\ubl2\field\Name', false),
		'TaxTypeCode' => array('\model\ext\ubl2\field\Code', false),
		'CurrencyCode' => array('\model\ext\ubl2\field\Code', false),

		
		'JurisdictionRegionAddress' => array('\model\ext\ubl2\field\Address', false),
	);
	
	protected $ID;//	ID	Identifier	Ja	1
	protected $Name;//	Navn	Name	Ja	0..1
	protected $TaxTypeCode;//	Procent	Percent	Ja	0..1
	protected $CurrencyCode;//	BasisEnhed	Measure	Ja	0..1

	protected $JurisdictionRegionAddress;//	AfgiftsSkema	Ja
}

?>
