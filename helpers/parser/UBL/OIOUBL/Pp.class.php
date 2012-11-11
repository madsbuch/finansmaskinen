<?php
/**
* a lot of preprocessors for processing the UBL document models
*
* preprocess function are by class names, first argument is the model, next is
* the settings, and then tagname
*
* someClass($model, $settings, $tagname)
*/

namespace helper\parser\UBL\OIOUBL;

class Pp{
	
	static function Invoice($m, $s){
		return $m;
	}
	
	
	/**** Fields ****/
	static function Amount($m){
		if(isset($m->_content))
			$m->_content =  number_format(((int)$m->_content / 100), 2, '.', '');
		return $m;
	}
	
	static function Date($m){
		if(isset($m->_content))
			$m->_content = date('Y-m-d', $m->_content);
		
		return $m;
	}
	
	static function Quantity($m, $s){
		if(!isset($m->unitCode))
			$m->unitCode = $s['defaults']['unitCode'];
		return $m;
	}
	
}


?>
