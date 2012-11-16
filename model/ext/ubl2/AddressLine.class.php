<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class AddressLine extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'Line' => array('\model\ext\ubl2\field\Text', false),
	);
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $Line;//	Adresse Punkt	Klasse
}

?>
