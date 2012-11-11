<?php
/**
 * @author Mads Buch
 */

namespace model\ext\ubl2;

class PartyLegalEntity extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'RegistrationName' => array('\model\ext\ubl2\field\Name', false),
		'CompanyID' => array('\model\ext\ubl2\field\Identifier', false),

		'RegistrationAddress' => array('\model\ext\ubl2\Address', true),
	);
	
	protected $RegistrationName;//	RegistreringsNavn	Name	Ja	0..1
	protected $CompanyID;//	RegistreringsNummer	Identifier	Ja	1


	protected $RegistrationAddress;

}

?>
