<?php
/**
* documentation:
* search for UBL2.02
*
* so weird that this is an actual class?? or...?
*/


namespace model\ext\ubl2;

class PartyName extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'Name' => array('\model\ext\ubl2\field\Name', false),
	);
	
	protected $Name;
	
	/**
	* this'll get a little odd constructor, as this class will accept a string
	*/
	function __construct($data){
		if(!is_array($data) && !is_object($data))
			$this->Name = new $this->_autoassign['Name'][0]($data);
		else
			parent::__construct($data);
	}
	
	/**
	* fucked up class, everything has to be overrided
	*/
	function __toString(){
		return (string) $this->Name;
	}
}

?>
