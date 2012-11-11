<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class Country extends \model\AbstractModel{
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'IdentificationCode' => 	array('\model\ext\ubl2\field\Code', false),
		'Name' => 	array('\model\ext\ubl2\field\Name', false),
	);
	
	protected $IdentificationCode;//	Landekode	Code	Ja	1
	protected $Name;//
}

?>
