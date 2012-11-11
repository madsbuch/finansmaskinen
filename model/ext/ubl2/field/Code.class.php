<?php
/**
* documentation:
* search for UBL2.02
*
* represents the amount datatype of UBL
*/

namespace model\ext\ubl2\field;

class Code extends \model\AbstractModel{
	
	protected $_fieldvarAsAttr = true;
	protected $_namespace =
		array('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
	
	protected $_content;
	
	protected $listID;//	Identificerer listen	Obligatorisk for OIOUBL koder
	protected $listAgencyID;//	Identificerer udstederen af listen	320	Obligatorisk for OIOUBL koder
	protected $listAgencyName;//	Angiver navnet på udstederen af listen	 	Valgfri
	protected $listVersionID;//	Versionen af listen	 	Valgfri
	protected $name;//	Navn på listen	 	Anvendes ikke
	protected $languageID;//	Det sprog listen er angivet på	 	Anvendes ikke
	protected $listURI;//	Link til hvor listen kan findes	 	Anvendes ikke
	protected $listSchemeURI;//
	
}

?>
