<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class MonetaryTotal extends \model\AbstractModel{
	
	/**
	* important for creating XML
	*/
	protected $_fieldvarAsAttr = false;
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'LineExtensionAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'TaxExclusiveAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'TaxInclusiveAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'AllowanceTotalAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'ChargeTotalAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'PrepaidAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'PayableRoundingAmount' => 		array('\model\ext\ubl2\field\Amount', false),
		'PayableAmount' => 		array('\model\ext\ubl2\field\Amount', false),
	);
	
	protected $LineExtensionAmount;//	LinjeTotal	Amount	Ja	1
	protected $TaxExclusiveAmount;//	AfgiftTotalEksklusivBeløb	Amount	Ja	0..1
	protected $TaxInclusiveAmount;//	AfgiftTotalInklusivBeløb	Amount	Ja	0..1
	protected $AllowanceTotalAmount;//	RabatTotal	Amount	Ja	0..1
	protected $ChargeTotalAmount;//	GebyrTotal	Amount	Ja	0..1
	protected $PrepaidAmount;//	ForudbetaltBeløb	Amount	Ja	0..1
	protected $PayableRoundingAmount;//	Afrundingsbeløb	Amount	Ja	0..1
	protected $PayableAmount;//
}

?>
