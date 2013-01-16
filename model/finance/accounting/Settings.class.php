<?php
/**
 * User: Mads Buch
 * Date: 12/21/12
 * Time: 5:25 PM
 */

namespace model\finance\accounting;

/**
 * class representing settings
 * @property $vatSettlementAccount
 */
class Settings extends \model\AbstractModel// implements \app\companyProfile\Settings
{
    //casting to correct types
    protected $_autoassign = array(
        'vatSettlementAccount' => array('int', false)
    );
	/**
	 * account to posst vat settlement to
	 *
	 * @var int
	 */
	protected $vatSettlementAccount;
}
