<?php
/**
 * file.php contains file handling class
 * 
 * This file contains a class for handling files. This includes opening
 * apending and reading
 * @author Mads Buch <madspbuch@gmail.com>
 * @version 1.0
 * @package sample
 */
 
/**
* File abstractions
* 
* If the class is used for objects, the constructer takes a filename (and path
* relative to the webroot) as argument. This is the lowest level of file
* abstractions.
* The second argument shall be set to false for not opening the file (big files
* whwich need to be copyed ect.)
* The object automatically creates a new file, if the doesn't exist
* This class should only be used as object on files, which are to be edited
*/
namespace core;
class file{
	
	private $fileHandle, $fileName;
	
	/**
	* to create an object
	* 
	* @param string $fileName file to open. Relative to webroot
	*/
	function __construct($fileName){
		$this->fileName = $fileName;
		$this->open();
	}

	function __destruct(){
		$this->close();
	}
	
	/**
	* Opens the file for writing
	* 
	* This function opens the file for writing (the pointer is placed at the end)
	* The function has to be called for editing files
	*/
	function open($mode = "a+"){
		$this->fileHandle = fopen($this->fileName, $mode);
	}
	
	/**
	* closes the file
	* 
	* this function closes the file. The function is called by the destructer, so
	* it is not importen to do this when you are finished.
	*/
	function close(){
		if(is_resource($this->fileHandle)){
			fclose($this->fileHandle);
		}
	}
	
	/**
	* append data to a file
	* 
	* This function is for appending data to a file
	*/
	public function append($string){
		if(is_resource($this->fileHandle)){
			fwrite ($this->fileHandle, $string);
			return true;
		}
		return false;
	}
	
	/**
	* move a file
	* 
	* This funktion will move the file, if there isn't created an object of the
	* class, second argument has to be given.
	*/
	public function mv($dest, $source = false){
		if($source == false){
			$name = $this->fileName;
			$this->close();
			$this->fileName = $dest;
		}
		else
			$name = $oldName;
	
		rename($name, $dest);
		
		if(!$oldName){
			$this->open();
		}
	}
	
	/**
	* copy a file
	* 
	* This funktion copies a file. If there isn't created an object of the
	* class, second argument has to be given.
	* The handle will stay at the old file
	*/
	public function cp($dest, $source = false){
		if($source == false){
			$source = $this->fileName;
		}
		copy($source, $dest);
	}
	
	public function emptyFile(){
		$this->close();
		$this->fileHandle = $this->open("w");
		$this->close();
		$this->fileHandle = $this->open();
	}
}
?>
