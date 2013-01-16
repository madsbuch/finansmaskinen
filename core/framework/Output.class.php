<?php
/**
 * User: Mads Buch
 * Date: 12/18/12
 * Time: 7:19 PM
 */
namespace core\framework;

/**
 * interfaces for devices handling output
 */
interface Output
{
	/**
	 * this method is responsible for showing an error message
	 * to the user if a noncought exception (including php errors)
	 * happens.
	 *
	 * It is only outputting.
	 *
	 * @param \Exception $e message to shout to the user (already translated)
	 * @return void
	 */
	function handleException($e);

	/**
	 * get the http header (\n seperated)
	 *
	 * @return string
	 */
	function getHeader();

	/**
	 * string representing the body
	 * any filetype is accepted
	 *
	 * @return string
	 */
	function getBody();
}
