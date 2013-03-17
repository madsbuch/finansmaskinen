<?php
/**
* documentation:
* search for UBL2.02
*
* represents the amount datatype of UBL
*/

namespace model\ext\ubl2\field;

class Amount extends \model\AbstractModel{
	protected $_fieldvarAsAttr = true;
	protected $_namespace =
		array('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
	/**
	* currency: ISO 4217
	*/
	
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	
	protected $currencyID;
	
	protected $_content;
	
	//some backwards combatability
	function set_CurrencyID($val){
		$this->currencyID = $val;
	}

	//validate whether ISO 4217
}

?>
