<?php
/**
 * User: Mads Buch
 * Date: 11/27/12
 * Time: 2:16 PM
 */
namespace start\fiance\strategies\signup;
interface Signup
{
	/**
	 * makes it possible for at strategy to add some input fields to the signup form
	 * @return mixed
	 */
	function prepareOutput();

	/**
	 * processes input from the form
	 * @return mixed
	 */
	function processInput($input, $signupObject);
}
