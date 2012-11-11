<?php
/**
* representing an app
*/

namespace model\finance\company;

class AppSetting extends \model\AbstractModel{
	
	protected $title;
	
	//a lauout object, should be ajax only
	protected $settingsModal;
	
	//id to trigger the modal
	protected $modalID;
}

?>
