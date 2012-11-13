<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juff
 * Date: 10/8/12
 * Time: 7:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\log\core;

class Exception{
	public $_filename = 'core/exception.log';
	public $_level = 'ERROR';

	/**
	 * actual logging fields
	 */
	public $message;
	public $stack;
}
