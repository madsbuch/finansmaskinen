<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

/**
 * Class Price
 *
 *
 * @package model\ext\ubl2
 */
class Price extends \model\AbstractModel{
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'PriceAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'BaseQuantity' => 		array('\model\ext\ubl2\field\Quantity', false),
		'PriceChangeReason' => 	array('\model\ext\ubl2\field\Text', false),
		'PriceTypeCode' => 		array('\model\ext\ubl2\field\Code', false),
		'PriceType' => 			array('\model\ext\ubl2\field\Text', false),
		'OrderableUnitFactorRate' => array('\model\ext\ubl2\field\Rate', false),
		
		'ValidityPeriod' => 	array('\model\ext\ubl2\Period', false),
		'PriceList' => 	array('\model\ext\ubl2\PriceList', false),
		'sAllowanceCharge' => 	array('\model\ext\ubl2\AllowanceCharge', false),
	);
	
	protected $PriceAmount;//	PrisBeløb	Amount	Ja	1
	protected $BaseQuantity;//	BeregningsGrundlagMængde	Quantity	Ja	0..1
	protected $PriceChangeReason;//	PrisÅrsag	Text	Aftales	0..n
	protected $PriceTypeCode;//	PrisTypeKode	Code	Ja	0..1
	protected $PriceType;//	PrisTypeBeskrivelse	Text	Ja	0..1
	protected $OrderableUnitFactorRate;//	OrdreAntalMængdeRate	Rate	Ja	0..1

	protected $ValidityPeriod;//	GyldighedsPeriode	Aftales	0..1	Bibliotek, 3.78	 
	protected $PriceList;//	Prisliste	Aftales	0..1	Bibliotek, 3.82	 
	protected $sAllowanceCharge;//	RabatGebyr	Ja
}

?>
