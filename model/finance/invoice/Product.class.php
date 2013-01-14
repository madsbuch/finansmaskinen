<?php
/**
* this represents a product. The UBL line is findable from the information in this
*
*/

namespace model\finance\invoice;

class Product extends \model\AbstractModel{
	/**
	* make it easy to track products of a certain cat
	*/
	protected $index;
	
	/**
	 * product id
	 */
	protected $id;
	
	/**
	* original amount
	*/
	protected $origAmount;
	
	/**
	* original valuta
	*/
	protected $origValuta;
}


?>
