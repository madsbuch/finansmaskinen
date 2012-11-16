<?php
/**
* this model represents a object similar to a facebook update
*/


namespace model\platform;

class Widget extends \model\AbstractModel{
	/**
	* string, title
	*/
	protected $title;
	
	/**
	* some content, depending on the platform, poberbly some helper\layout
	* object
	*/
	protected $content;
}

?>
