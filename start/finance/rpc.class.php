<?php
/**
 * User: Mads Buch
 * Date: 1/16/13
 * Time: 12:06 AM
 */

namespace start\finance;

class rpc extends \core\rpc implements \core\framework\Output
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
	function handleException($e)
	{
		/**** echo out some content ****/
		if($e instanceof \exception\PermissionException){
			$this->throwException('Forbidden, you do not have access to that resource (http 403)');
		}
		elseif($e instanceof \exception\PageNotFoundException){
			$this->throwException('Resource was not to find (http 404)');
		}
		elseif($e instanceof \exception\UserException){
			$this->throwException($e->getMessage());
		}
		else{
			$this->throwException('Internal server error (http 500)');
		}
	}

	/**
	 * get the http header (\n seperated)
	 *
	 * @return string
	 */
	function getHeader()
	{
		return $this->getOutputHeader();
	}

	/**
	 * string representing the body
	 * any filetype is accepted
	 *
	 * @return string
	 */
	function getBody()
	{
		return $this->getOutputContent();
	}
}
