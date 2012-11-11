<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class Contact extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'ID' => 	array('\model\ext\ubl2\field\Identifier', false),
		'Name' => 	array('\model\ext\ubl2\field\Name', false),
		'Telephone' => 	array('\model\ext\ubl2\field\Name', false),
		'Telefax' => 	array('\model\ext\ubl2\field\Name', false),
		'ElectronicMail' => 	array('\model\ext\ubl2\field\Name', false),
		'Note' => 	array('\model\ext\ubl2\field\Name', false),
		
		'OtherCommunication' => array('\model\ext\ubl2\OtherCommunication', false),
	);
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $ID;//	ID	Identifier	Ja	0..1
	protected $Name;//	Navn	Name	Ja	0..1
	protected $Telephone;//	Telefon	Text	Ja	0..1
	protected $Telefax;//	Telefax	Text	Ja	0..1
	protected $ElectronicMail;//	Email	Text	Ja	0..1
	protected $Note;//	Note	Text	Ja	0..1

	protected $OtherCommunication;//	AndenKommunikation	Ja	0..n	Bibliotek, 3.19
}

?>
