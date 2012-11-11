<?php
/**
* some aux for the app objects
*/

namespace app\companyProfile\apps;

abstract class AbstractApp {
	
	/**
	* creates a child of the main grp for holding this app
	*
	* returns the app afterwards
	*/
	function CreateGroup(){
	
	}
	
	/** All the abstract stuff **/
	
	/**
	* all the logic for setting this app up
	*/
	abstract function setup();
	
	/**
	* hides the app, and everything from the installation => the app isn't ran
	*/
	abstract function hide();
	
	/**
	* if the app was hidden, this shows it again
	*/
	abstract function show();
	
	/**
	* resets the whole app.
	*
	* that is equevilent to remove access to group, and 
	*/
	abstract function reset();
	
	/**
	* returns array for showing this object in a list
	*/
	abstract function getDescription();
	
	/**
	* returns layoutobject for showing this in a single page
	*/
	abstract function getPage();
}

?>
