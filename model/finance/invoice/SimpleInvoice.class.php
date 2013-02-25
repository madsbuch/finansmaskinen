<?php
/**
 * User: Mads Buch
 * Date: 12/9/12
 * Time: 11:46 PM
 */

namespace model\finance\invoice;

/**
 * @property $date string
 * @property $products
 * @property $contactID
 * @property $vat
 * @property $currency
 * @property $exchangeRates
 */
class SimpleInvoice extends \model\AbstractModel
{
	protected $_autoassign = array(
		'products' => array('\model\finance\invoice\SimpleProduct', true),
		'contact' => array('string', false),
		'exchangeRates' => array('\model\finance\invoice\Rate', true)
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
	 * exchange rates associated
	 *
	 * @var \model\finance\invoice\Rate
	 */
	protected $exchangeRates;

	/**
	 * date of the invoice
	 *
	 * @var string
	 */
	protected $date;

    /**
     * level is not used, in all cercumstances, all fields should be set
     *
     * @param int $level
     * @return array
     */
    function doValidate($level){
        $ret = array();
        if(empty($this->products))
            $ret[] = 'At least one product is needed';
        if(empty($this->contactID))
            $ret[] = 'A contact is needed';
        if(empty($this->vat))
            $ret[] = 'Vat is not set';

        //@TODO validate currency code, this requires helper function
        if(empty($this->currency))
            $ret[] = 'Currency was not set';
        return $ret;
    }

}
