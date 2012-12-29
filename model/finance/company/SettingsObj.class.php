<?php
/**
 * User: Mads Buch
 * Date: 12/21/12
 * Time: 6:38 PM
 */
namespace model\finance\company;
class SettingsObj extends \model\AbstractModel
{
	/**
	 * non translated settings title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * associative array of fields in this settingsobj
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * actual settings
	 * @var mixed
	 */
	protected $settings;


}
