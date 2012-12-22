<?php
/**
 * User: Mads Buch
 * Date: 12/9/12
 * Time: 11:46 PM
 */

namespace model\finance\invoice;

class SimpleInvoice extends \model\AbstractModel
{
	protected $_autoassign = array(
		'products' => array('string', true),
		'contact' => array('string', false),
	);
	protected $products;

	protected $contact;

	/** some options, with default values **/

	protected $vat = true;



}
