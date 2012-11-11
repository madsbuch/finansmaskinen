<?php
/**
* config class
*/


class config{
	
	static private $config;	
	
	static function getConfig(){
		if(empty(self::$config))
			self::$config = new default_config();
		return self::$config;
	}
	
	/**
	* set config object
	*/
	static function setConfig($config){
		self::$config = $config;
	}
}

class default_config{

}

?>
