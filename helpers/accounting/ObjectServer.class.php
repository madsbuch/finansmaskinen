<?php
/**
 * User: Mads Buch
 * Date: 1/8/13
 * Time: 12:15 AM
 */

namespace helper\accounting;

class ObjectServer
{
	/**
	 * the main accounting object
	 *
	 * @var \helper\accounting
	 */
	public $controller;

	/**
	 * @var \core\db
	 */
	public $db;

	/**
	 * @var \helper\accounting\Queries
	 */
	public $queries;

	/**** a few variables needed ****/

	public $grp;

	/**
	 * @var string
	 */
	public $accounting;
}
