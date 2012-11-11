<?php
/**
* representing an app
*/

namespace model\finance\company;

class App extends \model\AbstractModel{
	
	protected $title;
	
	//a lauout object, should be ajax only
	protected $description;
	
	protected $image;
	
	protected $pending;
	
	protected $integration;
}

?>
