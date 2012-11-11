<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class FinancialAccount extends \model\AbstractModel{
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'ID' => 		array('\model\ext\ubl2\field\Identifier', false),
		'Name' => 		array('\model\ext\ubl2\field\Name', false),
		'AccountTypeCode' => 	array('\model\ext\ubl2\field\Code', false),
		'CurrencyCode' => 		array('\model\ext\ubl2\field\Code', false),
		'PaymentNote' => 		array('\model\ext\ubl2\field\Text', false),

		'FinancialInstitutionBranch' => array('\model\ext\ubl2\Branch', false),
		//'Country' => 			array('\model\ext\ubl2\field\Identifier', false),
	);
	
	protected $ID;//	ID	Identifier	Ja	0..1
	protected $Name;//	Navn	Name	Aftales	0..1
	protected $AccountTypeCode;//	KontoType	Code	Aftales	0..1
	protected $CurrencyCode;//	ValutaKode	Code	Ja	0..1
	protected $PaymentNote;//	KortAdvisering	Text	Ja	0..n

	protected $FinancialInstitutionBranch;//
	protected $Country;//map it, not a part of oioubl
}

?>
