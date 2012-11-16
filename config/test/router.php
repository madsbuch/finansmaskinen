<?php
/**
* router
*
* specify custom pages for domains and so on
* @author Mads Buch <madspbuch@gmail.com>
*/
namespace config;
class router{
	/**
	* Profiles
	*/
	public static $profiles = array(
		'finance' => array('start' => 'finance'),
		'financeAdm' => array('start' => 'financeAdm')
	);
	
	/**
	* domains. the domain points on a profile (also called sites somewhere)
	*/
	public static $domains = array(
		'finansmaskinen.dk' => 'finance',
		'finansmaskinen.dev'=> 'finance',
		'samarbejd.nu'		=> 'school',
		'appf.dev'			=> null,
		'admin.dk'			=> "administration",//til administrationsmodul for hele lortet :D
		'fadmin.dev'		=> 'financeAdm'
	);
	
}
?>
