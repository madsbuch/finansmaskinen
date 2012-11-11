<?php
/**
* representation of a file.
*
* this holds metadata, and a handle to a fileobject
*/


namespace model\platform;

class File extends \model\AbstractModel{
	/**
	* the id of file, used for retrieval
	*/
	protected $_id;
	
	/**
	* some checksum
	*/
	protected $_sha1;
	
	/**
	* string, the filename
	*/
	protected $name;
	
	/**
	* mime type
	*/
	protected $mime;
	
	/**
	* groups that may access the file
	*/
	protected $groups;
	
	/**
	* the actual file, of type \helper\file\File
	*/
	protected $file;
}

?>
