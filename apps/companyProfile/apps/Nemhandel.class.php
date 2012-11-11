<?php
/**
* shows and setups nemhandel
*
* remeber to attach the object in parent dir
*/

namespace app\companyProfile\apps;

class Nemhandel extends AbstractApp{
	
	/**
	* all the logic for setting this app up
	*/
	function setup(){
	
	}
	
	/**
	* removes subscription.
	*
	* normally, just remove access to the app. No reason to delete data.
	*/
	function hide(){
	
	}
	
	/**
	* reattaches app, with the same groups, and stuff
	*
	*/
	function show(){
	
	}
	
	/**
	* resets the whole app.
	*
	* that is equevilent to remove access to group, and 
	*/
	function reset(){
	
	}
	
	/**
	* returns array for showing this object in a list
	*/
	function getDescription(){
		return new \model\finance\company\App(array(
				'title' => 'Nemhandel',
				'description' => __('Send and recieve documents through the Namhandel system'),
				'pending' => false,
				'integration' => false
			));
	}
	
	/**
	* returns layoutobject for showing this in a single page
	*/
	function getPage(){
		
	}
	
	
}

?>
