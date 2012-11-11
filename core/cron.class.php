<?php
/**
* core cron
*
* this file administrates the cron. the main purpose is to iterate throug all
* the core classes, and execute the static defined cron. That also means that
* the function cron is reserves
*
* remeber due to the horizontal scaling, timings are not to be trusted. do24hour
* executes every 24th hour pr server.
*/
namespace core;
class cron{
	
	/**
	* call as often as possible
	*/
	static function doVeryOften(){
	
	}
	
	/**
	* called every hour
	*/
	static function do1hour(){
	
	}
	
	/**
	* this one should be executed every 24th hour
	*/
	static function do24Hour(){
	
	
	}
	
	/**
	* called every 15th day
	*/
	static function do15day(){
	
	}
	
	
}
?>
