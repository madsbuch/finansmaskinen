<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance\contacts;

class ContactPerson extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'Person' => array('\model\ext\ubl2\Person', false),
		'Contact' => array('\model\ext\ubl2\Contact', false)
	);

	
	/**
	* the fields it consists of
	*/
	protected $Person;
	protected $Contact;

}

?>
