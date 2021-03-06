<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class Party extends \model\AbstractModel{
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'WebsiteURI' => array('\model\ext\ubl2\field\Identifier', false),
		'LogoReferenceID' => array('\model\ext\ubl2\field\Identifier', false),
		'EndpointID' => array('\model\ext\ubl2\field\Identifier', false),
		//'MarkCareIndicator' => array('\model\ext\ubl2\field\COIndikator', false),
		//'MarkAttentionIndicator' => array('\model\ext\ubl2\field\AttentionIndikator', false),
		
		//'AgentParty' => array('\model\ext\ubl2\Party', false),
		'PartyIdentification' => array('\model\ext\ubl2\PartyIdentification', true),
		'PartyName' => array('\model\ext\ubl2\PartyName', false),//@TODO this is true...
		'Language' => array('\model\ext\ubl2\Language', false),
		'PostalAddress' => array('\model\ext\ubl2\Address', false),
		'PhysicalLocation' => array('\model\ext\ubl2\Location', false),
		'PartyTaxScheme' => array('\model\ext\ubl2\PartyTaxScheme', false),
		'PartyLegalEntity' => array('\model\ext\ubl2\PartyLegalEntity', false),
		'Contact' => array('\model\ext\ubl2\Contact', false),
		'Person' => array('\model\ext\ubl2\Person', false),
	);
	
	protected $WebsiteURI;
	protected $LogoReferenceID;
	protected $EndpointID;
	protected $MarkCareIndicator;		//not in OIOUBL
	protected $MarkAttentionIndicator;	//not in OIOUBL
	
	protected $AgentParty;				//not in OIOUBL
	protected $PartyIdentification;
	protected $PartyName;
	protected $Language;
	protected $PostalAddress;//	PostAdresse	Ja	0..1	Bibliotek, 3.1	 
	protected $PhysicalLocation;//	FysiskAdresse	Ja	0..1	3.70.1	 
	protected $PartyTaxScheme;//	AfgiftOplysninger	Ja	0..n	Bibliotek, 3.74	 
	protected $PartyLegalEntity;//	JuridiskPart	Ja	0..1	Bibliotek, 3.72	 
	protected $Contact;//	Kontakt	Ja	0..1	Bibliotek, 3.21	 
	protected $Person;//
}

?>
