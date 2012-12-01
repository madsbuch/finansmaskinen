<?php
/**
 * User: mads
 * Date: 11/27/12
 * Time: 2:09 PM
 */

/**
 * settings factory for the finance
 */
interface Factory
{
	/**
	 * creates a sigup strategy object
	 *
	 * @return start\fiance\strategies\signup\Signup
	 */
	function createSignup();
}
