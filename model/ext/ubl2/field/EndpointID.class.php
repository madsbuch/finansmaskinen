<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\finance\ubl\fields;

class EndpointID extends \model\AbstractModel{

	//attributes:
	protected $schemeAgencyID;
	protected $schemeID;
	
	//actual content -> @TODO make a consistent name :S
	protected $EndpointID;
}

?>
