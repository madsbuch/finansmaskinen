<?php
/**
* html helper.
*
* This helper provides tools for generating layout
* The function returns an array, that can be insert with helper_page::insertByID()
* the std template functions automaticallly inserts into the struct
* write is used to automatically write to the struct. f.eks. if a ajax 
* function is inserted, the needed code will automatically be added
* 
*/
namespace helper;
class layout{
	
	/**
	* array with saved pages (for caching)
	*/
	private static $pages;
	
	/**
	* page is needed for reading settings
	*
	* @param ref $page		reference to page object
	*/
	public static function getInstance($pageObj){
		if(isset(self::$page[$pageObj]))
			return self::$page[$pageObj];
		
		$c = __CLASS__;
		self::$page[$pageObj] = new $c();
		return self::$page[$pageObj];
	}
	
	/**
	* why the **** did i make this singleton?
	* should it be singleton?
	*/
	function __construct(){
		
	}
	
	/**
	* Return a block helper
	*
	* f.ex. $helper = form, an object wich methods formanipulating forms are
	* returned
	*/
	public static function blockHelper($helper, $attr = array()){
		include_once __DIR__."/block_helpers/block.abstract.class.php";
		include_once __DIR__."/block_helpers/{$helper}.class.php";
		$objname = "helper\layout\\".$helper;
		return new $objname($attr);
	}
}

?>
