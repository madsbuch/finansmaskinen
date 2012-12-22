<?php
/**
 * User: Mads Buch
 * Date: 12/21/12
 * Time: 5:25 PM
 */

namespace model\finance\accounting;

/**
 * class representing settings
 */
class Settings extends \model\AbstractModel// implements \app\companyProfile\Settings
{

	/**
	 * account to posst vat settlement to
	 *
	 * @var int
	 */
	protected $vatSettlementAccount;

	function getDescriptions(){
		return array(
			'vatSettlementAccount' => 'Account to save VAT settlement to.'
		);
	}

	function getSettingsTitle(){
		return 'Accounting';
	}
}
