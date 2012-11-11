<?php
/**
* documentation:
* search for UBL2.02
*
* represents the amount datatype of UBL
*/

namespace model\ext\ubl2\field;

class Identifier extends \model\AbstractModel{
	protected $_fieldvarAsAttr = true;
	protected $_namespace =
		array('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
		
	protected $_content;
	
	protected $schemeID;//	Identificerer ID typen	urn:oioubl:id:profileid-1.1	Obligatorisk for OIOUBL Id-lister
	protected $schemeName;//	Navnet på ID typen	 	Anvendes ikke
	protected $schemeAgencyID;//	Angiver ID på udstederen af ID’et	320	Betinget (angives hvis muligt)
	protected $schemeAgencyName;//	Navnet på udstederen af ID’et	IT- og Telestyrelsen	Valgfri
	protected $schemeVersionID;//	Versionen af ID'et	 	Anvendes ikke
	protected $schemeLanguageID;//	Det sprog listen er angivet på	 	Anvendes ikke
	protected $schemeDataURI;//	Link til hvor listen kan findes	 	Anvendes ikke
	protected $schemeURI;//
}

?>
