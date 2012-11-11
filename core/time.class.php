<?php
namespace core;
class time{
	
	private static $timeObj;
	
	public static function init()
	{
		self::$timeObj = new DateTime();
		self::setTimeZone(TIMEZONE);
	}
	
	public static function setTimeZone($timezoneStr)
	{
		$timezone = new DateTimeZone($timezoneStr);
		self::$timeObj->setTimezone($timezone);
	}
	
	/**
	* Get Unix Time
	*
	* Returns current unixtime, the function is resistent to timezones
	*/
	public static function getUnixTime()
	{
		return time();
	}
	
	public static function format($format, $unix=null)
	{
		if(!$unix)
			$u = time();
		else
			$u = $unix;
		return $timeObj->format($format, $u);
	}
}

?>
