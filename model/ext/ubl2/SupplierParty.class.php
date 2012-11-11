<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class SupplierParty extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $_autoassign = array(
		'CustomerAssignedAccountID' => array('\model\ext\ubl2\field\Identifier', false),
		'AdditionalAccountID' => array('\model\ext\ubl2\field\Identifier', true),
		
		'Party' => array('\model\ext\ubl2\Party', false),
		'DespatchContact' => array('\model\ext\ubl2\Contact', false),
		'AccountingContact' => array('\model\ext\ubl2\Contact', false),
		'SellerContact' => array('\model\ext\ubl2\Contact', false),
	);
	
	protected $CustomerAssignedAccountID;//	KundeTildeltKontoNummer	Identifier	Aftales	0..1
	protected $AdditionalAccountID;//	AndenKontoIdentifikation	Identifier	Aftales	0..n

	//UBL-Navn	DK-Navn	Afløftes	Brug	Reference til printet dokumentation	Se i øvrigt
	protected $Party;//		PartSpecifikation	Ja	1	3.8.1	 
	protected $DespatchContact;//		AfsendelsesKontakt	Aftales	0..1	Bibliotek, 3.21	 
	protected $AccountingContact;//		AfregningsKontakt	Aftales	0..1	Bibliotek, 3.21	 
	protected $SellerContact;//
	
	protected $DataSendingCapability;
}

?>
