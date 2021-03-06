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

/**
 * @property $referenceText string
 * @property $postings array
 * @property $date int
 * @property $approved bool
 * @property $_id
 */
class DaybookTransaction extends \model\AbstractModel
{
	protected $_autoassign = array(
		'postings' => array('\model\finance\accounting\Posting', true),
	);

    protected $_id;

	/**
	 * unique reference text
	 *
	 * @var string
	 */
	protected $referenceText;


	/**
	 * @var \model\finance\accounting\Posting
	 */
	protected $postings;


	/**
	 * hmm, either unix ts or iso8601
	 *
	 * @var string
	 */
	protected $date;

	/**
	 * @var bool
	 */
	protected $approved;

	/**
	 * what do you think?!
	 */
	protected $data;
}
