<?php
/**
 * User: mads
 * Date: 3/14/13
 * Time: 12:14 PM
 *
 * Entrance for the app, this is introduced to make offline testing possible.
 * like unittest.
 *
 * This is he first stop of moving everything to objects and completely dependecy
 * injection
 */

namespace core;



class entrance
{
	function __construct(){

	}

	function execute(){

	}

	/**
	 * returns generated headers
	 * only if catchoutput is enbled
	 */
	function getHeaders(){

	}

	/**
	 * returns generated body.
	 * may be used if catchoutput is enabled
	 */
	function getBody(){

	}

	/**
	 * catches output insted of sending it to stdout
	 */
	function catchOutput(){

	}

	/**
	 * use a stub instead of reading variables from CLI
	 *
	 * @param $stub
	 */
	function setPageStub($stub){

	}
}
