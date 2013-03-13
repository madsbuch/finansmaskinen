<?php

namespace model\finance\company;

class Subscription extends \model\AbstractModel{

	/**
	 * timestamp og last payment time
	 *
	 * @var int
	 */
	protected $lastPayment;

	/**
	 * name og app
	 *
	 * @var string
	 */
	protected $appName;

	/**
	 * monthly price
	 *
	 * @var
	 */
	protected $price;

	/**
	 * whether app is subscribed
	 *
	 * @var
	 */
	protected $isSubscribed;
	
}

?>
