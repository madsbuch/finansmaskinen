<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juff
 * Date: 10/8/12
 * Time: 11:13 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\log\core;

class Access
{
	public $_filename = 'core/access.log';

	/**
	 * @var sat by the error handler
	 */
	public $_level = "INFO";

	public $userid;
	public $mail;

	/**
	 * @var the interface that is used, e.g. web, rpc, soap rest....
	 */
	public $interface;

}
