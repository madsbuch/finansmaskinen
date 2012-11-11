<?php
namespace core;
class client{
	public static function getIP()
	{
		return $_SERVER["REMOTE_ADDR"];
	}
	
	public static function getUA(){
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'UA undefined' ;
	}
	
	public static function getRef(){
		if(isset($_SERVER['HTTP_REFERER']))
			return $_SERVER['HTTP_REFERER'];
		return "";
	}
	
	/**
	* returns headers sent by client
	*
	* $encoding is possible, following is implemented:
	* base64: a string is made and base64 encoded
	* serialize: the array is serialized
	*/
	public static function getHeaders($encoding=false)
	{
		if(!$encoding)
			return getallheaders();
		
		if($encoding == "base64"){
			foreach (getallheaders() as $name => $value) {
				 $str = "$name: $value\n";
			}
			
			return \base64_encode($str);
		}
		
		if($encode == "serialize")
			return serialize(getallheaders());
		return false;
	}
}

?>
