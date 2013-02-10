<?php
/**
 * User: Mads Buch
 * Date: 2/10/13
 * Time: 11:45 AM
 */

namespace app\accounting\layout\finance;

use \helper\local as l;

class vatCodes extends \helper\layout\LayoutBlock
{

	private $vatCodes;

	function __construct($vatCodes){
	 	$this->vatCodes = $vatCodes;
	}

	function generate()
	{
		$table = new \helper\layout\Table(array(
			'code' => 'Kode',
			'name' => 'Navn',
			'description' => 'Beskrivelse',
			'.' => array(__('More'), function($obj, $dom){
				$a = $dom->createElement('a', __('More'));
				$a->setAttribute('href', '/accounting/viewVatCode/'.$obj->code);
				return $a;
			})
		));

		$table->setNull('-');
		$table->setEmpty(__('No vat codes to show'));
		$table->setItterator($this->vatCodes);

		return $table->generate();
	}
}
