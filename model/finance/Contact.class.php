<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance;

class Contact extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'Party' => array('\model\ext\ubl2\Party', false),
		'legalNumbers' => array('\model\Base', false),
		'ContactPerson' => array('model\finance\contacts\ContactPerson', true)
	);
	
	//field vars that are externally accessible
	protected $_external = array();
	
	/**
	* versining
	*
	* version string shall comply with the characters of those methods can have
	*/
	protected $_version = 'v2';
	protected $_model   = 'model\finance\Contact';
	
	//this is the current version of this model. notice the static context
	protected static $_currentVersion = 'v2';
	
	/**
	* internal ID
	*/
	protected $_id;
	protected $_subsystem;
	
	/**
	* if the contact also belongs to a system like this, it is possible to
	* fetch data about the user from the system.
	*
	* should those be accessible through normal api?
	*/
	protected $apiID;
	protected $apiUrl;
	
	/**
	* relation to \finance\ubl\Party
	*
	* person and contact are not used, they are in this object instead
	*/
	protected $Party;
	
	/**
	* ID by user
	*/
	protected $contactID;
	
	/**
	* legal numbers
	*/
	protected $legalNumbers;
	
	/**
	* overrides $Party->Contact and $Party->Person
	*
	* array of pairs:
	* model\Base{
	*  public Contacts;
	*  public Person
	* }
	*/	
	protected $ContactPerson;
	
	/**
	* default settings for this contacts
	*/
	protected $currency; //as defined in UBL standard.
	
	/**
	* array of default assigned ubl\PaymentMeans
	*/
	protected $PaymentMeans;
	
	/**** Updaters ****/
	
	/**
	* this method updates an array for use with this model, before creating the
	* actual model
	*
	* this is ran if a model have v1 as version string, and if v1 is not the newest one
	*/
	function upgrade_v1($arr){
		//remember to update the version, otherwise we'll have en infinite loop
        if(isset($arr['id']))
            $arr['contactID'] = $arr['id'];
        unset($arr['id']);
		$arr['_version'] = 'v2';
		return $arr;
	}
}

?>
