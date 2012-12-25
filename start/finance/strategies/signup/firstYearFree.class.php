<?php
/**
 * User: Mads Buch
 * Date: 11/27/12
 * Time: 2:23 PM
 */
namespace start\fiance\strategies\signup;
class firstYearFree implements Signup
{
	/**
	 * this strategy gives first year free according to some specs defined from constructer
	 *
	 * this should be used when creating
	 */
	function __construct()
	{

	}


	/**
	 * makes it possible for at strategy to add some input fields to the signup form
	 * @return mixed
	 */
	function prepareOutput()
	{
		// TODO: Implement prepareOutput() method.
	}

	/**
	 * processes input from the form
	 * @return mixed
	 */
	function processInput($input, $signupObject)
	{
		// TODO: Implement processInput() method.
	}
}
