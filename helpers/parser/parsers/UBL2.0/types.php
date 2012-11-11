<?php
/**
* This file contains all types.
*
* types are simple and does not require a lot of space, so they are all
* contained in this file
*/

namespace helper_parser\ubl\Type;

require_once("abstractType.class.php");

class Amount extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Code extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Date extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
	function setDate($timestamp){
		//\strtotime($date)
		$this->content = \date("o-m-d",$timestamp);
	}
}

/**
* identifier type
*/
class Identifier extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array(
		'schemeID' => false,
		'schemeName' => false,
		'schemeAgencyID' => false,
		'schemeAgencyName' => false,
		'schemeVersionID' => false,
		'schemeLanguageID' => false,
		'schemeDataURI' => false,
		'schemeURI' => false
	);
}

class Indicator extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Measure extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Name extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Numeric extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Percent extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Quantity extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Rate extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Text extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Time extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

class Value extends AbstractType {
	protected $xmlns = 'cbc';
	protected $attributes = array();
}

?>
