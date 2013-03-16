<?php

namespace model\finance\company;

/**
 * @property $lastPayment;
 * @property $expirationDate;
 * @property $appName;
 * @property $price;
 * @property $isSubscribed;
 */
class Subscription extends \model\AbstractModel{

	/**
	 * timestamp og last payment time
	 *
	 * @var int
	 */
	protected $lastPayment;

	/**
	 * when does it expire
	 *
	 * @var int
	 */
	protected $expirationDate;

	/**
	 * name of the app, this is the internal name (foldername)
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
