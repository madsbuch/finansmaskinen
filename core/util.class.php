<?php
/**
* core_util
*
* randon utilities
*/
namespace core;
class util{
	/**
	* create strong hash (using more hash algorithms)
	*/
	static function HashCleartext($text, $salt="lf34q*_p8qqT)QT"){
		$str = $salt.$text.$salt;
		$str .= hash("sha512", $str).'salt';
		$str = hash("sha512", $str);
		return $str;
	}
	
	/**
	* generate random string, used for password generation
	*/
	static function stringGen($length = 8) {  
		$chars = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
			"1", "2", "3", "4", "5", "6", "7", "8", "9", "0"	
		);
		$randomString = "";  
		while(strlen($randomString) < $length) {  
			$randIndex = rand(0, count($chars) - 1);  
			$randomString .= $chars[$randIndex];  
		}  
		return $randomString;  
	}
	
	/****************** VALIDATION FUNCTIONS **********************************/
	
	/**
	* mail validation
	*
	* just simple check
	*/
	static function validateMail($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		   return false;
		return true;
		
	}
	
	/****************** SANITIZATING FUNCTIONS ********************************/
}


?>
