<?php

namespace app\companyProfile;

class Subscribe{
	
	public static $apps = array(
		'nh' => '\app\companyProfile\apps\Nemhandel',
		'om' => '\app\companyProfile\apps\OfferMarket',
	);

	/**
	 * subscription details for all apps
	 * @var array
	 */
	public static $subscriptions = array(
		'invoice' => array(
			'appName'   => 'invoice',
			'price'     => 4900,

		),
		'billing' => array(
			'appName'   => 'billing',
			'price'     => 4900,

		),
	);
	
}

?>
