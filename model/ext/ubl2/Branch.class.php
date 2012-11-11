<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class Branch extends \model\AbstractModel{
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'ID' => 	array('\model\ext\ubl2\field\Identifier', false),
		'Name' => 	array('\model\ext\ubl2\field\Name', false),

		'FinancialInstitution' => array('\model\ext\ubl2\FinancialInstitution', false),
		'Address' =>	array('\model\ext\ubl2\Address', false),
	);
	
	protected $ID;//	Registreringsnummer	Identifier	Ja	0..1
	protected $Name;//	Navn	Name	Ja	0..1

	protected $FinancialInstitution;//	Pengeinstitut	Ja	0..1	Bibliotek, 3.43	 
	protected $Address;//
}

?>
