<?php
/**
* documentation:
* search for UBL2.02
*
* represents the amount datatype of UBL
*/

namespace model\ext\ubl2\field;

class Date extends \model\AbstractModel{
	
	protected $_fieldvarAsAttr = true;
	protected $_namespace =
		array('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
	
	/**
	* make sure it's formattet: ISO 8601’s standardformat "YYYY-MM-DD",
	*/
	protected $_content;

	/**
	 * tries to parse date to correct internally representation
	 */
	function doParse(){
		if($t = strtotime($this->_content))
			$this->_content = date("c", $t);
	}
}

?>
