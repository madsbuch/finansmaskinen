<?php
/**
* documentation:
* search for UBL2.02
*
* represents the amount datatype of UBL
*/

namespace model\ext\ubl2\field;

class Text extends \model\AbstractModel{
	
	protected $_fieldvarAsAttr = true;
	protected $_namespace =
		array('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
	
	protected $_content;
	
	protected $languageID;//
	
}

?>
