<?php
/**
* specific configuration for the finance profile
* this is primarily api key and stuff
*
* this could very well in be in a database?
*/
namespace config;

class finance{
	
	/**
	* initial contact, so they can communicate to us :D and recieve bills ;)
	*/
	public static $contacts = array();
	
	/**
	* initial apps a user is aubscribed to
	*/
	public static $initApps = array(
		//company, that get's the standard suite of apps, including offerCreate
		'company' => array(
			1 => 'Invoice',
			2 => 'Contacts',
			3 => 'Companyinformation',
			7 => 'Products',
			8 => 'Accounting',
			11 => 'Offer Creater',
			10 => 'Billing'),
		//instead of offerCreate, this user gets offermarket
		'accounter' => array(
			1 => 'Invoice',
			2 => 'Contacts',
			3 => 'Companyinformation',
			7 => 'Products',
			8 => 'Accounting',
			9 => 'Offer Market',
			10 => 'Billing'),
	);
	
	/**
	* some settings
	*/
	public static $settings = array(
		//how much do we take for providing the binding for an offer in %
		'offerFactor' => 30,
	);
	
	/**
	* people that sends mail to people, this is for personalizing the system
	*/
	public static $supporters = array(
		'mads' => array(
			'name' => 'Mads Buch',
			'mail' => 'mads@finansmaskinen.dk',
			'signature' => "Venlig hilsen Mads Buch - Finansmaskinen\n;-)"
		)
	);
	
	/**** API KEYS ****/
	public static $api = array(
		'krak' => array(),
		'finansmaskinen' => array(
			'url' => 'http://rpc.finansmaskinen.dk/',//remember trailing /
			'key' => 'b82544edb387a6190951b2ecbc4bdaa99b6d1f49d59f5d8cc3e395cd80175958-50716ffa41769-d41d8cd98f00b204e9800998ecf8427e'
		)
	);
}

?>
