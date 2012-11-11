<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class CustomerParty extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $_autoassign = array(
		'CustomerAssignedAccountID' => array('\model\ext\ubl2\field\Identifier', false),
		'SupplierAssignedAccountID' => array('\model\ext\ubl2\field\Identifier', false),
		'AdditionalAccountID' => array('\model\ext\ubl2\field\Identifier', true),
		
		'Party' => array('\model\ext\ubl2\Party', false),
		'DeliveryContact' => array('\model\ext\ubl2\Contact', false),
		'AccountingContact' => array('\model\ext\ubl2\Contact', false),
		'BuyerContact' => array('\model\ext\ubl2\Contact', false),
	);
	
	protected $CustomerAssignedAccountID;//	KundeTildeltKontoNummer	Identifier	Aftales	0..1
	protected $SupplierAssignedAccountID;//	LeverandørKontoNummer	Identifier	Aftales	0..1
	protected $AdditionalAccountID;//	AndenKontoIdentifikation	Identifier	Aftales	0..n

	//UBL-Navn	DK-Navn	Afløftes	Brug	Reference til printet dokumentation	Se i øvrigt
	protected $Party;//	Part	Ja	1	3.9.1	 
	protected $DeliveryContact;//	LeveringsKontakt	Aftales	0..1	Bibliotek, 3.29	 
	protected $AccountingContact;//	AfregningsKontakt	Aftales	0..1	 	 
	protected $BuyerContact;//	KøberKontakt	Aftales	0..1	
}

?>
