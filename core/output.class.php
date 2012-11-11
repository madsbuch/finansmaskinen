<?php
namespace core;
class output{
	
	public static $content;
	public static $header;

	private static $headerSend = FALSE;
	private static $contentSend = FALSE;
	
	public static function sendHeader(){
		$arr = explode("\n",self::$header);
		
		foreach($arr as $str)
			header("$str");
			
		self::$headerSend = TRUE;
	}
	public static function sendContent(){
		echo self::$content;
		self::$contentSend = TRUE;
	}
	public static function send(){
		if(!self::$headerSend)
			self::sendHeader();
		if(!self::$contentSend)
			self::sendContent();
	}

}

?>
