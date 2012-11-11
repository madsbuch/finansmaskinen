<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class PaymentMeans extends \model\AbstractModel{
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'ID' => 		array('\model\ext\ubl2\field\Identifier', false),
		'PaymentMeansCode' => 		array('\model\ext\ubl2\field\Code', false),
		'PaymentDueDate' => 		array('\model\ext\ubl2\field\Date', false),
		'PaymentChannelCode' => 		array('\model\ext\ubl2\field\Code', false),
		'InstructionID' => 		array('\model\ext\ubl2\field\Identifier', false),
		'InstructionNote' => 		array('\model\ext\ubl2\field\Text', false),
		'PaymentID' => 		array('\model\ext\ubl2\field\Identifier', false),

		'PayerFinancialAccount' => 		array('\model\ext\ubl2\FinancialAccount', false),
		'PayeeFinancialAccount' => 		array('\model\ext\ubl2\FinancialAccount', false),
		'CreditAccount' => 		array('\model\ext\ubl2\CreditAccount', false),
	);
	
	protected $ID;//	ID	Identifier	Ja	0..1
	protected $PaymentMeansCode;//	BetalingsMådeKode	Code	Ja	1
	protected $PaymentDueDate;//	BetalingsDato	Date	Ja	0..1
	protected $PaymentChannelCode;//	BetalingsType	Code	Ja	0..1
	protected $InstructionID;//	BetalingsInstruktionsID	Identifier	Ja	0..1
	protected $InstructionNote;//	LangAdvisering	Text	Ja	0..1
	protected $PaymentID;//

	protected $PayerFinancialAccount;//	KøbersBankKonto	Ja	0..1	Bibliotek, 3.42	 
	protected $PayeeFinancialAccount;//	SælgersBankKonto	Ja	0..1	3.76.1	 
	protected $CreditAccount;//
}

?>
