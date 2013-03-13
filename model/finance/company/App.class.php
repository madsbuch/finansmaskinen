<?php
/**
* representing an app
*/

namespace model\finance\company;

/**
 * This represents an app, activated apps has an object of this
 * in the company object
 */
class App extends \model\AbstractModel{

	/**
	 * Title
	 *
	 * @var
	 */
	protected $title;

	/**
	 * @var
	 */
	protected $description;

	/**
	 * @var
	 */
	protected $image;

	/**
	 * @var
	 */
	protected $pending;

	/**
	 * @var
	 */
	protected $integration;
}

?>
