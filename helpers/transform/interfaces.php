<?php
/**
 * @author Mads Buch
 */

namespace helper\transform;

/**
 * declare that this object returns XML
 */
interface XML{
	/**
	 * return DOMDocument object
	 *
	 * is you wanna be sure on getting XML from an implementation, use this function
	 * as the object may have other implementation, and that the generate fucntion
	 * does not return XML
	 */
	public function getDOM();
}

/**
 * declare output of JSON type
 */
interface JSON{
	/**
	 * returns valid JSON string
	 */
	public function getJSON();
}

/**
 * declare output of model form
 *
 * name so that we do not collide with the class...
 */
interface ModelType{
	/**
	 * returns a model representation of the data
	 */
	public function getModel();
}
?>
