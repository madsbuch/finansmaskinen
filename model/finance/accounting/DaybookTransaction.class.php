<?php
/**
 * User: mads
 * Date: 11/17/12
 * Time: 12:22 AM
 *
 * @TODO refactor the whole system, so that Transaction class is not used, but that Postings class is
 *
 */
namespace model\finance\accounting;
class DaybookTransaction extends \model\AbstractModel
{

	protected $ref;
	protected $postings;

	/**
	 * what do you think?!
	 */
	protected $data;
}
