<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juff
 * Date: 10/8/12
 * Time: 8:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\log\core;

class Error
{
	public $_filename = 'core/error.log';

	/**
	 * @var sat by the error handler
	 */
	public $_level = "ERROR";

	/**
	 * actual logging fields
	 */
	public $errstr;
	public $errfile;
	public $errline;
	public $errno;
}
