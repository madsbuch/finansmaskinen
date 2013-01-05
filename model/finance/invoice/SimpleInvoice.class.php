<?php
/**
 * User: Mads Buch
 * Date: 12/9/12
 * Time: 11:46 PM
 */

namespace model\finance\invoice;

class SimpleInvoice extends \model\AbstractModel
{
	protected $_autoassign = array(
		'products' => array('string', true),
		'contact' => array('string', false),
	);

    /**
     * SimpleProduct objects
     *
     * @var \model\Iterator
     */
    protected $products;

    /**
     * contat id (not object id)
     *
     * @var string
     */
    protected $contactID;

	/** some options, with default values **/

    /**
     * whether to add VAT
     * @var bool
     */
    protected $vat = true;

	/**
	 * currency of the invoice
	 *
	 * @var string
	 */
	protected $currency;


	/**
	 * date of the invoice
	 *
	 * @var string
	 */
	protected $date;



}
