<?php
/**
*  this cacher puts every entry in different files, this is for bigger files
*/
namespace helper\cache;

class File implements Cache{
	
	private $dir;
	
	/**
	* collection is to 
	*/
	function __construct($collection){
		$this->dir = CACHEDIR . 'filecacher/' . $collection . '/';
		//make sure the dir exists
		if(!is_dir(CACHEDIR . 'filecacher/')){
			mkdir(CACHEDIR . 'filecacher/');
		}
		if(!is_dir($this->dir)){
			@mkdir($this->dir);
		}
	}
	
	function offsetExists($offset){
		return file_exists($this->dir . $this->sanitize($offset));
	}
	
	/**
	* deletes entry
	*
	* this is safe
	*/
	function offsetUnset($offset){
		return unlink($this->dir . $this->sanitize($offset));
	}
	function dataGet($offset){
		if(!$this->offsetExists($offset))
			return null;
		$data = unserialize(file_get_contents($this->dir . $this->sanitize($offset)));
		
		return $data;
	}
	
	/**
	 * saves data to file, file timestamp is used to calculate expiry
	 */
	function dataSet($offset, $value, $expiry, $rel=true){
		$expiry = $rel ? $expiry + time() : $expiry;
		
		$toSave = serialize($value);
		file_put_contents($this->dir . $this->sanitize($offset), $toSave);
		chmod($this->dir . $this->sanitize($offset), 0777);
	}
	/**
	* takes limit oldest files, and deletes them if they exceeds expiry
	*/
	function gc($limit = 20){
	}
	
	/**
	* sanitizes string, so that not cache files is not deleted ;)
	*/
	function sanitize($string, $anal = true) {
		$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")",
			"_", "=", "+", "[", "{", "]", "}", "\\", "|", ";", ":", "\"", "'",
			"&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
			"â€”", "â€“", ",", "<", ".", ">", "/", "?");
		
		$clean = trim(str_replace($strip, "", strip_tags($string)));
		$clean = preg_replace('/\s+/', "-", $clean);
		$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
		return $clean;
	}
}
?>
