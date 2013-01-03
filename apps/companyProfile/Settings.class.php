<?php
/**
 * User: Mads Buch
 * Date: 12/21/12
 * Time: 5:52 PM
 */
namespace app\companyProfile;

interface Settings
{
	/**
	 * returns associative array like
	 *
	 * 'key' => 'description on form field'
	 *
	 * descriptions are NON-translated.
	 *
	 * @return array
	 */
	function getDescriptions();

	/**
	 * returns NON-translated title for which the settings are available under
	 *
	 * @return string
	 */
	function getSettingsTitle();
}
