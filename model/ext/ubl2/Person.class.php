<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class Person extends \model\AbstractModel{

	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
	
	protected $_autoassign = array(
		'FirstName' => 	array('\model\ext\ubl2\field\Name', false),
		'FamilyName' => 	array('\model\ext\ubl2\field\Name', false),
		'Title' => 	array('\model\ext\ubl2\field\Text', false),
		'MiddleName' => 	array('\model\ext\ubl2\field\Name', false),
		'NameSuffix' => 	array('\model\ext\ubl2\field\Text', false),
		'JobTitle' => 	array('\model\ext\ubl2\field\Text', false),
		'OrganizationDepartment' => 	array('\model\ext\ubl2\field\Text', false),
	);
	
	protected $FirstName;
	protected $FamilyName;
	protected $Title;
	protected $MiddleName;
	protected $NameSuffix;
	protected $JobTitle;
	protected $OrganizationDepartment;
}
