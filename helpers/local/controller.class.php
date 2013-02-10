<?php
namespace helper;

use \core\localization as l;

class local{
	
	/**
	* converts a int to a string representation of some valuta
	*
	*
	*
	* @param $num the string representation
	* @param $valuta the valuta code
	* @param $asNum if true, the output is just the number, otherwise, the valuta
	*				is prepended
	*/
	static function writeValuta($num, $valuta = null, $asString=false){
		//formatting number as a valuta. Remeber to devide with the decimal devisor, often 100
		$num = number_format ($num/100, 2, l::$commaSeparator, l::$thousandsSeparator);
		
		if($asString)
			return $valuta . ' ' . $num;
		return $num;
	}

	/**
	 * reads a number for internal storage (int)
	 *
	 * @param $num
	 * @param null $value
	 * @return int
	 * @internal param \helper\don $valuta 't have to be set. the function will assume 1/100'th is
	 *            the smallest part in the valuta
	 */
	static function readValuta($num, $value=null){
		//explode by comma
		$num = explode(l::$commaSeparator, $num);
		
		$num[0] = str_replace(l::$thousandsSeparator, '', $num[0]);
		
		$num[1] = isset($num[1]) ? substr($num[1], 0, 2) : '00';
		
		if(strlen($num[1]) == 1)
			$num[1] .= '0';
		
		return intval($num[0] . $num[1]);
	}
	
	/**
	* writes valuta, just always with . as comma and no thousandsseparator.
	*
	* this does take the valuta in account.
	*/
	static function nonLocalWriteValuta($num, $valuta = null, $asNum=true){
		return $num/100;
	}
	
	/**
	* parses number, and takes commaseparator and thousandsseparator in account
	*
	* parses it to float
	*/
	static function readNum($number){
		//explode by comma
		$num = explode(l::$commaSeparator, $number);
		//remove thousands
		$left = str_replace(l::$thousandsSeparator, '', $num[0]);

		return (float) ($left . '.' . (isset($num[1]) ? $num[1] : ''));
	}
	
	/**
	* writes number with localization in account
	*/
	static function writeNum($num){
		$num = number_format ($num, 5, l::$commaSeparator, l::$thousandsSeparator);
		return $num;
	}
}

?>
